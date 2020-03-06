<?php declare(strict_types = 1);

/**
 * Serializer.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Interfaces\ISerializer;
use XEAF\Rack\API\Utils\Exceptions\SerializerException;

/**
 * Реализует методы сериализации данных
 *
 * @package XEAF\Rack\API\Utils
 */
class Serializer implements ISerializer {

    /**
     * Представление пустого объекта JSON
     */
    public const EMPTY_JSON = '{}';

    /**
     * Максимальная глубина просмотра массивов и объектов
     */
    private const DEPTH = 512;

    /**
     * Поле сохранения данных
     */
    private const DATA_FIELD = 'data';

    /**
     * Поле сохранения хеша
     */
    private const HASH_FIELD = 'hash';

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function jsonArrayEncode(array $data): string {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw SerializerException::serializationError($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonArrayDecode(string $json): array {
        try {
            return json_decode($json, true, self::DEPTH, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw SerializerException::invalidJsonFormat($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonObjectEncode(object $obj = null): string {
        $result = self::EMPTY_JSON;
        if ($obj != null) {
            try {
                return json_encode($obj, JSON_THROW_ON_ERROR);
            } catch (Throwable $exception) {
                throw SerializerException::serializationError($exception);
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function jsonObjectDecode(string $json): object {
        try {
            return json_decode($json, false, self::DEPTH, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw SerializerException::invalidJsonFormat($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonDataObjectEncode(DataObject $dataObject = null): string {
        $result = self::EMPTY_JSON;
        try {
            if ($dataObject != null) {
                $result = $this->jsonArrayEncode($dataObject->toArray());
            }
        } catch (Throwable $exception) {
            throw SerializerException::serializationError($exception);
        }
        return $result;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonDataObjectDecode(string $json): DataObject {
        $data = $this->jsonArrayDecode($json);
        return DataObject::fromArray($data);
    }

    /**
     * @inheritDoc
     */
    public function jsonCollectionEncode(Collection $list): string {
        return $this->jsonArrayEncode($list->toArray());
    }

    /**
     * @inheritDoc
     */
    public function jsonCollectionDecode(string $json): Collection {
        $result = new Collection();
        $data   = $this->jsonArrayDecode($json);
        try {
            foreach ($data as $item) {
                $result->push(DataObject::fromArray($item));
            }
        } catch (Throwable $exception) {
            throw SerializerException::serializationError($exception);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function jsonDecodeFileArray(string $fileName, bool $comments = false): array {
        $result = [];
        $fs     = FileSystem::getInstance();
        if ($fs->fileExists($fileName)) {
            $json = file_get_contents($fileName);
            if ($comments) {
                $json = preg_replace('!/\*.*?\*/!s', '', $json);
                $json = preg_replace('/\n\s*\n/', "\n", $json);
                /** @noinspection RegExpRedundantEscape */
                $json = preg_replace('/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/', '', $json);
            }
            $result = $this->jsonArrayDecode($json);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function jsonDecodeFileObject(string $fileName, bool $comments = false): object {
        $result = $this->jsonDecodeFileArray($fileName, $comments);
        return (object)$result;
    }

    /**
     * @inheritDoc
     */
    public function serialize($data, string $password = ''): string {
        $result  = serialize($data);
        $strings = Strings::getInstance();
        if (!$strings->isEmpty($password)) {
            $crypto = Crypto::getInstance();
            $data   = [
                self::DATA_FIELD => $crypto->base64Encode($result),
                self::HASH_FIELD => $crypto->hash($result, $password)
            ];
            $result = $this->jsonArrayEncode($data);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $serialized, string $password = '') {
        $strings = Strings::getInstance();
        if ($strings->isEmpty($password)) {
            $result = unserialize($serialized);
        } else {
            $arr  = $this->jsonArrayDecode($serialized);
            $data = $arr[self::DATA_FIELD] ?? null;
            $hash = $arr[self::HASH_FIELD] ?? null;
            if ($data && $hash) {
                $data    = base64_decode($data);
                $crypto  = Crypto::getInstance();
                $newHash = $crypto->hash($data, $password);
                if ($crypto->hashEquals($newHash, (string) $hash)) {
                    $result = unserialize($data);
                } else {
                    throw SerializerException::dataHashValidationError();
                }
            } else {
                throw SerializerException::dataHashValidationError();
            }
        }
        return $result;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\ISerializer
     */
    public static function getInstance(): ISerializer {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ISerializer);
        return $result;
    }
}

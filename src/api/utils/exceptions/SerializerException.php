<?php

/**
 * SerializerException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при сериализации данных
 *
 * @package XEAF\Rack\API\Utils\Exceptions
 */
class SerializerException extends Exception {

    /**
     * Некорректный формат данных JSON
     */
    public const INVALID_JSON_FORMAT = 'SRZ001';

    /**
     * Ошибка сериализации данных
     */
    public const DATA_SERIALIZATION_ERROR = 'SRZ002';

    /**
     * Ошибка восстановления данных
     */
    public const DATA_UNSERIALIZATION_ERROR = 'SRZ003';

    /**
     * Ошибка проверки хеша данных
     */
    public const HASH_VALIDATION_ERROR = 'SRZ004';

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = null;
        switch ($code) {
            case self::INVALID_JSON_FORMAT:
                $result = 'Invalid JSON format.';
                break;
            case self::DATA_SERIALIZATION_ERROR:
                $result = 'Data serialization error.';
                break;
            case self::DATA_UNSERIALIZATION_ERROR:
                $result = 'Data unserialization error.';
                break;
            case self::HASH_VALIDATION_ERROR:
                $result = 'Hash validation error.';
                break;
        }
        return $result;
    }

    /**
     * Некорректный формат данных JSON
     *
     * @param \Throwable $previous Причина возникновения ошибки
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public static function invalidJsonFormat(Throwable $previous): self {
        return new self(self::INVALID_JSON_FORMAT, [], $previous);
    }

    /**
     * Ошибка сериализации данных
     *
     * @param \Throwable $previous Причина возникновения ошибки
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public static function serializationError(Throwable $previous): self {
        return new self(self::DATA_SERIALIZATION_ERROR, [], $previous);
    }

    /**
     * Ошибка восстановления данных
     *
     * @param \Throwable $previous Причина возникновения ошибки
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public static function unserializationError(Throwable $previous): self {
        return new self(self::DATA_UNSERIALIZATION_ERROR, [], $previous);
    }

    /**
     * Ошибка проверки хеша данных
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public static function dataHashValidationError(): self {
        return new self(self::HASH_VALIDATION_ERROR);
    }
}

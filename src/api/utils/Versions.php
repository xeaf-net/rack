<?php declare(strict_types = 1);

/**
 * Versions.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IVersions;

/**
 * Реализует методы получения информации о версии
 *
 * @package XEAF\Rack\API\Utils
 */
class Versions implements IVersions {

    /**
     * Неизвестный номер версии
     */
    public const UNKNOWN = '0.0.0';

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * Возвращает версию приложения
     *
     * @return string
     */
    public function getAppVersion(): string {
        $filePath = __RACK_VENDOR_DIR__ . '/../composer.json';
        return $this->readComposerVersion($filePath);
    }

    /**
     * Возвращает версию библиотеки
     *
     * @return string
     */
    public function getRackVersion(): string {
        $filePath = __DIR__ . '/../../../composer.json';
        return $this->readComposerVersion($filePath);
    }

    /**
     * Читает номер версии из файла composer.json
     *
     * @param string $filePath Путь к файлу
     *
     * @return string
     */
    protected function readComposerVersion(string $filePath): string {
        try {
            $serializer = Serializer::getInstance();
            $composer   = $serializer->jsonDecodeFileArray($filePath);
            $result     = $composer['version'] ?? self::UNKNOWN;
        } catch (Throwable $exception) {
            $result = self::UNKNOWN;
            Logger::getInstance()->exception($exception);
        }
        return $result;
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IVersions
     */
    public static function getInstance(): IVersions {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IVersions);
        return $result;
    }

}

<?php declare(strict_types = 1);

/**
 * FileStorageConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Config;

use XEAF\Rack\API\App\Factory;

/**
 * Содержит параметры конфигурации файлового хранилища Ключ-Значение
 *
 * @property-read string $path Путь к директории хранилища
 *
 * @package XEAF\Rack\API\Models\Config
 */
class FileStorageConfig extends StorageConfig {

    /**
     * Путь к директории хранилища по умолчанию
     */
    private const DEFAULT_PATH = '/tmp';

    /**
     * Путь к директории хранилища
     * @var string
     */
    private $_path = self::DEFAULT_PATH;

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        parent::parseConfigurationSection($data);
        $this->_path   = $data->{'path'} ?? self::DEFAULT_PATH;
    }

    /**
     * Возвращает путь к директории хранилища
     *
     * @return string
     */
    public function getPath(): string {
        return $this->_path;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\API\Models\Config\FileStorageConfig
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): FileStorageConfig {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof FileStorageConfig);
        return $result;
    }
}

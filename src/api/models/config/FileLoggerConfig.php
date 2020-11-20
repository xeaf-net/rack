<?php declare(strict_types = 1);

/**
 * FileLoggerConfig.php
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
 * Содержит параметры конфигурации файлового журнала операций
 *
 * @property-read string $path Путь к директории файлов журнала
 *
 * @package XEAF\Rack\API\Models\Config
 */
class FileLoggerConfig extends LoggerConfig {

    /**
     * Путь к директории файлов журнала по умолчанию
     */
    private const DEFAULT_PATH = '/tmp';

    /**
     * Путь к директории файлов журнала
     * @var string
     */
    private string $_path = self::DEFAULT_PATH;

    /**
     * Префикс имени файла по умолчанию
     */
    private const DEFAULT_PREFIX = 'xeaf';

    /**
     * Префикс имени файла
     * @var string
     */
    private string $_prefix = self::DEFAULT_PREFIX;

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        parent::parseConfigurationSection($data);
        $this->_path   = $data->{'path'} ?? self::DEFAULT_PATH;
        $this->_prefix = $data->{'prefix'} ?? self::DEFAULT_PREFIX;
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
     * Возвращает префикс имени файла
     *
     * @return string
     */
    public function getPrefix(): string {
        return $this->_prefix;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\API\Models\Config\FileLoggerConfig
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): FileLoggerConfig {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof FileLoggerConfig);
        return $result;
    }
}

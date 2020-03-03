<?php

/**
 * Configuration.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\App;

use XEAF\Rack\API\Interfaces\IConfiguration;
use XEAF\Rack\API\Utils\Exceptions\ConfigurationException;
use XEAF\Rack\API\Utils\Exceptions\SerializerException;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\Serializer;
use XEAF\Rack\API\Utils\Strings;

/**
 * Реализует методы обработки файла конфигурации
 *
 * @package XEAF\Rack\API\App
 */
class Configuration implements IConfiguration {

    /**
     * Имя файла конфигурации
     */
    protected const FILE_NAME = 'config';

    /**
     * Расширение имени файла конфигурации
     */
    protected const FILE_NAME_EXT = '.json';

    /**
     * Имя файла конфигурации
     * @var string
     */
    private $_filePath = null;

    /**
     * Разобранные данные файла конфигурации
     * @var \XEAF\Rack\API\Core\DataObject
     */
    private $_data = null;

    /**
     * Конструктор класса
     *
     * @param string|null $filePath Путь к файлу конфигурации
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct(string $filePath = null) {
        $this->_filePath = $filePath ? $filePath : $this->configFilePath();
        $this->readConfigFile();
    }

    /**
     * Возращает путь к файлу конфигурации
     *
     * @return string
     */
    protected function configFilePath(): string {
        $prefix = __RACK_CONFIG_DIR__ . '/' . self::FILE_NAME;
        $result = $prefix . self::FILE_NAME_EXT;
        $host   = $_SERVER['SERVER_NAME'] ?? '';
        if ($host) {
            $fs       = FileSystem::getInstance();
            $hostFile = $prefix . '-' . $host . self::FILE_NAME_EXT;
            if ($fs->fileExists($hostFile)) {
                $result = $hostFile;
            }
        }
        return $result;
    }

    /**
     * Читает данные файла конфигурации
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    protected function readConfigFile(): void {
        $fs = FileSystem::getInstance();
        if ($fs->fileExists($this->_filePath)) {
            try {
                $sz          = Serializer::getInstance();
                $this->_data = $sz->jsonDecodeFileObject($this->_filePath, true);
            } catch (SerializerException $exception) {
                throw ConfigurationException::parsingError($exception);
            }
        } else {
            throw ConfigurationException::fileNotFound($this->_filePath);
        }
    }

    /**
     * @inheritDoc
     */
    public function getParameters(string $section, string $subsection = '', bool $mustExists = true): ?object {
        $result = $this->_data->{$section} ?? null;
        if ($result != null && $subsection != '') {
            $result = $result[$subsection] ?? null;
        }
        if ($result == null && $mustExists) {
            throw ConfigurationException::sectionNotFound($section, $subsection);
        }
        return !is_string($result) ? (object)$result : $this->parseProviderConfig($result);
    }

    /**
     * Разбирает параметры конфигурации провайдера
     *
     * @param string|null $data Строковые параметры конфигурации
     *
     * @return object
     */
    private function parseProviderConfig(string $data = null): ?object {
        $strings = Strings::getInstance();
        $result  = $strings->parseDSN($data);
        return (object)$result;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\IConfiguration
     */
    public static function getInstance(): IConfiguration {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IConfiguration);
        return $result;
    }
}

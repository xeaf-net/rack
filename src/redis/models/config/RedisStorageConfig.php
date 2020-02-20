<?php

/**
 * RedisStorageConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Redis\Models\Config;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Models\Config\StorageConfig;
use XEAF\Rack\API\Utils\Strings;

/**
 * Содержит параметры конфигурации подключания к серверу Redis
 *
 * @property-read string $host    Имя хоста
 * @property-read int    $port    Порт
 * @property-read string $auth    Данные для авторизации
 * @property-read int    $dbindex Индекс базы данных
 *
 * @package XEAF\Rack\Redis\Models\Config
 */
class RedisStorageConfig extends StorageConfig {

    /**
     * Имя хоста по умолчанию
     */
    protected const DEFAULT_HOST = 'localhost';

    /**
     * Порт Redis по умолчанию
     */
    protected const DEFAULT_PORT = 6379;

    /**
     * Параметры авторизации по умолчанию
     */
    protected const DEFAULT_AUTH = '';

    /**
     * Индекс базы данных по умолчанию
     */
    protected const DEFAULT_DBINDEX = 0;

    /**
     * Имя хоста
     * @var string
     */
    private $_host = 'localhost';

    /**
     * Порт
     * @var int
     */
    private $_port = self::DEFAULT_PORT;

    /**
     * Данные авторизации
     * @var string
     */
    private $_auth = '';

    /**
     * Индекс базы данных
     * @var int
     */
    private $_dbindex = self::DEFAULT_DBINDEX;

    /**
     * Возвращает имя хоста
     *
     * @return string
     */
    public function getHost(): string {
        return $this->_host;
    }

    /**
     * Возвращает порт
     *
     * @return int
     */
    public function getPort(): int {
        return $this->_port;
    }

    /**
     * Возвращает данные авторизации
     *
     * @return string
     */
    public function getAuth(): string {
        return $this->_auth;
    }

    /**
     * Возвращает индекс базы данных
     *
     * @return int
     */
    public function getDbindex(): int {
        return $this->_dbindex;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        parent::parseConfigurationSection($data);
        $strings        = Strings::getInstance();
        $this->_host    = $data->{'host'} ?? self::DEFAULT_HOST;
        $this->_port    = $strings->stringToInteger($data->{'port'} ?? null, self::DEFAULT_PORT);
        $this->_auth    = $data->{'auth'} ?? self::DEFAULT_AUTH;
        $this->_dbindex = $strings->stringToInteger($data->{'dbindex'} ?? null, self::DEFAULT_DBINDEX);
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\Redis\Models\Config\RedisStorageConfig
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): RedisStorageConfig {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof RedisStorageConfig);
        return $result;
    }
}

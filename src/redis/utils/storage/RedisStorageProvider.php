<?php

/**
 * RedisStorageProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Redis\Utils\Storage;

use Redis;
use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\IStorageProvider;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Serializer;
use XEAF\Rack\Redis\Models\Config\RedisStorageConfig;
use XEAF\Rack\Redis\Utils\Exceptions\RedisException;

/**
 * Реализует методы работы с сервером Redis
 *
 * @package XEAF\Rack\Redis\Utils\Storage
 */
class RedisStorageProvider extends KeyValue implements IStorageProvider {

    use NamedObjectTrait;

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'redis';

    /**
     * Подключение к серверу Redis
     * @var \Redis|null
     */
    private $_redis = null;

    /**
     * Параметры конфигурации сервера Redis
     * @var \XEAF\Rack\Redis\Models\Config\RedisStorageConfig|null
     */
    private $_config = null;

    /**
     * Объект методов сериализации
     * @var \XEAF\Rack\API\Interfaces\ISerializer|null
     */
    private $_serializer = null;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name       = $name;
        $this->_redis      = new Redis();
        $this->_config     = RedisStorageConfig::getInstance($name);
        $this->_serializer = Serializer::getInstance();
        $this->connect();
    }

    /**
     * Создает подключение к серверу Redis
     *
     * @return void
     * @throws \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public function connect(): void {
        try {
            $this->_redis->connect($this->_config->getHost(), $this->_config->getPort());
            if ($this->_config->getAuth()) {
                $this->_redis->auth($this->_config->getAuth());
            }
            $this->selectDatabase($this->_config->getDbindex());
        } catch (Throwable $reason) {
            throw  RedisException::connectionError($this->_name, $reason);
        }
    }

    /**
     * Выбор базы данных
     *
     * @param int $dbindex Индекс базы данных
     *
     * @return void
     * @throws \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public function selectDatabase(int $dbindex = 0): void {
        try {
            $this->_redis->select($dbindex);
        } catch (Throwable $reason) {
            throw RedisException::dbindexError($this->_name, $dbindex, $reason);
        }
    }

    /**
     * Получает связанное с ключом значение
     *
     * @param string      $key          Ключ
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     * @throws \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public function get(string $key, $defaultValue = null) {
        try {
            $data = $this->_redis->get($key);
            if ($data === false) {
                $result = $defaultValue;
            } else {
                $result = $this->_serializer->unserialize($data);
            }
            return $result;
        } catch (Throwable $reason) {
            throw RedisException::gettingError($this->_name, $reason);
        }
    }

    /**
     * Сохраняет связанное с ключом значение
     *
     * @param string     $key   Ключ
     * @param mixed|null $value Значение
     * @param int        $ttl   Время жизни в секундах
     *
     * @return void
     * @throws \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public function put(string $key, $value = null, int $ttl = 0): void {
        try {
            $data = $this->_serializer->serialize($value);
            if ($ttl == 0) {
                $this->_redis->setex($key, $this->_config->getTtl(), $data);
            } else {
                $this->_redis->setex($key, $ttl, $data);
            }
        } catch (Throwable $reason) {
            throw RedisException::puttingError($this->_name, $reason);
        }
    }

    /**
     * Удаляет связанное с ключом значение
     *
     * @param string $key Ключ
     *
     * @return void
     * @throws \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public function delete(string $key): void {
        try {
            $this->_redis->del($key);
        } catch (Throwable $reason) {
            throw RedisException::puttingError($this->_name, $reason);
        }
    }

    /**
     * Возвращает признак существования значения
     *
     * @param string $key Ключ
     *
     * @return bool
     */
    public function exists(string $key): bool {
        return $this->_redis->exists($key);
    }

    /**
     * Закрывает подключение к серверу Redis
     *
     * @return void
     */
    public function disconnect(): void {
        $this->_redis->close();
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\Redis\Utils\Storage\RedisStorageProvider
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): RedisStorageProvider {
        $result = Factory::getFactoryNamedObject(RedisStorageProvider::class, $name);
        assert($result instanceof RedisStorageProvider);
        return $result;
    }
}

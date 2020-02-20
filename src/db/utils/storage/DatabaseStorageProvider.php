<?php

/**
 * DatabaseStorageProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Utils\Storage;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\IStorageProvider;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Calendar;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Serializer;
use XEAF\Rack\API\Utils\Storage;
use XEAF\Rack\Db\Models\Config\DatabaseStorageConfig;
use XEAF\Rack\Db\Utils\Database;
use XEAF\Rack\Db\Utils\Exceptions\DatabaseException;

/**
 * Реализует методы провайдера хранилища значений в базе данных
 *
 * @package XEAF\Rack\Db\Utils\Storage
 */
class DatabaseStorageProvider extends KeyValue implements IStorageProvider {

    use NamedObjectTrait;

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'database';

    /**
     * Параметры конфигурации
     * @var \XEAF\Rack\Db\Models\Config\DatabaseStorageConfig|null
     */
    private $_config = null;

    /**
     * Подключение к базе данных
     * @var \XEAF\Rack\Db\Interfaces\IDatabase|null
     */
    private $_database = null;

    /**
     * Объект методов сериализации
     * @var \XEAF\Rack\API\Interfaces\ISerializer|null
     */
    private $_serializer = null;

    /**
     * @var \XEAF\Rack\API\Interfaces\ILogger| null
     */
    private $_logger = null;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name       = $name;
        $this->_config     = DatabaseStorageConfig::getInstance($name);
        $this->_logger     = Logger::getInstance();
        $this->_database   = Database::getInstance($this->_config->getConnection());
        $this->_serializer = Serializer::getInstance();
        $this->loadStorageValues();
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function clear(): void {
        $data = $this->toArray();
        foreach ($data as $key => $value) {
            $this->delete($key);
        }
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function put(string $key, $value = null, int $ttl = 0): void {
        parent::put($key, $value);
        $this->saveStorageValue($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function delete(string $key): void {
        parent::delete($key);
        $this->deleteStorageValue($key);
    }

    /**
     * Загружает данные хранилища из базы данных
     *
     * @return void
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    private function loadStorageValues(): void {
        $sql  = $this->getSelectSQL();
        $now  = Calendar::getInstance()->now();
        $data = $this->_database->select($sql, ['name' => $this->getName()]);
        foreach ($data as $record) {
            $key = $record['storage_key'];
            $ttl = $this->_database->sqlDateTime($record['storage_validity']);
            if ($ttl >= $now) {
                $value = $this->_serializer->unserialize($record['storage_value']);
                parent::put($key, $value);
            } else {
                $this->delete($key);
            }
        }
    }

    /**
     * Сохраняет значение в базе данных
     *
     * @param string $key   Ключ
     * @param mixed  $value Значение
     * @param int    $ttl   Время жизни
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    private function saveStorageValue(string $key, $value, int $ttl): void {
        $now      = Calendar::getInstance()->now();
        $validity = $ttl != 0 ? $now + $ttl : $now + Storage::MAX_TTL;
        $params   = [
            'name'     => $this->getName(),
            'key'      => $key,
            'value'    => $this->_serializer->serialize($value),
            'validity' => $this->_database->formatDateTime($validity)
        ];
        $this->_database->startTransaction();
        try {
            $sql = $this->getUpdateSQL();
            $cnt = $this->_database->execute($sql, $params);
            if (!$cnt) {
                $sql = $this->getInsertSQL();
                $this->_database->execute($sql, $params);
            }
            $this->_database->commitTransaction();
        } catch (DatabaseException $exception) {
            $this->_database->rollbackTransaction();
            $this->_logger->exception($exception);
        }
    }

    /**
     * Удаляет значение из базы данных
     *
     * @param string $key Ключ
     *
     * @return void
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    private function deleteStorageValue(string $key): void {
        $sql = $this->getDeleteSQL();
        $this->_database->startTransaction();
        try {
            $this->_database->execute($sql, ['name' => $this->getName(), 'key' => $key]);
            $this->_database->commitTransaction();
        } catch (DatabaseException $exception) {
            $this->_database->rollbackTransaction();
            $this->_logger->exception($exception);
        }
    }

    /**
     * Возвращает текст SQL запроса выбора записей
     *
     * @return string
     * @noinspection RedundantSuppression
     */
    private function getSelectSQL(): string {
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        return '
            select * from xeaf_storage
                where storage_name = :name';
    }

    /**
     * Возвращает текст SQL команды добавления записи
     *
     * @return string
     * @noinspection RedundantSuppression
     */
    private function getInsertSQL(): string {
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        return '
            insert into xeaf_storage (
                storage_name, 
                storage_key, 
                storage_value, 
                storage_validity
            ) values (
                :name,
                :key,
                :value,
                :validity
            )';
    }

    /**
     * Возвращает текст SQL команды изменения записей
     *
     * @return string
     * @noinspection RedundantSuppression
     */
    private function getUpdateSQL(): string {
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        return '
            update xeaf_storage
                set
                    storage_value    = :value,
                    storage_validity = :validity
                where 
                    storage_name     = :name and
                    storage_key      = :key';
    }

    /**
     * Возвращает текст SQL команды удаления записи
     *
     * @return string
     * @noinspection RedundantSuppression
     */
    private function getDeleteSQL(): string {
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        return '
            delete from xeaf_storage
                where
                    storage_name = :name and
                    storage_key  = :key';
    }
}

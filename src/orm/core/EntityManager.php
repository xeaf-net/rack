<?php

/**
 * EntityManager.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Core;

use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\Db\Interfaces\IDatabase;
use XEAF\Rack\Db\Utils\Database;
use XEAF\Rack\Db\Utils\Exceptions\DatabaseException;
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Generator;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы менеджера сущностей
 *
 * @package XEAF\Rack\ORM\Core
 */
abstract class EntityManager {

    /**
     * Подключение к базе данных
     * @var \XEAF\Rack\Db\Utils\Database
     */
    private $_db = null;

    /**
     * Определения моделей сущностей
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_entities = null;

    /**
     * Определения имен сущностей в разрезе имент таблиц
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_entityTables = null;

    /**
     * Классы сущностей
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_entityClasses = null;
    /**
     * Хранилище отслеживаемых объектов
     * @var IKeyValue
     */
    private $_watchedEntities = null;

    /**
     * Хранилище оригинальных версий отслеживаемых сущностей
     * @var IKeyValue
     */
    private $_watchedOriginals = null;

    /**
     * Конструктор класса
     *
     * @param string $connection Имя подключения к базе данных
     * @param array  $entities   Определения сущностей
     */
    public function __construct(string $connection, array $entities) {
        $this->_db               = Database::getInstance($connection);
        $this->_entities         = new KeyValue();
        $this->_entityTables     = new KeyValue();
        $this->_entityClasses    = new KeyValue();
        $this->_watchedEntities  = new KeyValue();
        $this->_watchedOriginals = new KeyValue();
        foreach ($entities as $name => $className) {
            $item = new $className();
            assert($item instanceof Entity);
            $this->_entities->put($name, $item->getModel());
            $this->_entityTables->put($item->getModel()->getTableName(), $name);
            $this->_entityClasses->put($name, $className);
        }
    }

    /**
     * Возващает подключение к базе данных
     *
     * @return \XEAF\Rack\Db\Interfaces\IDatabase
     */
    public function getDb(): IDatabase {
        return $this->_db;
    }

    /**
     * Возвращает модели данных сущностей
     *
     * @return \XEAF\Rack\API\Interfaces\IKeyValue
     */
    public function getEntities(): IKeyValue {
        return $this->_entities;
    }

    /**
     * Возвращает имя класса сущности по имени сущности
     *
     * @param string $entity Имя сущности
     *
     * @return string
     */
    public function getEntityClass(string $entity): string {
        return $this->_entityClasses->get($entity);
    }

    /**
     * Возвращает имя сущности по имени таблицы
     *
     * @param string $tableName Имя таблицы
     *
     * @return string|null
     */
    public function findByTableName(string $tableName): ?string {
        return $this->_entityTables->get($tableName);
    }

    /**
     * Возвращает новый объект запроса
     *
     * @param string $xql Текст XQL запроса
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function query(string $xql): EntityQuery {
        return new EntityQuery($this, $xql);
    }

    /**
     * Открывает новую транзакцию
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function startTransaction(): void {
        try {
            $this->_db->startTransaction();
        } catch (DatabaseException $exception) {
            throw EntityException::internalError($exception);
        }
    }

    /**
     * Подтверждает изменения в транзакции
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function commit(): void {
        try {
            $this->_db->commitTransaction();
        } catch (DatabaseException $exception) {
            throw EntityException::internalError($exception);
        }
    }

    /**
     * Отменяет изменения в транзакции
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function rollback(): void {
        try {
            $this->_db->rollbackTransaction();
        } catch (DatabaseException $exception) {
            throw EntityException::internalError($exception);
        }
    }

    /**
     * Возвращает объект сущности по первичному ключу
     *
     * @param string $entityName Имя объекта сущностей
     * @param array  $params     Параметры значений первичного ключа
     *
     * @return \XEAF\Rack\ORM\Core\Entity|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function findByPK(string $entityName, array $params): ?Entity {
        $result = null;
        $model  = $this->_entities->get($entityName);
        if ($model) {
            assert($model instanceof EntityModel);
            $xql = "e from $entityName where ";
            $pks = $model->getPrimaryKeyNames();
            foreach ($pks as $pk) {
                $xql .= "$pk == :$pk &&";
            }
            $query  = $this->query(rtrim($xql, '&'));
            $result = $query->getFirst($params);
            if ($result) {
                assert($result instanceof Entity);
            }
        } else {
            throw EntityException::unknownEntity($entityName);
        }
        return $result;
    }

    /**
     * Сохранение изменений сущности в базе данных
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return \XEAF\Rack\ORM\Core\Entity
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function persist(Entity $entity): Entity {
        try {
            return $this->isWatching($entity) ? $this->persistUpdate($entity) : $this->persistInsert($entity);
        } catch (DatabaseException $dbe) {
            throw EntityException::internalError($dbe);
        }
    }

    /**
     * Сохраняет изменения списка сущностей в базе данных
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $collection Список сущностей
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function persistList(ICollection $collection): void {
        foreach ($collection as $entity) {
            assert($entity instanceof Entity);
            $this->persist($entity);
        }
    }

    /**
     * Сохраняет изменения сущности посредством создания новой записи
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return \XEAF\Rack\ORM\Core\Entity
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function persistInsert(Entity $entity): Entity {
        $sql           = Generator::getInstance()->insertSQL($entity);
        $model         = $entity->getModel();
        $parameters    = [];
        $autoIncrement = null;
        foreach ($model->getPropertyByNames() as $name => $property) {
            assert($property instanceof PropertyModel);
            if (!$property->getAutoIncrement()) {
                $parameters[$name] = $this->parameterValue($name, $entity);
            } else {
                $autoIncrement = $name;
            }
        }
        $this->_db->execute($sql, $parameters);
        if ($autoIncrement != null) {
            $entity->{$autoIncrement} = $this->_db->lastInsertId();
        }
        $this->watch($entity);
        return $entity;
    }

    /**
     * Сохраняет изменения сущности посредством изменения записи
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return \XEAF\Rack\ORM\Core\Entity
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    protected function persistUpdate(Entity $entity): Entity {
        if ($this->isModified($entity)) {
            $sql        = Generator::getInstance()->updateSQL($entity);
            $model      = $entity->getModel();
            $properties = $model->getPropertyByNames();
            $parameters = [];
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                if (!$property->getReadOnly() && !$property->getAutoIncrement()) {
                    $parameters[$name] = $this->parameterValue($name, $entity);
                }
            }
            $this->_db->execute($sql, $parameters);
        }
        return $entity;
    }

    /**
     * Удаляет объект сущности
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function delete(Entity $entity): void {
        if ($this->isWatching($entity)) {
            $sql        = Generator::getInstance()->deleteSQL($entity);
            $model      = $entity->getModel();
            $parameters = [];
            foreach ($model->getPrimaryKeyNames() as $primaryKey) {
                $parameters[$primaryKey] = $entity->{$primaryKey};
            }
            try {
                $this->_db->execute($sql, $parameters);
                $this->stopWatch($entity);
            } catch (DatabaseException $exception) {
                throw EntityException::internalError($exception);
            }
        }
    }

    /**
     * Удаляет объекты сущностей из базы данных
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $collection Список сущностей
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function deleteList(ICollection $collection): void {
        foreach ($collection as $entity) {
            assert($entity instanceof Entity);
            $this->delete($entity);
        }
    }

    /**
     * Запускает слежение за объектом сущности
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return \XEAF\Rack\ORM\Core\Entity
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function watch(Entity $entity): Entity {
        $result = $entity;
        $key    = $entity->getEntityWatchingId();
        if ($key != null) {
            if (!$this->isWatching($entity)) {
                $this->_watchedEntities->put($key, $entity);
                $this->_watchedOriginals->put($key, clone $entity);
            } else {
                $result = $this->_watchedEntities->get($key);
            }
        } else {
            throw EntityException::primaryKeyIsNull();
        }
        return $result;
    }

    /**
     * Отменяет слежение за объектом
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return void
     */
    public function stopWatch(Entity $entity): void {
        if ($this->isWatching($entity)) {
            $key = $entity->getEntityWatchingId();
            $this->_watchedEntities->delete($key);
            $this->_watchedOriginals->delete($key);
        }
    }

    /**
     * Возвращает признак слежения за объектом
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return bool
     */
    public function isWatching(Entity $entity): bool {
        $key = $entity->getEntityWatchingId();
        return $this->_watchedEntities->get($key) != null;
    }

    /**
     * Возвращает признак изменения значения свойств сушности после начала слежения
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return bool
     */
    public function isModified(Entity $entity): bool {
        $result = !$this->isWatching($entity);
        if (!$result) {
            $key      = $entity->getEntityWatchingId();
            $original = $this->_watchedOriginals->get($key);
            assert($original instanceof Entity);
            $properties = $original->getModel()->getPropertyByNames();
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                if (!$property->getReadOnly()) {
                    if ($entity->{$name} != $original->{$name}) {
                        $result = true;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Восстанавливает оригинальные значения свойств объекта сущности
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return void
     */
    public function restore(Entity $entity): void {
        if (!$this->isWatching($entity)) {
            $key      = $entity->getEntityWatchingId();
            $original = $this->_watchedOriginals->get($key);
            assert($original instanceof Entity);
            $properties = $original->getModel()->getPropertyByNames();
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                if (!$property->getReadOnly()) {
                    $entity->{$name} = $original->{$name};
                }
            }
        }
    }

    /**
     * Возвращает строковое представление значения параметра
     *
     * @param string                     $name   Имя параметра
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return string
     */
    private function parameterValue(string $name, Entity $entity): string {
        $result   = $entity->{$name};
        $property = $entity->getModel()->getPropertyByName($name);
        switch ($property->getDataType()) {
            case DataTypes::DT_BOOL:
                $result = $this->_db->formatBool($result);
                break;
            case DataTypes::DT_DATE:
                $result = $this->_db->formatDate($result);
                break;
            case DataTypes::DT_DATETIME:
                $result = $this->_db->formatDateTime($result);
                break;
        }
        return $result;
    }
}

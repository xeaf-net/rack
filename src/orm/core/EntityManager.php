<?php declare(strict_types = 1);

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

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\API\Utils\Exceptions\SerializerException;
use XEAF\Rack\API\Utils\Serializer;
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
    private $_db;

    /**
     * Определения моделей сущностей
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_entities;

    /**
     * Определения имен сущностей в разрезе имент таблиц
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_entityTables;

    /**
     * Классы сущностей
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_entityClasses;
    /**
     * Хранилище отслеживаемых объектов
     * @var IKeyValue
     */
    private $_watchedEntities;

    /**
     * Хранилище оригинальных версий отслеживаемых сущностей
     * @var IKeyValue
     */
    private $_watchedOriginals;

    /**
     * Конструктор класса
     *
     * @param string $connection Имя подключения к базе данных
     */
    public function __construct(string $connection = Factory::DEFAULT_NAME) {
        $this->_db               = Database::getInstance($connection);
        $this->_entities         = new KeyValue();
        $this->_entityTables     = new KeyValue();
        $this->_entityClasses    = new KeyValue();
        $this->_watchedEntities  = new KeyValue();
        $this->_watchedOriginals = new KeyValue();
        $this->initEntities($this->declareEntities());
    }

    /**
     * Возвращает массив определений сущностей
     *
     * @return array
     */
    abstract protected function declareEntities(): array;

    /**
     * Возвращает информацию о модели сущности
     *
     * @param string $name Идентификатор сущности
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function __get(string $name) {
        $result = $this->getEntityModel($name);
        if (!$result) {
            throw EntityException::unknownEntity($name);
        }
        return $result;
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
     * Возвращает модель сущности
     *
     * @param string $name Имя сущности
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel
     */
    public function getEntityModel(string $name): ?EntityModel {
        $result = $this->_entities->get($name);
        if ($result) {
            assert($result instanceof EntityModel);
        }
        return $result;
    }

    /**
     * Возвращает имя класса сущности по имени сущности
     *
     * @param string $entity Имя сущности
     *
     * @return string
     */
    public function getEntityClass(string $entity): string {
        return (string)$this->_entityClasses->get($entity);
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
     * Возвращает имя сущности по имени класса
     *
     * @param string $className Имя класса
     *
     * @return string|null
     */
    public function findByClassName(string $className): ?string {
        foreach ($this->_entityClasses as $name => $entityClassName) {
            if ($className == $entityClassName) {
                return $name;
            }
        }
        return null;
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
     * Возвращает объект запроса к сущностям заданного класса
     *
     * @param string $className Имя класса
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function queryEntities(string $className): EntityQuery {
        $name = $this->findByClassName($className);
        if (!$name) {
            throw EntityException::unknownEntityClass($className);
        }
        $xql = $name . ' from ' . $name;
        return $this->query($xql);
    }

    /**
     * Возвращает объект запроса к сущностям по первичному ключу
     *
     * @param string $className
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function queryEntity(string $className): EntityQuery {
        $name  = $this->findByClassName($className);
        $query = $this->queryEntities($className);
        $model = $this->_entities->get($name);
        assert($model instanceof EntityModel);
        $primaryKeys = $model->getPrimaryKeyNames();
        foreach ($primaryKeys as $primaryKey) {
            $query->andWhere("$name.$primaryKey == :$primaryKey");
        }
        return $query;
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
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function findByPK(string $entityName, array $params): ?Entity {
        $result = null;
        $model  = $this->_entities->get($entityName);
        if ($model) {
            assert($model instanceof EntityModel);
            $xql = "e from $entityName e where ";
            $pks = $model->getPrimaryKeyNames();
            foreach ($pks as $pk) {
                $xql .= "e.$pk == :$pk &&";
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
     * Перезапрашивает сущность из БД
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function reload(Entity $entity): void {
        $name   = $this->findByClassName($entity->getClassName());
        $params = $entity->getPrimaryKeyParams();
        $this->stopWatch($entity);
        $double = $this->findByPK($name, $params);
        if (!$double) {
            throw EntityException::reloadError();
        }
        $model      = $entity->getModel();
        $properties = $model->getPropertyByNames();
        foreach ($properties as $name => $property) {
            assert($property instanceof PropertyModel);
            if ($property->getIsReadable()) {
                $entity->{$name} = $double->{$name};
            }
        }
        $this->stopWatch($double);
        $this->watch($entity);
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
            $entity->beforePersist($this);
            $result = $this->isWatching($entity) ? $this->persistUpdate($entity) : $this->persistInsert($entity);
            $entity->afterPersist($this);
            return $result;
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
     * Сораняет изменения самма сущностей
     *
     * @param array $entities Массив сущностей
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function persistArray(array $entities): void {
        foreach ($entities as $entity) {
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
            if ($property->getIsInsertable()) {
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
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function persistUpdate(Entity $entity): Entity {
        if ($this->isModified($entity)) {
            $sql        = Generator::getInstance()->updateSQL($entity);
            $model      = $entity->getModel();
            $properties = $model->getPropertyByNames();
            $parameters = [];
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                if ($property->getIsUpdatable()) {
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
        $entity->beforeDelete($this);
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
        $entity->afterDelete($this);
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
     * Удаляет собъеты сущностей массива из базы данных
     *
     * @param array $entities Массив сущностей
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function deleteArray(array $entities): void {
        foreach ($entities as $entity) {
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
                if ($property->getIsInsertable() || $property->getIsUpdatable()) {
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
                if ($property->getIsReadable()) {
                    $entity->{$name} = $original->{$name};
                }
            }
        }
    }

    /**
     * Инициализирует сущности
     *
     * @param array $entities Определения сущностей
     *
     * @return void
     */
    private function initEntities(array $entities): void {
        foreach ($entities as $name => $className) {
            $item = new $className();
            assert($item instanceof Entity);
            $model = $item->getModel();
            $model->setEntityManager($this);
            $this->_entities->put($name, $model);
            $this->_entityTables->put($model->getTableName(), $name);
            $this->_entityClasses->put($name, $className);
        }
    }

    /**
     * Возвращает строковое представление значения параметра
     *
     * @param string                     $name   Имя параметра
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return string|null
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    private function parameterValue(string $name, Entity $entity): ?string {
        $result = $entity->{$name};
        if ($result === null) {
            return null;
        }
        $property = $entity->getModel()->getPropertyByName($name);
        if ($property != null) {
            try {
                switch ($property->getDataType()) {
                    case DataTypes::DT_UUID:
                        if (!$result) {
                            return null;
                        }
                        break;
                    case DataTypes::DT_BOOL:
                        $result = $this->_db->formatBool((bool)$result);
                        break;
                    case DataTypes::DT_DATE:
                        $result = $this->_db->formatDate((int)$result);
                        break;
                    case DataTypes::DT_DATETIME:
                        $result = $this->_db->formatDateTime((int)$result);
                        break;
                    case DataTypes::DT_ARRAY:
                    case DataTypes::DT_OBJECT:
                        $result = Serializer::getInstance()->serialize($result);
                        break;
                }
            } catch (SerializerException $exception) {
                throw EntityException::internalError($exception);
            }
        }
        return (string)$result;
    }

}

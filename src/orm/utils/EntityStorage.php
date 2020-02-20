<?php

/**
 * EntityStorage.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\ORM\Interfaces\IEntityStorage;
use XEAF\Rack\ORM\Models\EntityModel;

/**
 * Реализует методы хранилища объектов ORM
 *
 * @package XEAF\Rack\ORM\Utils
 */
class EntityStorage implements IEntityStorage {

    /**
     * Хранилище моделей сущностей
     * @var \XEAF\Rack\API\Interfaces\IKeyValue|null
     */
    private $_models = null;

    /**
     * Хранилище SQL команд вставки записи
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_insertSQL = null;

    /**
     * Хранилище SQL команд изменения записи
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_updateSQL = null;

    /**
     * Хранилище SQL команд удаления записи
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_deleteSQL = null;

    /**
     * @inheritDoc
     */
    public function __construct() {
        $this->_models    = new KeyValue();
        $this->_insertSQL = new KeyValue();
        $this->_updateSQL = new KeyValue();
        $this->_deleteSQL = new KeyValue();
    }

    /**
     * @inheritDoc
     */
    public function getModel(string $className): ?EntityModel {
        $result = $this->_models->get($className);
        if ($result != null) {
            assert($result instanceof EntityModel);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function putModel(string $className, EntityModel $entityModel): void {
        $this->_models->put($className, $entityModel);
    }

    /**
     * @inheritDoc
     */
    public function getInsertSQL(string $className): ?string {
        return $this->_insertSQL->get($className);
    }

    /**
     * @inheritDoc
     */
    public function putInsertSQL(string $className, string $sql): void {
        $this->_insertSQL->put($className, $sql);
    }

    /**
     * @inheritDoc
     */
    public function getUpdateSQL(string $className): ?string {
        return $this->_updateSQL->get($className);
    }

    /**
     * @inheritDoc
     */
    public function putUpdateSQL(string $className, string $sql): void {
        $this->_updateSQL->put($className, $sql);
    }

    /**
     * @inheritDoc
     */
    public function getDeleteSQL(string $className): ?string {
        return $this->_deleteSQL->get($className);
    }

    /**
     * @inheritDoc
     */
    public function putDeleteSQL(string $className, string $sql): void {
        $this->_deleteSQL->put($className, $sql);
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\ORM\Interfaces\IEntityStorage
     */
    public static function getInstance(): IEntityStorage {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IEntityStorage);
        return $result;
    }
}

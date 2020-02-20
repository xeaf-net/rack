<?php

/**
 * Factory.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\App;

use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\API\Interfaces\INamedObject;

/**
 * Реализует методы фабрики объектов
 *
 * @package XEAF\Rack\API\App
 */
class Factory {

    /**
     * Имя объекта по умолчанию
     */
    public const DEFAULT_NAME = 'default';

    /**
     * Хранилище экземпляров объектов
     * @var array
     */
    private static $_instances = [];

    /**
     * Возвращает экземпляр объекта класса
     *
     * @param string $className Имя класса
     *
     * @return \XEAF\Rack\API\Interfaces\IFactoryObject
     */
    public static function getFactoryObject(string $className): IFactoryObject {
        $id = self::getObjectId($className, self::DEFAULT_NAME);
        if (!isset(self::$_instances[$id])) {
            self::$_instances[$id] = new $className();
        }
        return self::$_instances[$id];
    }

    /**
     * Задает экземпляр объекта класса
     *
     * @param string                                   $className     Имя класса
     * @param \XEAF\Rack\API\Interfaces\IFactoryObject $factoryObject Экземпляр объекта
     *
     * @return void
     */
    public static function setFactoryObject(string $className, IFactoryObject $factoryObject): void {
        $id                    = self::getObjectId($className, self::DEFAULT_NAME);
        self::$_instances[$id] = $factoryObject;
    }

    /**
     * Возвращает именованный экземпляр объекта класса
     *
     * @param string $className Имя класса
     * @param string $name      Имя объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IFactoryObject
     */
    public static function getFactoryNamedObject(string $className, string $name): IFactoryObject {
        $id = self::getObjectId($className, $name);
        if (!isset(self::$_instances[$id])) {
            $obj = new $className($name);
            assert($obj instanceof INamedObject);
            $obj->setName($name);
            self::$_instances[$id] = $obj;
        }
        return self::$_instances[$id];
    }

    /**
     * Задает именованый экземпляр объекта класса
     *
     * @param string                                   $className     Имя класса
     * @param string                                   $name          Имя объекта
     * @param \XEAF\Rack\API\Interfaces\IFactoryObject $factoryObject Экземпляр объекта
     *
     * @return void
     */
    public static function setFactoryNamedObject(string $className, string $name, IFactoryObject $factoryObject): void {
        $id                    = self::getObjectId($className, $name);
        self::$_instances[$id] = $factoryObject;
    }

    /**
     * Возвращает признак существования экземпляра объекта класса
     *
     * @param string $className Имя класса
     *
     * @return bool
     */
    public static function factoryObjectExists(string $className): bool {
        $id = self::getObjectId($className, self::DEFAULT_NAME);
        return isset(self::$_instances[$id]);
    }

    /**
     * Возвращает признак существования именованного экземпляра объекта класса
     *
     * @param string $className Имя класса
     * @param string $name      Имя объекта
     *
     * @return bool
     */
    public static function factoryNamedObjectExists(string $className, string $name): bool {
        $id = self::getObjectId($className, $name);
        return isset(self::$_instances[$id]);
    }

    /**
     * Возвращает идентификатор хранения объекта
     *
     * @param string $className Имя класса
     * @param string $name      Имя объекта
     *
     * @return string
     */
    protected static function getObjectId(string $className, string $name): string {
        return "$className-$name";
    }
}

<?php

/**
 * Resolver.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IFactoryObject;

/**
 * Реализует методы разрашения отношений
 *
 * @package  XEAF\Rack\ORM\Utils
 */
class Resolver implements IFactoryObject {

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\ORM\Utils\Resolver
     */
    public static function getInstance(): Resolver {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof Resolver);
        return $result;
    }
}

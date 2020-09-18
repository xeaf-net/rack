<?php declare(strict_types = 1);

/**
 * SingletonTrait.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Traits;

use XEAF\Rack\API\App\Factory;

/**
 * Реализует метод получения ссылки на единичный объект
 *
 * @package XEAF\Rack\API\Traits
 */
trait SingletonTrait {

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return static
     */
    public static function getInstance(): self {
        $result = Factory::getFactoryObject(static::class);
        assert($result instanceof static);
        return $result;
    }
}

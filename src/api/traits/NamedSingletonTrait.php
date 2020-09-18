<?php declare(strict_types = 1);

/**
 * NamedSingletonTrait.php
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
 * Реализует метод получения ссылки на единичный именованный объект
 *
 * @package XEAF\Rack\API\Traits
 */
trait NamedSingletonTrait {

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @param string $name Имя объекта
     *
     * @return static
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): self {
        $result = Factory::getFactoryNamedObject(static::class, $name);
        assert($result instanceof static);
        return $result;
    }
}

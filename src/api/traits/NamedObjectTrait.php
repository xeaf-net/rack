<?php

/**
 * NamedObjectTrait.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Traits;

use XEAF\Rack\API\App\Factory;

/**
 * Содержит методы для реализации интерфейса INamedObject
 *
 * @package XEAF\Rack\API\Traits
 */
trait NamedObjectTrait {

    /**
     * Имя объекта
     * @var string
     */
    private $_name = Factory::DEFAULT_NAME;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name = $name;
    }

    /**
     * Возвращает имя объекта
     *
     * @return string
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Задает имя объекта
     *
     * @param string $name Имя объекта
     *
     * @return void
     */
    public function setName(string $name): void {
        $this->_name = $name;
    }
}

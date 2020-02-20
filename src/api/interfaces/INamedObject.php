<?php

/**
 * INamedObject.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use XEAF\Rack\API\App\Factory;

/**
 * Описывает методы классов именованных объектов
 *
 * @property string $name Имя объекта
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface INamedObject extends IFactoryObject {

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     */
    function __construct(string $name = Factory::DEFAULT_NAME);

    /**
     * Возвращает имя объекта
     *
     * @return string
     */
    function getName(): string;

    /**
     * Задает имя объекта
     *
     * @param string $name Имя объекта
     *
     * @return void
     */
    function setName(string $name): void;
}

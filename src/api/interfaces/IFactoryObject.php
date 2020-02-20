<?php

/**
 * IFactoryObject.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает базовые методы объекта фабрики
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IFactoryObject {

    /**
     * Конструктор класса
     */
    function __construct();
}

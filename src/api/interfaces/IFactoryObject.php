<?php declare(strict_types = 1);

/**
 * IFactoryObject.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
    public function __construct();
}

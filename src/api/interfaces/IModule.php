<?php

/**
 * IModule.php
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
 * Описывает базовые методы модуля проекта
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IModule {

    /**
     * Исполняет действие модуля
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    public function execute(): ?IActionResult;
}

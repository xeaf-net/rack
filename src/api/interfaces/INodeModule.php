<?php

/**
 * INodeModule.php
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
 * Описывает функции проверки узла маршрута
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface INodeModule extends IModule {

    /**
     * Проверяет текущий маршрут
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    function checkNode(): ?IActionResult;
}

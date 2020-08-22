<?php declare(strict_types = 1);

/**
 * INodeModule.php
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
 * Описывает функции проверки узла маршрута
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface INodeModule {

    /**
     * Регистрирует модули узла
     *
     * @return void
     */
    public function registerNodeModules(): void;

    /**
     * Проверяет текущий маршрут
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    public function checkNode(): ?IActionResult;
}

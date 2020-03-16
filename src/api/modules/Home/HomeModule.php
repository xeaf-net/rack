<?php declare(strict_types = 1);

/**
 * HomeModule.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Modules\Home;

use XEAF\Rack\API\App\Application;
use XEAF\Rack\API\Core\Module;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Models\Results\DataResult;

/**
 * Демонстрационный модуль домашней страницы
 *
 * @package XEAF\Rack\API\Modules\Home
 */
class HomeModule extends Module {

    /**
     * Обрабатывает обращение по методу GET
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    protected function processGet(): ?IActionResult {
        $data = [
            'title'     => 'XEAF Rack',
            'version'   => Application::rackVersion(),
            'debugMode' => __RACK_DEBUG_MODE__
        ];
        return DataResult::dataArray($data);
    }
}

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

use XEAF\Rack\API\Core\Module;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Models\Results\DataResult;
use XEAF\Rack\API\Utils\Versions;

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
        $config  = PortalConfig::getInstance();
        $version = Versions::getInstance();
        $data    = [
            'title'     => 'XEAF Rack',
            'version'   => $version->getRackVersion(),
            'debugMode' => $config->getDebugMode()
        ];
        return DataResult::dataArray($data);
    }
}

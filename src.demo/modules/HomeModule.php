<?php declare(strict_types = 1);

/**
 * HomeModule.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Demo\Modules;

use XEAF\Rack\API\Core\Module;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Models\Results\DataResult;
use XEAF\Rack\API\Utils\Versions;

/**
 * Возвращает информацию о демонстрационном примере
 *
 * @package XEAF\Rack\Demo\Modules
 */
class HomeModule extends Module {

    /**
     * Возвращает номер версии
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    protected function processGet(): ?IActionResult {
        $versions = Versions::getInstance();
        $result   = [
            'title'   => 'Rack Demo API',
            'version' => $versions->getAppVersion()
        ];
        return DataResult::dataArray($result);
    }
}

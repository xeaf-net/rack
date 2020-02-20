<?php

/**
 * NodeModule.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Interfaces\INodeModule;

/**
 * Реалиует методы проверки узла маршрута
 *
 * @package  XEAF\Rack\API\Core
 */
abstract class NodeModule extends Module implements INodeModule {

    /**
     * @inheritDoc
     */
    public function checkNode(): ?IActionResult {
        return null;
    }
}

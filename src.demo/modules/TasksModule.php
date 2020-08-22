<?php declare(strict_types = 1);

/**
 * TasksModule.php
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
use XEAF\Rack\API\Models\Results\ListResult;
use XEAF\Rack\Demo\App\DemoEM;

/**
 * Реализует методы работы с Задачами
 *
 * @package XEAF\Rack\Demo\Modules
 */
class TasksModule extends Module {

    /**
     * Путь к модулю
     */
    public const MODULE_PATH = '/tasks';

    /**
     * Возвращает список проектов
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function processGet(): ?IActionResult {
        $em    = DemoEM::getInstance();
        $xql   = "t from tasks t";
        $query = $em->query($xql);
        $list  = $query->get();
        return new ListResult($list);
    }
}

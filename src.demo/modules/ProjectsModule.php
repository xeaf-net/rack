<?php declare(strict_types = 1);

/**
 * ProjectsModule.php
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
 * Реализует методы работы с проектами
 *
 * @package XEAF\Rack\Demo\Modules
 */
class ProjectsModule extends Module {

    /**
     * Путь к модулю
     */
    public const MODULE_PATH = '/projects';

    /**
     * Возвращает список проектов
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function processGet(): ?IActionResult {
        $em    = DemoEM::getInstance();
        $xql   = "p from projects p where p.id == :id";
        $query = $em->query($xql);
        // $query->select('u')->leftJoin('users', 'u', 'id', 'p', 'userId');
        $query->withEager('p', 'user');
        $query->withLazy('p', 'tasks');
        // $q = $query->subquery('p', 'tasks')->andWhere("tasks.status == 'ACTIVE'")->orderBy('tasks', 'status', true);
        // $subquery = $query->subquery('p', 'tasks')->orderBy('tasks', 'status');
        // $subquery->andWhere("tasks.status == :status");
        // $q = $query->subquery('p', 'tasks');
        $list = $query->get([
            'id'     => 'd2ebe471-9308-4879-a4d2-f6143a6bf7e6',
            'status' => 'ACTIVE'
        ]);
        // print "<pre>";
        // print_r($q->getModel());
        return new ListResult($list);
    }
}

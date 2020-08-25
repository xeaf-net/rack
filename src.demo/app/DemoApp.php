<?php declare(strict_types = 1);

/**
 * DemoApp.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Demo\App;

use XEAF\Rack\API\App\Application;
use XEAF\Rack\API\App\Router;
use XEAF\Rack\Demo\Modules\HomeModule;
use XEAF\Rack\Demo\Modules\ProjectsModule;
use XEAF\Rack\Demo\Modules\TasksModule;

/**
 * Реализует методы демонстрационного приложения
 *
 * @package XEAF\Rack\Demo\App
 */
class DemoApp extends Application {

    /**
     * @inheritDoc
     */
    protected function declareModules(): array {
        return [
                Router::ROOT_NODE           => HomeModule::class,
                ProjectsModule::MODULE_PATH => ProjectsModule::class,
                TasksModule::MODULE_PATH    => TasksModule::class
            ] + parent::declareModules();
    }
}

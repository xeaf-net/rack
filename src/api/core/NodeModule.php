<?php declare(strict_types = 1);

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

use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Interfaces\INodeModule;
use XEAF\Rack\API\Utils\Parameters;

/**
 * Реалиует методы проверки узла маршрута
 *
 * @package  XEAF\Rack\API\Core
 */
abstract class NodeModule extends Extension implements INodeModule {

    /**
     * Возвращает список определяемых модулей
     *
     * @return array
     */
    protected function declareModules(): array {
        return [];
    }

    /**
     * Регистрирует модули узла
     *
     * @return void
     */
    public function registerNodeModules(): void {
        $modules  = $this->declareModules();
        if (count($modules) > 0) {
            $router   = Router::getInstance();
            $params   = Parameters::getInstance();
            $nodeRoot = $params->getActionNode();
            foreach ($modules as $path => $className) {
                $nodePath = '/' . trim($nodeRoot, '/') . '/' . trim($path, '/');
                $router->registerRoute($nodePath, $className);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function checkNode(): ?IActionResult {
        return null;
    }
}

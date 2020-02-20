<?php

/**
 * Router.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\App;

use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\IRouter;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\API\Utils\Strings;

/**
 * Реализует методы роутера маршрутов вызова приложения
 *
 * @package XEAF\Rack\API\App
 */
class Router implements IRouter {

    /**
     * Корневой узел
     */
    public const ROOT_NODE = '/';

    /**
     * Путь действия авторизации
     */
    public const LOGIN_PATH = '/login';

    /**
     * Список маршрутов
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    protected $_routes = null;

    /**
     * Список узлов маршрутов
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    protected $_routeNodes = null;

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->_routes     = new KeyValue();
        $this->_routeNodes = new KeyValue();
        $this->registerRouteNode(self::ROOT_NODE);
    }

    /**
     * @inheritDoc
     */
    public function clearRoutes(): void {
        $this->_routes->clear();
    }

    /**
     * @inheritDoc
     */
    public function routeExists(string $path): bool {
        return $this->_routes->exists($path);
    }

    /**
     * @inheritDoc
     */
    public function routeClassName(?string $path): ?string {
        $result = null;
        if (!$path) {
            $result = $this->_routes->get(self::ROOT_NODE);
        } else {
            if ($this->routeExists($path)) {
                $result = $this->_routes->get($path);
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function registerRoute(string $path, string $className): void {
        $this->_routes->put($path, $className);
    }

    /**
     * @inheritDoc
     */
    public function registerRoutes(array $routes): void {
        foreach ($routes as $path => $className) {
            $this->registerRoute($path, $className);
        }
    }

    /**
     * @inheritDoc
     */
    public function unregisterRoute(string $path): void {
        $this->_routes->delete($path);
    }

    /**
     * @inheritDoc
     */
    public function clearRouteNodes(): void {
        $this->_routeNodes->clear();
        $this->_routeNodes->put(self::ROOT_NODE);
    }

    /**
     * @inheritDoc
     */
    public function routeNodeExists(string $path): bool {
        return $this->_routeNodes->exists($path);
    }

    /**
     * @inheritDoc
     */
    public function routeNodeClassName(string $path): ?string {
        return $this->_routeNodes->get($path);
    }

    /**
     * @inheritDoc
     */
    public function registerRouteNode(string $path, string $className = ''): void {
        $this->_routeNodes->put($path, $className);
    }

    /**
     * @inheritDoc
     */
    public function registerRoutesNodes(array $routeNodes): void {
        foreach ($routeNodes as $path => $className) {
            $this->registerRouteNode($path, $className);
        }
    }

    /**
     * @inheritDoc
     */
    public function unregisterRouteNode(string $path): void {
        if ($path != self::ROOT_NODE) {
            $this->_routeNodes->delete($path);
        }
    }

    /**
     * @inheritDoc
     */
    public function extractPathNode(string $path): string {
        $result  = '';
        $length  = 0;
        $strings = Strings::getInstance();
        foreach ($this->_routeNodes->keys() as $node) {
            if ($strings->startsWith($path, $node)) {
                $len = strlen($node);
                if ($len > $length) {
                    $result = $node;
                    $length = $len;
                }
            }
        }
        return $result;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\IRouter
     */
    public static function getInstance(): IRouter {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IRouter);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function moduleClassName(): ?string {
        $args = Parameters::getInstance();
        return $this->routeClassName($args->getActionPath());
    }

    /**
     * @inheritDoc
     */
    public function moduleNodeClassName(): ?string {
        $args = Parameters::getInstance();
        return $this->routeNodeClassName($args->getActionNode());
    }
}

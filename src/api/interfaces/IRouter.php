<?php declare(strict_types = 1);

/**
 * IRouter.php
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
 * Описывает методы роутера маршрутов вызова приложения
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IRouter extends IFactoryObject {
    /**
     * Отменяет регистрацию всех маршрутов
     *
     * @return void
     */
    public function clearRoutes(): void;

    /**
     * Возвращает признак существования маршрута
     *
     * @param string $path Путь
     *
     * @return bool
     */
    public function routeExists(string $path): bool;

    /**
     * Возвращает имя класса модуля обработки маршрута
     *
     * @param string|null $path Путь
     *
     * @return string|null
     */
    public function routeClassName(?string $path): ?string;

    /**
     * Регистрирует новый маршрут
     *
     * @param string $path      Путь
     * @param string $className Имя класса модуля
     *
     * @return void
     */
    public function registerRoute(string $path, string $className): void;

    /**
     * Регистрирует маршруты по определениям из массива
     *
     * @param array $routes Массив маршрутов
     *
     * @return void
     */
    public function registerRoutes(array $routes): void;

    /**
     * Отменяет регистрацию маршрута
     *
     * @param string $path Путь
     *
     * @return void
     */
    public function unregisterRoute(string $path): void;

    /**
     * Отменяет регистрацию всех узлов маршрутов
     *
     * @return void
     */
    public function clearRouteNodes(): void;

    /**
     * Возвращает признак существования узла маршрута
     *
     * @param string $path Путь
     *
     * @return bool
     */
    public function routeNodeExists(string $path): bool;

    /**
     * Возвращает имя класса модуля обработчика узла маршрута
     *
     * @param string $path Путь
     *
     * @return string|null
     */
    public function routeNodeClassName(string $path): ?string;

    /**
     * Регистрирует узел маршрута
     *
     * @param string $path      Путь
     * @param string $className Имя класса модуля
     *
     * @return void
     */
    public function registerRouteNode(string $path, string $className = ''): void;

    /**
     * Регистрирует маршруты по определениям из массива
     *
     * @param array $routeNodes Массив определений модулей проверки узлов маршрутов
     *
     * @return void
     */
    public function registerRoutesNodes(array $routeNodes): void;

    /**
     * Отменяет регистрацию узла маршрута
     *
     * @param string $path Путь
     *
     * @return void
     */
    public function unregisterRouteNode(string $path): void;

    /**
     * Извлекает из пути зарегистрированный узел
     *
     * @param string $path Путь
     *
     * @return string
     */
    public function extractPathNode(string $path): string;

    /**
     * Возвращает класс модуля обработки текущего маршрута
     *
     * @return string|null
     */
    public function moduleClassName():?string;

    /**
     * Возвращает класс модуля обработки узла текущего маршрута
     *
     * @return string|null
     */
    public function moduleNodeClassName():?string;

    /**
     * Возвращает массив зарегистрированных маршрутов
     *
     * @return array
     */
    public function registeredRoutes(): array;

    /**
     * Возвращает массив зарегистрированных узлов маршрутов
     *
     * @return array
     */
    public function registeredRouteNodes(): array;
}

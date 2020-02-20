<?php

/**
 * IRouter.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
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
    function clearRoutes(): void;

    /**
     * Возвращает признак существования маршрута
     *
     * @param string $path Путь
     *
     * @return bool
     */
    function routeExists(string $path): bool;

    /**
     * Возвращает имя класса модуля обработки маршрута
     *
     * @param string|null $path Путь
     *
     * @return string|null
     */
    function routeClassName(?string $path): ?string;

    /**
     * Регистрирует новый маршрут
     *
     * @param string $path      Путь
     * @param string $className Имя класса модуля
     *
     * @return void
     */
    function registerRoute(string $path, string $className): void;

    /**
     * Регистрирует маршруты по определениям из массива
     *
     * @param array $routes Массив маршрутов
     *
     * @return void
     */
    function registerRoutes(array $routes): void;

    /**
     * Отменяет регистрацию маршрута
     *
     * @param string $path Путь
     *
     * @return void
     */
    function unregisterRoute(string $path): void;

    /**
     * Отменяет регистрацию всех узлов маршрутов
     *
     * @return void
     */
    function clearRouteNodes(): void;

    /**
     * Возвращает признак существования узла маршрута
     *
     * @param string $path Путь
     *
     * @return bool
     */
    function routeNodeExists(string $path): bool;

    /**
     * Возвращает имя класса модуля обработчика узла маршрута
     *
     * @param string $path Путь
     *
     * @return string|null
     */
    function routeNodeClassName(string $path): ?string;

    /**
     * Регистрирует узел маршрута
     *
     * @param string $path      Путь
     * @param string $className Имя класса модуля
     *
     * @return void
     */
    function registerRouteNode(string $path, string $className = ''): void;

    /**
     * Регистрирует маршруты по определениям из массива
     *
     * @param array $routeNodes Массив определений модулей проверки узлов маршрутов
     *
     * @return void
     */
    function registerRoutesNodes(array $routeNodes): void;

    /**
     * Отменяет регистрацию узла маршрута
     *
     * @param string $path Путь
     *
     * @return void
     */
    function unregisterRouteNode(string $path): void;

    /**
     * Извлекает из пути зарегистрированный узел
     *
     * @param string $path Путь
     *
     * @return string
     */
    function extractPathNode(string $path): string;

    /**
     * Возвращает класс модуля обработки текущего маршрута
     *
     * @return string|null
     */
    function moduleClassName():?string;

    /**
     * Возвращает класс модуля обработки узла текущего маршрута
     *
     * @return string|null
     */
    function moduleNodeClassName():?string;
}

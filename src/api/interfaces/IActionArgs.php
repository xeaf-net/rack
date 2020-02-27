<?php

/**
 * IActionArgs.php
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
 * Описывает методы контейнера параметров вызова приложения
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IActionArgs extends IFactoryObject {

    /**
     * Возвращает имя метода
     *
     * @return string
     */
    function getMethodName(): string;

    /**
     * Возвращает имя узла маршрута
     *
     * @return string
     */
    function getActionNode(): string;

    /**
     * Возвращает путь действия
     *
     * @return string|null
     */
    function getActionPath(): ?string;

    /**
     * Врзвращает режим вызова действия
     *
     * @return string|null
     */
    function getActionMode(): ?string;

    /**
     * Возвращает идентификатор объекта действия
     *
     * @return string|null
     */
    function getObjectId(): ?string;

    /**
     * Возвращает путь объекта действия
     *
     * @return string|null
     */
    function getObjectPath(): ?string;

    /**
     * Возвращает полный URL вызова действия
     *
     * @return string|null
     */
    function getCurrentURL(): ?string;

    /**
     * Возвращает значение параметра
     *
     * @param string     $name         Имя параметра
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     */
    function get(string $name, $defaultValue = null);

    /**
     * Возвращает значение параметра заголовка
     *
     * @param string     $name         Имя параметра
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     */
    function getHeader(string $name, $defaultValue = null);

    /**
     * Возвращает список параметров заголовков
     *
     * @return array
     */
    function getHeaders(): array;

    /**
     * Возвращает локаль
     *
     * @return string
     */
    function getLocale(): string;

    /**
     * Задает локаль
     *
     * @param string $locale Локаль
     *
     * @return void
     */
    function setLocale(string $locale): void;
}

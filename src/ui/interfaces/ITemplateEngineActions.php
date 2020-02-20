<?php

/**
 * ITemplateEngineActions.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Interfaces;

use XEAF\Rack\UI\Core\Template;
use XEAF\Rack\UI\Models\Results\HtmlResult;

/**
 * Описывает методы действия шаблонизатора
 *
 * @package XEAF\Rack\UI\Interfaces
 */
interface ITemplateEngineActions {

    /**
     * Возвращает имя файла разметки для заданного класса
     *
     * @param string $className Имя класса
     *
     * @return string
     */
    function defaultLayoutFile(string $className): string;

    /**
     * Возвращает имя класса зарегистрированного плагина
     *
     * @param string $name ТИмя плагина
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    function getRegisteredPlugin(string $name): string;

    /**
     * Регистрирует новый плагин
     *
     * @param string $name      Имя плагина
     * @param string $className Имя класса
     *
     * @return void
     */
    function registerPlugin(string $name, string $className): void;

    /**
     * Регистрирует плагины по определениям из массива
     *
     * @param array $plugins Массив определений плагинов
     *
     * @return void
     */
    function registerPlugins(array $plugins): void;

    /**
     * Отменяет регистрацию плагина
     *
     * @param string $name Имя плагина
     *
     * @return void
     */
    function unregisterPlugin(string $name): void;

    /**
     * Возвращает имя класса зарегистрированного шаблона
     *
     * @param string $name Имя шаблона
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    function getRegisteredTemplate(string $name): string;

    /**
     * Регистрирует новый шаблон
     *
     * @param string $name      Имя шаблона
     * @param string $className Имя класса
     *
     * @return void
     */
    function registerTemplate(string $name, string $className): void;

    /**
     * Регистрирует шаблоны по определениям из массива
     *
     * @param array $templates Массив определений шаблонов
     *
     * @return void
     */
    function registerTemplates(array $templates): void;

    /**
     * Отменяет регигстрацию шаблона
     *
     * @param string $name Имя шаблона
     *
     * @return void
     */
    function unregisterTemplate(string $name): void;

    /**
     * Возвращает HTML код результата действия
     *
     * @param \XEAF\Rack\UI\Models\Results\HtmlResult $actionResult Результат исполнения действия
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    function parseModule(HtmlResult $actionResult): string;

    /**
     * Возвращает HTML код страницы
     *
     * @param \XEAF\Rack\UI\Core\Template $template    Объект шаблона страницы
     * @param string                      $pageContent Содержимое страницы
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    function parseTemplate(Template $template, string &$pageContent): string;
}

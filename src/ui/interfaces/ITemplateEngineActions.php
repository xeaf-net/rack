<?php declare(strict_types = 1);

/**
 * ITemplateEngineActions.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
    public function defaultLayoutFile(string $className): string;

    /**
     * Возвращает имя класса зарегистрированного плагина
     *
     * @param string $name ТИмя плагина
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public function getRegisteredPlugin(string $name): string;

    /**
     * Регистрирует новый плагин
     *
     * @param string $name      Имя плагина
     * @param string $className Имя класса
     *
     * @return void
     */
    public function registerPlugin(string $name, string $className): void;

    /**
     * Регистрирует плагины по определениям из массива
     *
     * @param array $plugins Массив определений плагинов
     *
     * @return void
     */
    public function registerPlugins(array $plugins): void;

    /**
     * Отменяет регистрацию плагина
     *
     * @param string $name Имя плагина
     *
     * @return void
     */
    public function unregisterPlugin(string $name): void;

    /**
     * Возвращает имя класса зарегистрированного шаблона
     *
     * @param string $name Имя шаблона
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public function getRegisteredTemplate(string $name): string;

    /**
     * Регистрирует новый шаблон
     *
     * @param string $name      Имя шаблона
     * @param string $className Имя класса
     *
     * @return void
     */
    public function registerTemplate(string $name, string $className): void;

    /**
     * Регистрирует шаблоны по определениям из массива
     *
     * @param array $templates Массив определений шаблонов
     *
     * @return void
     */
    public function registerTemplates(array $templates): void;

    /**
     * Отменяет регигстрацию шаблона
     *
     * @param string $name Имя шаблона
     *
     * @return void
     */
    public function unregisterTemplate(string $name): void;

    /**
     * Возвращает HTML код результата действия
     *
     * @param \XEAF\Rack\UI\Models\Results\HtmlResult $actionResult Результат исполнения действия
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public function parseModule(HtmlResult $actionResult): string;

    /**
     * Возвращает HTML код страницы
     *
     * @param \XEAF\Rack\UI\Core\Template $template    Объект шаблона страницы
     * @param string                      $pageContent Содержимое страницы
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public function parseTemplate(Template $template, string &$pageContent): string;
}

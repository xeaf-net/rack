<?php

/**
 * ILocale.php
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
 * Описывает методы пакета локализации
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ILocale {

    /**
     * Возвращает имя локали
     *
     * @return string
     */
    function getName(): string;

    /**
     * Возвращает имя языка локали
     *
     * @return string
     */
    function getLanguage(): string;

    /**
     * Возвращает сокращенное название языка
     *
     * @return string
     */
    function getLang(): string;

    /**
     * Возвращает направление написания
     *
     * @return string
     */
    function getDir(): string;

    /**
     * Возвращает формат представления даты
     *
     * @return string
     */
    function getDateFormat(): string;

    /**
     * Возвращает формат представления времени
     *
     * @return string
     */
    function getTimeFormat(): string;

    /**
     * Возвращет формат представления даты и времени
     *
     * @return string
     */
    function getDateTimeFormat(): string;

    /**
     * Возвращает символ десятичной точки
     *
     * @return string
     */
    function getDecimalPoint(): string;

    /**
     * Возвращает разделитель разрядов
     *
     * @return string
     */
    function getThousandsSeparator(): string;

    /**
     * Возвращает представление констант локали в виде массива
     *
     * @return array
     */
    function toArray(): array;
}

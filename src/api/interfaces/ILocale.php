<?php declare(strict_types = 1);

/**
 * ILocale.php
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
    public function getName(): string;

    /**
     * Возвращает имя языка локали
     *
     * @return string
     */
    public function getLanguage(): string;

    /**
     * Возвращает сокращенное название языка
     *
     * @return string
     */
    public function getLang(): string;

    /**
     * Возвращает направление написания
     *
     * @return string
     */
    public function getDir(): string;

    /**
     * Возвращает формат представления даты
     *
     * @return string
     */
    public function getDateFormat(): string;

    /**
     * Возвращает формат представления времени
     *
     * @return string
     */
    public function getTimeFormat(): string;

    /**
     * Возвращет формат представления даты и времени
     *
     * @return string
     */
    public function getDateTimeFormat(): string;

    /**
     * Возвращает символ десятичной точки
     *
     * @return string
     */
    public function getDecimalPoint(): string;

    /**
     * Возвращает разделитель разрядов
     *
     * @return string
     */
    public function getThousandsSeparator(): string;

    /**
     * Возвращает представление констант локали в виде массива
     *
     * @return array
     */
    public function toArray(): array;
}

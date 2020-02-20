<?php

/**
 * ICalendar.php
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
 * Описывает методы работы с датами
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ICalendar extends IFactoryObject {

    /**
     * Возвращает текущие дату и время
     *
     * @return int
     */
    function now(): int;

    /**
     * Возвращает текущую дату
     *
     * @return int
     */
    function today(): int;

    /**
     * Возвращает дату, предшествующую заданной
     *
     * @param int|null $date Заданная дата
     *
     * @return int
     */
    function yesterday(?int $date = null): int;

    /**
     * Возвращает дату, следующую за заданной
     *
     * @param int|null $date Заданная дата
     *
     * @return int
     */
    function tomorrow(?int $date = null): int;

    /**
     * Возвращает первый день месяца заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    function firstDayOfMonth(int $date): int;

    /**
     * Возвращает последний день месяца заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    function lastDayOfMonth(int $date): int;

    /**
     * Возвращает первый день года заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    function firstDayOfYear(int $date): int;

    /**
     * Возвращает последний день года заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    function lastDayOfYear(int $date): int;

    /**
     * Возвращает массив разобранных состалвющих даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return array
     */
    function parseDateTime(int $dateTime = null): array;

    /**
     * Возвращает день месяца заданной даты
     *
     * @param int|null $date Дата
     *
     * @return int
     */
    function getDay(int $date = null): int;

    /**
     * Возвращает номер месяца заданной даты
     *
     * @param int|null $date Дата
     *
     * @return int
     */
    function getMonth(int $date = null): int;

    /**
     * Возвращает год заданной даты
     *
     * @param int|null $date Дата
     *
     * @return int
     */
    function getYear(int $date = null): int;

    /**
     * Возвращает часы заданных даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return int
     */
    function getHours(int $dateTime = null): int;

    /**
     * Возвращает минуты заданных даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return int
     */
    function getMinutes(int $dateTime): int;

    /**
     * Возвращает секунды заданных даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return int
     */
    function getSeconds(int $dateTime): int;

    /**
     * Удаляет время из даты и времени
     *
     * @param int $dateTime Дата и время
     *
     * @return int
     */
    function dateTimeToDate(int $dateTime): int;
}

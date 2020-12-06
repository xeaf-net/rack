<?php declare(strict_types = 1);

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
    public function now(): int;

    /**
     * Возвращает текущую дату
     *
     * @return int
     */
    public function today(): int;

    /**
     * Возвращает дату, предшествующую заданной
     *
     * @param int|null $date Заданная дата
     *
     * @return int
     */
    public function yesterday(?int $date = null): int;

    /**
     * Возвращает дату, следующую за заданной
     *
     * @param int|null $date Заданная дата
     *
     * @return int
     */
    public function tomorrow(?int $date = null): int;

    /**
     * Возвращает первый день месяца заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    public function firstDayOfMonth(int $date): int;

    /**
     * Возвращает последний день месяца заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    public function lastDayOfMonth(int $date): int;

    /**
     * Возвращает первый день года заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    public function firstDayOfYear(int $date): int;

    /**
     * Возвращает последний день года заданной даты
     *
     * @param int $date Заданная дата
     *
     * @return int
     */
    public function lastDayOfYear(int $date): int;

    /**
     * Возвращает массив разобранных состалвющих даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return array
     */
    public function parseDateTime(int $dateTime = null): array;

    /**
     * Возвращает день месяца заданной даты
     *
     * @param int|null $date Дата
     *
     * @return int
     */
    public function getDay(int $date = null): int;

    /**
     * Возвращает номер месяца заданной даты
     *
     * @param int|null $date Дата
     *
     * @return int
     */
    public function getMonth(int $date = null): int;

    /**
     * Возвращает год заданной даты
     *
     * @param int|null $date Дата
     *
     * @return int
     */
    public function getYear(int $date = null): int;

    /**
     * Возвращает часы заданных даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return int
     */
    public function getHours(int $dateTime = null): int;

    /**
     * Возвращает минуты заданных даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return int
     */
    public function getMinutes(int $dateTime): int;

    /**
     * Возвращает секунды заданных даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return int
     */
    public function getSeconds(int $dateTime): int;

    /**
     * Удаляет время из даты и времени
     *
     * @param int $dateTime Дата и время
     *
     * @return int
     */
    public function dateTimeToDate(int $dateTime): int;


    /**
     * Возвращает нормализованное представления даты
     *
     * @param int|null $date Дата
     *
     * @return string
     */
    public function normalizeDate(int $date = null): string;

    /**
     * Возвращает нормализованное представления даты и времени
     *
     * @param int|null $dateTime Дата и время
     *
     * @return string
     */
    public function normalizeDateTime(int $dateTime = null): string;
}

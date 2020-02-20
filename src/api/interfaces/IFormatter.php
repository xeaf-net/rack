<?php

/**
 * Formatter.php
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
 * Описывает методы форматирования данных
 *
 * @package XEAF\Rack\API\Interfaces;
 */
interface IFormatter extends IFactoryObject {

    /**
     * Форматирует целое число
     *
     * @param int         $number Форматируемое значение
     * @param string|null $locale Имя локали
     *
     * @return string
     */
    public function formatInteger(int $number, string $locale = null): string;

    /**
     * Форматирует действительное число
     *
     * @param float       $number Форматируемое значение
     * @param int         $dec    Количество десятичных знаков
     * @param string|null $locale Имя локали
     *
     * @return string
     */
    public function formatNumeric(float $number, int $dec = 2, string $locale = null): string;

    /**
     * Форматирует дату
     *
     * @param int         $date   Дата
     * @param string|null $locale Имя локали
     *
     * @return string
     */
    public function formatDate(int $date, string $locale = null): string;

    /**
     * Форматирует время
     *
     * @param int         $time   Время
     * @param string|null $locale Имя локали
     *
     * @return string
     */
    public function formatTime(int $time, string $locale = null): string;

    /**
     * Форматирует дату и время
     *
     * @param int         $dateTime Дата и время
     * @param string|null $locale   Имя локали
     *
     * @return string
     */
    public function formatDateTime(int $dateTime, string $locale = null): string;

    /**
     * Форматирует дату и время для использования в кеше
     *
     * @param int $dateTime Дата и время
     *
     * @return string
     */
    public function formatCacheDateTime(int $dateTime): string;
}

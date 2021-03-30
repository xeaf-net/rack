<?php declare(strict_types = 1);

/**
 * IStrings.php
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
 * Описывает методы работы со строковыми данными
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IStrings extends IFactoryObject {

    /**
     * Возвращает признак пустой строки
     *
     * @param string|null $buf Строка символов
     *
     * @return bool
     */
    public function isEmpty(?string $buf): bool;

    /**
     * Возвращает NULL для пустой строки
     *
     * @param string|null $buf Строка символов
     *
     * @return string|null
     */
    public function emptyToNull(?string $buf): ?string;

    /**
     * Возвращает пустую строку для значения NULL
     *
     * @param string|null $buf Строка символов
     *
     * @return string
     */
    public function emptyToEmpty(?string $buf): string;

    /**
     * Преобразует строку в целое число
     *
     * @param string|null $buf     Строка символов
     * @param int|null    $onError Результат при ошибке
     *
     * @return int|null
     */
    public function stringToInteger(?string $buf, int $onError = null): ?int;

    /**
     * Преобразует строку в действительное число
     *
     * @param string|null $buf     Строка символов
     * @param float|null  $onError Результат при ошибке
     *
     * @return float|null
     */
    public function stringToNumeric(?string $buf, float $onError = null): ?float;

    /**
     * Преобразует строку в дату и время
     *
     * @param string|null $buf     Строка символов
     * @param int|null    $onError Результат при ошибке
     *
     * @return int|null
     */
    public function stringToDateTime(?string $buf, int $onError = null): ?int;

    /**
     * Проверяет содержит ли переданная строка логическое значение
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isBoolean(?string $buf): bool;

    /**
     * Проверяет содержит ли переданная строка целое число
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isInteger(?string $buf): bool;

    /**
     * Проверяет содержит ли переданная строка действительное число
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isNumeric(?string $buf): bool;

    /**
     * Проверяет содержит ли переданная строка дату и время
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isDateTime(?string $buf): bool;

    /**
     * Проверяет является ли преданная строка UUID
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isUUID(?string $buf): bool;

    /**
     * Провряет является ли переданная строка адресом электронной почты
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isEmail(?string $buf): bool;

    /**
     * Провряет является ли переданная строка номером телефона
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isPhoneNumber(?string $buf): bool;

    /**
     * Проверяет является ли преданная строка идентификатором объекта
     *
     * @param string|null $buf Проверяемая строка
     *
     * @return bool
     */
    public function isObjectId(?string $buf): bool;

    /**
     * Возвращает признак начала строки символов с заданной подкстроки
     *
     * @param string $haystack   Строка символов
     * @param string $needle     Подстрока
     * @param bool   $ignoreCase Игнорировать регистр
     *
     * @return bool
     */
    public function startsWith(string $haystack, string $needle, bool $ignoreCase = false): bool;

    /**
     * Возвращает признак завершения строки символов заданной подстрокой
     *
     * @param string $haystack   Строка символов
     * @param string $needle     Подстрока
     * @param bool   $ignoreCase Игнорировать регистр
     *
     * @return bool
     */
    public function endsWith(string $haystack, string $needle, bool $ignoreCase = false): bool;

    /**
     * Возвращает строку с приведенным к верхенму регистру первым символом
     *
     * @param string $buf Строка символов
     *
     * @return string
     */
    public function upperCaseFirst(string $buf): string;

    /**
     * Разбирает параметры DSN
     *
     * @param string $data Исходные данные
     *
     * @return array
     */
    public function parseDSN(string $data): array;

    /**
     * Разбирает строку на пары Ключ-Значение
     *
     * @param string $data      Исходные строковые данные
     * @param string $separator Используемый разделитель
     *
     * @return array
     */
    public function parseKeyValue(string $data, string $separator): array;

    /**
     * Преобразует строки из систаксиса Kebab в синтаксис Camel
     *
     * @param string|null $kebab          Строка в синтаксисе Kebab
     * @param bool        $upperFirstChar Признак заглоавного первого символа
     *
     * @return string|null
     */
    public function kebabToCamel(?string $kebab, bool $upperFirstChar = true): ?string;
}

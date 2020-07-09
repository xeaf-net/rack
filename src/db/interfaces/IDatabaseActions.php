<?php declare(strict_types = 1);

/**
 * IDatabaseActions.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Interfaces;

/**
 * Описывает общие методы работы с базой данных
 *
 * @package XEAF\Rack\Db\Interfaces
 */
interface IDatabaseActions {

    /**
     * Открывает подключение к базе данных
     *
     * @return void
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function connect(): void;

    /**
     * Закрывает соединение с базой данных
     *
     * @return void
     */
    public function disconnect(): void;

    /**
     * Возвращает признак открытого соединения
     *
     * @return bool
     */
    public function connected(): bool;

    /**
     * Возвращает признак открытой транзакции
     *
     * @return bool
     */
    public function inTransaction(): bool;

    /**
     * Открывает транзакцию
     *
     * @return void
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function startTransaction(): void;

    /**
     * Подтверждает изменения в транзакции
     *
     * @return void
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function commitTransaction();

    /**
     * Откатывает изменения в транзакции
     *
     * @return void
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function rollbackTransaction(): void;

    /**
     * Возвращает массив записей результата SQL запроса
     *
     * @param string $sql    Текст SQL запроса
     * @param array  $params Массив значений параметров
     * @param int    $count  Количество записей
     * @param int    $offset Смещение
     *
     * @return array
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function select(string $sql, array $params = [], int $count = 0, int $offset = 0): array;

    /**
     * Возвращает массив значений полей первой записи
     *
     * @param string $sql    Текст SQL запроса
     * @param array  $params Массив значений параметров
     *
     * @return null|array
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function selectFirst(string $sql, array $params = []): ?array;

    /**
     * Исполняет SQL команду к базе данных
     *
     * @param string $sql    Текст SQL команды
     * @param array  $params Массив значений параметров
     *
     * @return int Количество затронутых записей
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function execute(string $sql, array $params = []): int;

    /**
     * Возвращает идентификатор последней созданной записи
     *
     * @return string
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function lastInsertId(): string;

    /**
     * Возвращает параметры подключения
     *
     * @return array
     */
    public function connectionOptions(): array;

    /**
     * Возвращает SQL выражение преобразования к верхнему регистру
     *
     * @param string $expression Исходное выражение
     *
     * @return string
     */
    public function lowerCaseExpression(string $expression): string;

    /**
     * Возвращает SQL выражение преобразования к верхнему регистру
     *
     * @param string $expression Исходное выражение
     *
     * @return string
     */
    public function upperCaseExpression(string $expression): string;

    /**
     * Возвращает SQL выражение форматирования даты
     *
     * @param string      $expression Исходное выражение
     * @param string|null $locale     Имя локали
     *
     * @return string
     */
    public function dateExpression(string $expression, string $locale = null): string;

    /**
     * Возвращает SQL выражение форматирования времени
     *
     * @param string      $expression Исходное выражение
     * @param string|null $locale     Имя локали
     *
     * @return string
     */
    public function timeExpression(string $expression, string $locale = null): string;

    /**
     * Возвращает SQL выражение форматирования даты и времени
     *
     * @param string      $expression Исходное выражение
     * @param string|null $locale     Имя локали
     *
     * @return string
     */
    public function dateTimeExpression(string $expression, string $locale = null): string;

    /**
     * Возвращает представление даты в пригодном для SQL формате
     *
     * @param int $date Дата
     *
     * @return string
     */
    public function formatDate(int $date): string;

    /**
     * Преобразует дату формата SQL в timestamp
     *
     * @param string|null $date Дата в формате SQL
     *
     * @return int|null
     */
    public function sqlDate(?string $date): ?int;

    /**
     * Возвращает представление даты и времени в пригодном для SQL формате
     *
     * @param int $dateTime
     *
     * @return string
     */
    public function formatDateTime(int $dateTime): string;

    /**
     * Преобразует дату и время формата SQL в timestamp
     *
     * @param string|null $dateTime Дата и время в формате SQL
     *
     * @return int|null
     */
    public function sqlDateTime(?string $dateTime): ?int;

    /**
     * Возвращает представления логического значения в пригодном для SQL формате
     *
     * @param bool $flag Логическое значение
     *
     * @return string
     */
    public function formatBool(bool $flag): string;

    /**
     * Преобразует логическое значение формата SQL в тип bool
     *
     * @param string $flag Логические значение в формате SQL
     *
     * @return bool
     */
    public function sqlBool(string $flag): bool;
}

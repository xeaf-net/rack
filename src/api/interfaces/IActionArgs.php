<?php declare(strict_types = 1);

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

use XEAF\Rack\API\Models\UploadedFile;

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
    public function getMethodName(): string;

    /**
     * Возвращает имя узла маршрута
     *
     * @return string
     */
    public function getActionNode(): string;

    /**
     * Возвращает путь действия
     *
     * @return string|null
     */
    public function getActionPath(): ?string;

    /**
     * Врзвращает режим вызова действия
     *
     * @return string|null
     */
    public function getActionMode(): ?string;

    /**
     * Возвращает идентификатор объекта действия
     *
     * @return string|null
     */
    public function getObjectId(): ?string;

    /**
     * Возвращает путь объекта действия
     *
     * @return string|null
     */
    public function getObjectPath(): ?string;

    /**
     * Возвращает полный URL вызова действия
     *
     * @return string|null
     */
    public function getCurrentURL(): ?string;

    /**
     * Возвращает признак передачи параметра
     *
     * @param string $name Имя параметра
     *
     * @return bool
     */
    public function exists(string $name): bool;

    /**
     * Возвращает значение параметра
     *
     * @param string     $name         Имя параметра
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     * @deprecated
     */
    public function get(string $name, $defaultValue = null);

    /**
     * Возвращает значение строкового параметра
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return string|null
     */
    public function getString(string $name, string $defaultValue = null): ?string;

    /**
     * Возвращает значение логического параметра
     *
     * @param string $name         Имя параметра
     * @param bool   $defaultValue Значение по умолчанию
     *
     * @return bool
     */
    public function getBool(string $name, bool $defaultValue = false): bool;

    /**
     * Возвращает значение целочисленного параметра
     *
     * @param string $name         Имя параметра
     * @param int    $defaultValue Значение по умолчанию
     *
     * @return int
     */
    public function getInteger(string $name, int $defaultValue = 0): int;

    /**
     * Возвращает значение действительного параметра
     *
     * @param string $name         Имя параметра
     * @param float  $defaultValue Значение по умолчанию
     *
     * @return float
     */
    public function getFloat(string $name, float $defaultValue = 0.00): float;

    /**
     * Возвращает значение параметра идентификатора
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return string|null
     */
    public function getUUID(string $name, string $defaultValue = null): ?string;

    /**
     * Возвращает значение параметра типа массив
     *
     * @param string $name         Имя параметра
     * @param array  $defaultValue Значение по умолчанию
     *
     * @return array
     */
    public function getArray(string $name, array $defaultValue = []): array;

    /**
     * Возвращает значение целочисленного элемента из массива
     *
     * @param string $name         Имя параметра массива
     * @param string $element      Имя элемента
     * @param int    $defaultValue Значение по умолчанию
     *
     * @return int
     */
    public function getArrayInt(string $name, string $element, int $defaultValue = 0): int;

    /**
     * Возвращает значение строкового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return string|null
     */
    public function getArrayString(string $name, string $element, string $defaultValue = null): ?string;

    /**
     * Возвращает значение идентификатора из массива
     *
     * @param string      $name         Имя параметра
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return string|null
     */
    public function getArrayUUID(string $name, string $element, string $defaultValue = null): ?string;

    /**
     * Возвращает информацию о загруженном файле
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\API\Models\UploadedFile|null
     */
    public function getFile(string $name): ?UploadedFile;

    /**
     * Возвращает значение параметра заголовка
     *
     * @param string     $name         Имя параметра
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     */
    public function getHeader(string $name, $defaultValue = null);

    /**
     * Возвращает тип контента
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * Возвращает MIME типа контента
     *
     * @return string
     */
    public function getContentMIME(): string;

    /**
     * Возвращает размер контента
     *
     * @return int
     */
    public function getContentLength(): int;

    /**
     * Возвращает список параметров заголовков
     *
     * @return array
     */
    public function getHeaders(): array;
}

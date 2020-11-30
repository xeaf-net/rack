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
     * Возвращает значение не пустого строкового параметра
     *
     * @param string      $name         Имя параметра
     * @param string      $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getString(string $name, string $defaultValue, string $tag = null): string;

    /**
     * Возвращает значение строкового параметра
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return string|null
     */
    public function getNullOrString(string $name, string $defaultValue = null): ?string;

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
     * Возвращает значение не пустого целочисленного параметра
     *
     * @param string      $name         Имя параметра
     * @param int         $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return int
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getInteger(string $name, int $defaultValue, string $tag = null): int;

    /**
     * Возвращает значение целочисленного параметра
     *
     * @param string      $name         Имя параметра
     * @param int|null    $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return int|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNullOrInteger(string $name, int $defaultValue = null, string $tag = null): ?int;

    /**
     * Возвращает значение не пустого действительного параметра
     *
     * @param string      $name         Имя параметра
     * @param float       $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return float
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNumeric(string $name, float $defaultValue, string $tag = null): float;

    /**
     * Возвращает значение действительного параметра
     *
     * @param string      $name         Имя параметра
     * @param float|null  $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return float|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNullOrNumeric(string $name, float $defaultValue = null, string $tag = null): ?float;

    /**
     * Возвращает не пустое значение параметра идентификатора
     *
     * @param string      $name         Имя параметра
     * @param string      $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getUUID(string $name, string $defaultValue, string $tag = null): string;

    /**
     * Возвращает значение параметра идентификатора
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNullOrUUID(string $name, string $defaultValue = null, string $tag = null): ?string;

    /**
     * Возвращает не пустое значение параметра адреса электронной почты
     *
     * @param string      $name         Имя параметра
     * @param string      $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getEmail(string $name, string $defaultValue, string $tag = null): string;

    /**
     * Возвращает значение параметра адреса электронной почты
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNullOrEmail(string $name, string $defaultValue = null, string $tag = null): ?string;

    /**
     * Возвращает не пустое значение параметра номера телефона
     *
     * @param string      $name         Имя параметра
     * @param string      $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getPhone(string $name, string $defaultValue, string $tag = null): string;

    /**
     * Возвращает значение параметра номера телефона
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNullOrPhone(string $name, string $defaultValue = null, string $tag = null): ?string;

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
     * Возвращает значение не пустого строкового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param string      $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayString(string $name, string $element, string $defaultValue, string $tag = null): string;

    /**
     * Возвращает значение строкового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return string|null
     */
    public function getArrayNullOrString(string $name, string $element, string $defaultValue = null): ?string;

    /**
     * Возвращает не пустое значение целочисленного элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param int         $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return int
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayInteger(string $name, string $element, int $defaultValue = 0, string $tag = null): int;

    /**
     * Возвращает значение целочисленного элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param int|null    $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return int|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayNullOrInteger(string $name, string $element, int $defaultValue = null, string $tag = null): ?int;

    /**
     * Возвращает не пустое значение числового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param float       $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return float
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayNumeric(string $name, string $element, float $defaultValue = 0, string $tag = null): float;

    /**
     * Возвращает значение числового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param float|null  $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return float|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayNullOrNumeric(string $name, string $element, float $defaultValue = null, string $tag = null): ?float;

    /**
     * Возвращает не пустое значение идентификатора из массива
     *
     * @param string      $name         Имя параметра
     * @param string      $element      Имя элемента
     * @param string      $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayUUID(string $name, string $element, string $defaultValue, string $tag = null): string;

    /**
     * Возвращает значение идентификатора из массива
     *
     * @param string      $name         Имя параметра
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayNullOrUUID(string $name, string $element, string $defaultValue = null, string $tag = null): ?string;

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

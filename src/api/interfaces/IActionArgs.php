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
     * Возвращает значение не пустого строкового параметра
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getStringNN(string $name, string $defaultValue = null, string $tag = null): string;

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
     * @param string      $name         Имя параметра
     * @param int|null    $defaultValue Значение по умолчанию
     * @param int|null    $min          Минимальное значение
     * @param int|null    $max          Максимальное значение
     * @param string|null $tag          Тег
     *
     * @return int|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getInteger(string $name, int $defaultValue = null, int $min = null, int $max = null, string $tag = null): ?int;

    /**
     * Возвращает значение не пустого целочисленного параметра
     *
     * @param string      $name         Имя параметра
     * @param int|null    $defaultValue Значение по умолчанию
     * @param int|null    $min          Минимальное значение
     * @param int|null    $max          Максимальное значение
     * @param string|null $tag          Тег
     *
     * @return int
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getIntegerNN(string $name, int $defaultValue = null, int $min = null, int $max = null, string $tag = null): int;

    /**
     * Возвращает значение действительного параметра
     *
     * @param string      $name         Имя параметра
     * @param float|null  $defaultValue Значение по умолчанию
     * @param float|null  $min          Минимальное значение
     * @param float|null  $max          Максимальное значение
     * @param string|null $tag          Тег
     *
     * @return float|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNumeric(string $name, float $defaultValue = null, float $min = null, float $max = null, string $tag = null): ?float;

    /**
     * Возвращает значение не пустого действительного параметра
     *
     * @param string      $name         Имя параметра
     * @param float|null  $defaultValue Значение по умолчанию
     * @param float|null  $min          Минимальное значение
     * @param float|null  $max          Максимальное значение
     * @param string|null $tag          Тег
     *
     * @return float
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getNumericNN(string $name, float $defaultValue = null, float $min = null, float $max = null, string $tag = null): float;

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
    public function getUUID(string $name, string $defaultValue = null, string $tag = null): ?string;

    /**
     * Возвращает не пустое значение параметра идентификатора
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getUUIDNN(string $name, string $defaultValue = null, string $tag = null): string;

    /**
     * Возвращает значение перечислительного параметра
     *
     * @param string      $name         Имя параметра
     * @param array       $values       Возможоые значения
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getEnum(string $name, array $values, string $defaultValue = null, string $tag = null): ?string;

    /**
     * Возвращает значение перечислительного параметра
     *
     * @param string      $name         Имя параметра
     * @param array       $values       Возможоые значения
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getEnumNN(string $name, array $values, string $defaultValue = null, string $tag = null): string;

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
    public function getEmail(string $name, string $defaultValue = null, string $tag = null): ?string;

    /**
     * Возвращает не пустое значение параметра адреса электронной почты
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getEmailNN(string $name, string $defaultValue = null, string $tag = null): string;

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
    public function getPhone(string $name, string $defaultValue = null, string $tag = null): ?string;

    /**
     * Возвращает не пустое значение параметра номера телефона
     *
     * @param string      $name         Имя параметра
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getPhoneNN(string $name, string $defaultValue = null, string $tag = null): string;

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
     * Возвращает значение не пустого строкового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayStringNN(string $name, string $element, string $defaultValue = null, string $tag = null): string;

    /**
     * Возвращает значение логического элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param bool        $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return bool
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayBool(string $name, string $element, bool $defaultValue = false, string $tag = null): bool;

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
    public function getArrayInteger(string $name, string $element, int $defaultValue = null, string $tag = null): ?int;

    /**
     * Возвращает не пустое значение целочисленного элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param int|null    $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return int
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayIntegerNN(string $name, string $element, int $defaultValue = null, string $tag = null): int;

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
    public function getArrayNumeric(string $name, string $element, float $defaultValue = null, string $tag = null): ?float;

    /**
     * Возвращает не пустое значение числового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param float|null  $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return float
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayNumericNN(string $name, string $element, float $defaultValue = null, string $tag = null): float;

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
    public function getArrayUUID(string $name, string $element, string $defaultValue = null, string $tag = null): ?string;

    /**
     * Возвращает массив идентификаторов UUID
     *
     * @param string      $name         Имя параметра
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return array
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getUUIDArray(string $name, string $element, string $defaultValue = null, string $tag = null): array;

    /**
     * Возвращает не пустое значение идентификатора из массива
     *
     * @param string      $name         Имя параметра
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     * @param string|null $tag          Тег
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ValidatorException
     */
    public function getArrayUUIDNN(string $name, string $element, string $defaultValue = null, string $tag = null): string;

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

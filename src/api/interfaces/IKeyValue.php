<?php declare(strict_types = 1);

/**
 * IKeyValue.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use \Iterator;

/**
 * Описывает методы хранилища Ключ-Значение
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IKeyValue extends Iterator {

    /**
     * Удаляет все сохраненные значения
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Возвращает признак пустого хранилища
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Возвращает ранее сохраненное значение
     *
     * @param string     $key          Ключ
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     */
    public function get(string $key, $defaultValue = null);

    /**
     * Сохраняет значение
     *
     * @param string     $key   Ключ
     * @param mixed|null $value Значение
     *
     * @return void
     */
    public function put(string $key, $value = null): void;

    /**
     * Удаляет ранее сохраненное значение
     *
     * @param string $key Ключ
     *
     * @return void
     */
    public function delete(string $key): void;

    /**
     * Возвращает признак существования значения
     *
     * @param string $key Ключ
     *
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Возвращает массив ключей
     *
     * @return array
     */
    public function keys(): array;

    /**
     * Возвращает сохраняемые значения в виде массива
     *
     * @return array
     */
    public function toArray(): array;
}

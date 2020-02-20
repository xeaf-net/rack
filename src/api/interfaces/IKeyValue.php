<?php

/**
 * IKeyValue.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
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
    function clear(): void;

    /**
     * Возвращает признак пустого хранилища
     *
     * @return bool
     */
    function isEmpty(): bool;

    /**
     * Возвращает ранее сохраненное значение
     *
     * @param string     $key          Ключ
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     */
    function get(string $key, $defaultValue = null);

    /**
     * Сохраняет значение
     *
     * @param string     $key   Ключ
     * @param mixed|null $value Значение
     *
     * @return void
     */
    function put(string $key, $value = null): void;

    /**
     * Удаляет ранее сохраненное значение
     *
     * @param string $key Ключ
     *
     * @return void
     */
    function delete(string $key): void;

    /**
     * Возвращает признак существования значения
     *
     * @param string $key Ключ
     *
     * @return bool
     */
    function exists(string $key): bool;

    /**
     * Возвращает массив ключей
     *
     * @return array
     */
    function keys(): array;

    /**
     * Возвращает сохраняемые значения в виде массива
     *
     * @return array
     */
    function toArray(): array;
}

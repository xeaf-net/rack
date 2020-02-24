<?php

/**
 * ICacheStorage.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Interfaces;

use XEAF\Rack\API\Interfaces\INamedObject;

/**
 * Описывает методы работы с кешем
 *
 * @package XEAF\Rack\Interfaces
 */
interface ICacheStorage extends INamedObject {

    /**
     * Возвращает значение из кеша
     *
     * @param string $key          Ключ
     * @param null   $defaultValue Значение по умолчанию
     *
     * @return mixed|null
     */
    function get(string $key, $defaultValue = null);

    /**
     * Сохраняет значение в кеше
     *
     * @param string $key   Ключ
     * @param null   $value Значение
     * @param int    $ttl   Время жизни
     * @param array  $tags  Набор тегов
     *
     * @return void
     */
    function put(string $key, $value = null, int $ttl = 0, array $tags = []): void;

    /**
     * Возвращает набор связанных со значением тегов
     *
     * @param string $key Ключ
     *
     * @return array
     */
    function getTags(string $key): array;

    /**
     * Связывает значение кеша с одним или несколькими тегами
     *
     * @param string $key  Ключ
     * @param array  $tags Массив тегов
     *
     * @return void
     */
    function setTags(string $key, array $tags): void;

    /**
     * Отменяет валидность значения кеша
     *
     * @param string $key Ключ
     *
     * @return void
     */
    function invalidate(string $key): void;

    /**
     * Отменяет валидность всех связанных с тегом переменных
     *
     * @param string $tag Тег
     *
     * @return void
     */
    function invalidateTag(string $tag): void;

    /**
     * Отменяет валидность всех значений кеша
     *
     * @return void
     */
    function invalidateAll(): void;
}

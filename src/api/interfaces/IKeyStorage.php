<?php

/**
 * IkeyStorage.php
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
 * Описывает методы хранилища Ключ-Значение с указанием времени жизни
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IKeyStorage extends IKeyValue {

    /**
     * Сохраняет значение
     *
     * @param string     $key   Ключ
     * @param mixed|null $value Значение
     * @param int        $ttl   Время жизни значения
     *
     * @return void
     */
    function put(string $key, $value = null, int $ttl = 0): void;
}

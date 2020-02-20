<?php

/**
 * ICookies.php
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
 * Описывает методы работы с Cookie
 *
 * @package XEAF\Rack\API\Utils
 */
interface ICookies extends IKeyStorage, IFactoryObject {

    /**
     * @inheritDoc
     */
    function clear(): void;

    /**
     * @inheritDoc
     */
    function get(string $key, $defaultValue = null);

    /**
     * @inheritDoc
     */
    function put(string $key, $value = null, int $ttl = 0): void;

    /**
     * @inheritDoc
     */
    function delete(string $key): void;

    /**
     * @inheritDoc
     */
    function exists(string $key): bool;

    /**
     * @inheritDoc
     */
    function keys(): array;

    /**
     * @inheritDoc
     */
    function toArray(): array;
}

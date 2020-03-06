<?php declare(strict_types = 1);

/**
 * ICookies.php
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
 * Описывает методы работы с Cookie
 *
 * @package XEAF\Rack\API\Utils
 */
interface ICookies extends IKeyStorage, IFactoryObject {

    /**
     * @inheritDoc
     */
    public function clear(): void;

    /**
     * @inheritDoc
     */
    public function get(string $key, $defaultValue = null);

    /**
     * @inheritDoc
     */
    public function put(string $key, $value = null, int $ttl = 0): void;

    /**
     * @inheritDoc
     */
    public function delete(string $key): void;

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool;

    /**
     * @inheritDoc
     */
    public function keys(): array;

    /**
     * @inheritDoc
     */
    public function toArray(): array;
}

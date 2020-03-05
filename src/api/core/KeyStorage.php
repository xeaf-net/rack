<?php declare(strict_types = 1);

/**
 * KeyStorage.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\IKeyStorage;

/**
 * Реализует методы хранилища Ключ-Значение с указанием времени жизни
 *
 * @package XEAF\Rack\API\Core
 */
abstract class KeyStorage extends KeyValue implements IKeyStorage {

    /**
     * @inheritDoc
     */
    public function put(string $key, $value = null, int $ttl = 0): void {
        parent::put($key, $value);
    }
}

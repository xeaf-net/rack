<?php

/**
 * autoload.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Redis;

use Throwable;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Storage;
use XEAF\Rack\Redis\Utils\Storage\RedisStorageProvider;

try {
    Storage::registerProvider(RedisStorageProvider::PROVIDER_NAME, RedisStorageProvider::class);
} catch (Throwable $fatalError) {
    Logger::fatalError($fatalError->getMessage(), $fatalError);
}

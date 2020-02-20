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
namespace XEAF\Rack\Db;

use Throwable;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Storage;
use XEAF\Rack\Db\Utils\Storage\DatabaseStorageProvider;

try {
    Storage::registerProvider(DatabaseStorageProvider::PROVIDER_NAME, DatabaseStorageProvider::class);
} catch (Throwable $fatalError) {
    Logger::fatalError($fatalError->getMessage(), $fatalError);
}


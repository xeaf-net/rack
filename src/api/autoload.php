<?php

/**
 * autoload.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API;

use Throwable;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Loggers\FileLoggerProvider;
use XEAF\Rack\API\Utils\Session;
use XEAF\Rack\API\Utils\Sessions\NativeSessionProvider;
use XEAF\Rack\API\Utils\Sessions\StaticSessionProvider;
use XEAF\Rack\API\Utils\Sessions\StorageSessionProvider;
use XEAF\Rack\API\Utils\Storage;
use XEAF\Rack\API\Utils\Storage\FileStorageProvider;
use XEAF\Rack\API\Utils\Storage\StaticStorageProvider;

try {
    Storage::registerProvider(StaticStorageProvider::PROVIDER_NAME, StaticStorageProvider::class);
    Storage::registerProvider(FileStorageProvider::PROVIDER_NAME, FileStorageProvider::class);
    Logger::registerProvider(FileLoggerProvider::PROVIDER_NAME, FileLoggerProvider::class);
    Session::registerProvider(StaticSessionProvider::PROVIDER_NAME, StaticSessionProvider::class);
    Session::registerProvider(StorageSessionProvider::PROVIDER_NAME, StorageSessionProvider::class);
    Session::registerProvider(NativeSessionProvider::PROVIDER_NAME, NativeSessionProvider::class);
} catch (Throwable $fatalError) {
    Logger::fatalError($fatalError->getMessage(), $fatalError);
}

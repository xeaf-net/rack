<?php declare(strict_types = 1);

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
namespace XEAF\Rack\Db\PgSQL;

use Throwable;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\Db\PgSQL\Utils\Providers\PgSqlProvider;
use XEAF\Rack\Db\Utils\Database;

try {
    Database::registerProvider(PgSqlProvider::PROVIDER_NAME, PgSqlProvider::class);
} catch (Throwable $fatalError) {
    Logger::fatalError($fatalError->getMessage(), $fatalError);
}


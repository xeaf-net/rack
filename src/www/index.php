<?php

/**
 * index.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\WWW;

use XEAF\Rack\API\App\Application;

/**
 * Путь к папке с файлами конфигурации
 */
define('__XEAF_RACK_CONFIG_DIR__', __DIR__ . '/../etc');

/**
 * Путь к директории файлов сторонних поставщиков
 */
define('__XEAF_RACK_VENDOR_DIR__', __DIR__ . '/../../vendor');

/**
 * Признак режима отладки
 */
define('__XEAF_RACK_DEBUG_MODE__', true);

/**
 * Подгрузка autoload.php
 */
require_once __XEAF_RACK_VENDOR_DIR__ . '/autoload.php';

/**
 * Запуск приложения
 */
(new Application())->run();

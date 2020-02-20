<?php

/**
 * index.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Space\WWW;

use XEAF\Rack\API\App\Application;

//
// Глобальные параметры приложения
//
define('__XEAF_RACK_CONFIG_DIR__', __DIR__ . '/../etc');
define('__XEAF_RACK_VENDOR_DIR__', __DIR__ . '/../../vendor');
define('__XEAF_RACK_DEBUG_MODE__', true);

/** @noinspection PhpIncludeInspection */
require_once __XEAF_RACK_VENDOR_DIR__ . '/autoload.php';

(new Application())->run();

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
namespace XEAF\Rack\UI;

use Throwable;
use XEAF\Rack\API\Utils\Assets;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\UI\Utils\Engines\SmartyTemplateEngine;
use XEAF\Rack\UI\Utils\TemplateEngine;

try {
    TemplateEngine::registerProvider(SmartyTemplateEngine::PROVIDER_NAME, SmartyTemplateEngine::class);
    Assets::getInstance()->registerPublicFolder(__DIR__ . '/public');
} catch (Throwable $fatalError) {
    Logger::fatalError($fatalError->getMessage(), $fatalError);
}


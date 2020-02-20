<?php

/**
 * SessionModule.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Modules\Tools;

use XEAF\Rack\API\Core\Module;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Models\Results\StatusResult;
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Session;

/**
 * Реализует методы задания параметров сессии
 *
 * @package  XEAF\Rack\API\Modules\Tools
 */
class SessionModule extends Module {

    /**
     * Путь к модулю
     */
    public const MODULE_PATH = '/session';

    /**
     * Вызов метода по умолчанию
     *
     * @param \XEAF\Rack\API\Utils\Localization $l10n    Объект методов локализации
     * @param \XEAF\Rack\API\Utils\Session      $session Объект методов работы с сессиями
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    public function processGet(Localization $l10n, Session $session): ?IActionResult {
        $locale = $this->getActionArgs()->get('locale');
        if ($locale) {
            $session->setLocale($locale);
            $l10n->setDefaultLocale($locale);
        }
        return StatusResult::ok();
    }
}

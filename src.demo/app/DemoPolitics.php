<?php declare(strict_types = 1);

/**
 * DemoPolitics.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Demo\App;

use XEAF\Rack\API\Utils\Politics;

/**
 * Определяет политику демонстрационного приложения
 *
 * @package  XEAF\Rack\Demo\App
 */
class DemoPolitics extends Politics {

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function allowDebugMode(): bool {
        return true;
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function allowNativeSession(): bool {
        return true;
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function forceFormResult200(): bool {
        return true;
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function dataResultTimestamp(): bool {
        return true;
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function dataResultJSTimestamp(): bool {
        return true;
    }

}

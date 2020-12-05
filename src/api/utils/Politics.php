<?php declare(strict_types = 1);

/**
 * Politics.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IPolitics;

/**
 * Возвращает информацию о политике функционирования приложения
 *
 * @package  XEAF\Rack\API\Utils
 */
class Politics implements IPolitics {

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function allowNativeSession(): bool {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function allowDebugMode(): bool {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function forceFormResult200(): bool {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function dataResultTimestamp(): bool {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function dataResultJSTimestamp(): bool {
        return false;
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IPolitics
     */
    public static function getInstance(): IPolitics {
        $result = Factory::getFactoryObject(IPolitics::class);
        assert($result instanceof IPolitics);
        return $result;
    }
}

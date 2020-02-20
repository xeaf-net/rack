<?php

/**
 * Formatter.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IFormatter;
use XEAF\Rack\API\Interfaces\ILocale;

/**
 * Реализует методы форматирования данных
 *
 * @package XEAF\Rack\API\Utils
 */
class Formatter implements IFormatter {

    /**
     * Формат даты и времени для кеша
     */
    private const CACHE_DT_FORMAT = 'D, d M Y H:i:s';

    /**
     * Объект методов локализации
     * @var \XEAF\Rack\API\Interfaces\ILocalization
     */
    private $_l10n = null;

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->_l10n = Localization::getInstance();
    }

    /**
     * @inheritDoc
     */
    public function formatInteger(int $number, string $locale = null): string {
        $ts = $this->selectLocale($locale)->getThousandsSeparator();
        return number_format($number, 0, '', $ts);
    }

    /**
     * @inheritDoc
     */
    public function formatNumeric(float $number, int $dec = 2, string $locale = null): string {
        $lc = $this->selectLocale($locale);
        $dp = $lc->getDecimalPoint();
        $ts = $lc->getThousandsSeparator();
        return number_format($number, $dec, $dp, $ts);
    }

    /**
     * @inheritDoc
     */
    public function formatDate(int $date, string $locale = null): string {
        $fmt = $this->selectLocale($locale)->getDateFormat();
        return date($fmt, $date);
    }

    /**
     * @inheritDoc
     */
    public function formatTime(int $time, string $locale = null): string {
        $fmt = $this->selectLocale($locale)->getTimeFormat();
        return date($fmt, $time);
    }

    /**
     * @inheritDoc
     */
    public function formatDateTime(int $dateTime, string $locale = null): string {
        $fmt = $this->selectLocale($locale)->getDateTimeFormat();
        return date($fmt, $dateTime);
    }

    /**
     * @inheritDoc
     */
    public function formatCacheDateTime(int $dateTime): string {
        return gmdate(self::CACHE_DT_FORMAT, $dateTime) . ' ' . Calendar::GMT;
    }

    /**
     * Возвращает заданную локаль
     *
     * @param string|null $locale Имя локали
     *
     * @return \XEAF\Rack\API\Interfaces\ILocale
     */
    private function selectLocale(string $locale = null): ILocale {
        return $locale == null ? $this->_l10n->getDefaultLocale() : $this->_l10n->getLocale($locale);
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IFormatter
     */
    public static function getInstance(): IFormatter {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IFormatter);
        return $result;
    }
}

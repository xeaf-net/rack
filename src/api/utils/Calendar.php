<?php declare(strict_types = 1);

/**
 * Calendar.php
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
use XEAF\Rack\API\Interfaces\ICalendar;

/**
 * Реализует методы работы с датами
 *
 * @package  XEAF\Rack\API\Utils
 */
class Calendar implements ICalendar {

    /**
     * Идентификатор времени по Гринвичу
     */
    public const GMT = 'GMT';

    /**
     * Идентификатор всемирного координированного времени
     */
    public const UTC = 'UTC';

    /**
     * Количество секунд в часе
     */
    public const SECONDS_PER_HOUR = 60 * 60;

    /**
     * Количество секунд в сутках
     */
    public const SECONDS_PER_DAY = 24 * 60 * 60;

    /**
     * Формат первого дня месяца
     */
    protected const FDM = 'Y-m-01';

    /**
     * Формат последнего дня месяца
     */
    protected const LDM = 'Y-m-t';

    /**
     * Фоормат первого дня года
     */
    protected const FDY = 'Y-01-01';

    /**
     * Формат последнего дня года
     */
    protected const LDY = 'Y-12-31';

    /**
     * Формат нормализованного представления даты
     */
    protected const NORMALIZE_DATE = 'Y-m-d';

    /**
     * Формат нормализованного представления даты и времени
     */
    protected const NORMALIZE_DATETIME = 'Y-m-d H:i:s';

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function now(): int {
        return time();
    }

    /**
     * @inheritDoc
     */
    public function today(): int {
        return $this->dateTimeToDate($this->now());
    }

    /**
     * @inheritDoc
     */
    public function yesterday(?int $date = null): int {
        $start = is_null($date) ? $this->today() : $this->dateTimeToDate($date);
        return $start - self::SECONDS_PER_DAY;
    }

    /**
     * @inheritDoc
     */
    public function tomorrow(?int $date = null): int {
        $start = is_null($date) ? $this->today() : $this->dateTimeToDate($date);
        return $start + self::SECONDS_PER_DAY;
    }

    /**
     * @inheritDoc
     */
    public function firstDayOfMonth(int $date): int {
        $strings = Strings::getInstance();
        return $strings->stringToDateTime(date(self::FDM, $date));
    }

    /**
     * @inheritDoc
     */
    public function lastDayOfMonth(int $date): int {
        $strings = Strings::getInstance();
        return $strings->stringToDateTime(date(self::LDM, $date));
    }

    /**
     * @inheritDoc
     */
    public function firstDayOfYear(int $date): int {
        $strings = Strings::getInstance();
        return $strings->stringToDateTime(date(self::FDY, $date));
    }

    /**
     * @inheritDoc
     */
    public function lastDayOfYear(int $date): int {
        $strings = Strings::getInstance();
        return $strings->stringToDateTime(date(self::LDY, $date));
    }

    /**
     * @inheritDoc
     */
    public function parseDateTime(int $dateTime = null): array {
        $dt = $this->normalizeDateTime($dateTime);
        return date_parse_from_format(self::NORMALIZE_DATETIME, $dt);
    }

    /**
     * @inheritDoc
     */
    public function getDay(int $date = null): int {
        $arr = $this->parseDateTime($date);
        return $arr['day'];
    }

    /**
     * @inheritDoc
     */
    public function getMonth(int $date = null): int {
        $arr = $this->parseDateTime($date);
        return $arr['month'];
    }

    /**
     * @inheritDoc
     */
    public function getYear(int $date = null): int {
        $arr = $this->parseDateTime($date);
        return $arr['year'];
    }

    /**
     * @inheritDoc
     */
    public function getHours(int $dateTime = null): int {
        $arr = $this->parseDateTime($dateTime);
        return $arr['hour'];
    }

    /**
     * @inheritDoc
     */
    public function getMinutes(int $dateTime): int {
        $arr = $this->parseDateTime($dateTime);
        return $arr['minute'];
    }

    /**
     * @inheritDoc
     */
    public function getSeconds(int $dateTime): int {
        $arr = $this->parseDateTime($dateTime);
        return $arr['second'];
    }

    /**
     * @inheritDoc
     */
    public function dateTimeToDate(int $dateTime): int {
        return strtotime(date(self::NORMALIZE_DATE, $dateTime));
    }

    /**
     * @inheritDoc
     */
    public function normalizeDate(int $date = null): string {
        $dt = $date == null ? $this->today() : $date;
        return date(self::NORMALIZE_DATE, $dt);
    }

    /**
     * @inheritDoc
     */
    public function normalizeDateTime(int $dateTime = null): string {
        $dt = $dateTime == null ? $this->now() : $dateTime;
        return date(self::NORMALIZE_DATETIME, $dt);
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\ICalendar
     */
    public static function getInstance(): ICalendar {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ICalendar);
        return $result;
    }
}

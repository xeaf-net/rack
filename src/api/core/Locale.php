<?php

/**
 * Locale.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\ILocale;

/**
 * Реализует базовые методы локали
 *
 * @property-read string $name               Имя локали
 * @property-read string $language           Язык
 * @property-read string $lang               Сокращенное название языка
 * @property-read string $dir                Направление письма
 * @property-read string $dateFormat         Формат даты
 * @property-read string $timeFormat         Формат времени
 * @property-read string $dateTimeFormat     Формат даты и времени
 * @property-read string $decimalPoint       Десятичная точка
 * @property-read string $thousandsSeparator Разделитель разрядов
 *
 * @package  XEAF\Rack\API\Core
 */
class Locale extends DataModel implements ILocale {

    /**
     * Направление слева направо
     */
    public const LTR = 'ltr';

    /**
     * Направление справа налево
     */
    public const RTL = 'rtl';

    /**
     * Имя локали
     * @var string
     */
    protected $_name;

    /**
     * Язык
     * @var string
     */
    protected $_language;

    /**
     * Сокращенное название языка
     * @var string
     */
    protected $_lang;

    /**
     * Направление письма
     * @var string
     */
    protected $_dir;

    /**
     * Формат даты
     * @var string
     */
    protected $_dateFormat;

    /**
     * Формат времени
     * @var string
     */
    protected $_timeFormat;

    /**
     * Формат даты и времени
     * @var string
     */
    protected $_dateTimeFormat;

    /**
     * Десятичная точка
     * @var string
     */
    protected $_decimalPoint;

    /**
     * Разделитель разрядов
     * @var string
     */
    protected $_thousandsSeparator = '';

    /**
     * @inheritDoc
     */
    function getName(): string {
        return $this->_name;
    }

    /**
     * @inheritDoc
     */
    function getLanguage(): string {
        return $this->_language;
    }

    /**
     * @inheritDoc
     */
    function getLang(): string {
        return $this->_lang;
    }

    /**
     * @inheritDoc
     */
    function getDir(): string {
        return $this->_dir;
    }

    /**
     * @inheritDoc
     */
    function getDateFormat(): string {
        return $this->_dateFormat;
    }

    /**
     * @inheritDoc
     */
    function getTimeFormat(): string {
        return $this->_timeFormat;
    }

    /**
     * @inheritDoc
     */
    function getDateTimeFormat(): string {
        return $this->_dateTimeFormat;
    }

    /**
     * @inheritDoc
     */
    function getDecimalPoint(): string {
        return $this->_decimalPoint;
    }

    /**
     * @inheritDoc
     */
    function getThousandsSeparator(): string {
        return $this->_thousandsSeparator;
    }
}

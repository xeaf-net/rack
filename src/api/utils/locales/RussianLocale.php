<?php declare(strict_types = 1);

/**
 * RussianLocale.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Locales;

use XEAF\Rack\API\Core\Locale;

/**
 * Реализует методы локали русского языка
 *
 * @package XEAF\Rack\API\Utils\Locales
 */
class RussianLocale extends Locale {

    /**
     * Имя локали
     */
    public const LOCALE_NAME = 'ru_RU';

    /**
     * @inheritDoc
     */
    public function __construct() {
        parent::__construct();
        $this->_name               = self::LOCALE_NAME;
        $this->_language           = 'Русский';
        $this->_lang               = 'ru';
        $this->_dir                = self::LTR;
        $this->_dateFormat         = 'd.m.Y';
        $this->_timeFormat         = 'H:i:s';
        $this->_dateTimeFormat     = 'd.m.Y H:i:s';
        $this->_decimalPoint       = ',';
        $this->_thousandsSeparator = ' ';
    }
}

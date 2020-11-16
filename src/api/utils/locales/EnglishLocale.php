<?php declare(strict_types = 1);

/**
 * EnglishLocale.php
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
 * Реализует методы локали английского языка
 *
 * @package XEAF\Rack\API\Utils\Locales
 */
class EnglishLocale extends Locale {

    /**
     * Имя локали
     */
    public const LOCALE_NAME = 'en_US';

    /**
     * @inheritDoc
     */
    public function __construct() {
        parent::__construct();
        $this->_name               = self::LOCALE_NAME;
        $this->_language           = 'English';
        $this->_lang               = 'en';
        $this->_dir                = self::LTR;
        $this->_dateFormat         = 'Y/m/d';
        $this->_timeFormat         = 'h:i:s a';
        $this->_dateTimeFormat     = 'Y/m/d h:i:s a';
        $this->_decimalPoint       = '.';
        $this->_thousandsSeparator = "'";
    }
}

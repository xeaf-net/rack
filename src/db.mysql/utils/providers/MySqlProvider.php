<?php

/**
 * MySqlProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\MySQL\Utils\Providers;

use XEAF\Rack\Db\Core\DatabaseProvider;

/**
 * Реализует методы провайдера подключения к MySQL
 *
 * @package XEAF\Rack\Db\MySQL\Utils\Providers
 */
class MySqlProvider extends DatabaseProvider {

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'mysql';

    /**
     * @inheritDoc
     */
    public function dateExpression(string $expression, string $locale = null): string {
        $format = $this->_localization->getLanguageVar('mysql.dateFormat', $locale);
        return "to_char($expression, '$format')";
    }

    /**
     * @inheritDoc
     */
    public function timeExpression(string $expression, string $locale = null): string {
        $format = $this->_localization->getLanguageVar('mysql.timeFormat', $locale);
        return "to_char($expression, '$format')";
    }

    /**
     * @inheritDoc
     */
    public function dateTimeExpression(string $expression, string $locale = null): string {
        $format = $this->_localization->getLanguageVar('mysql.dateTimeFormat', $locale);
        return "to_char($expression, '$format')";
    }
}

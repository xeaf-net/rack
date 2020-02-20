<?php

/**
 * PgSqlProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\PgSQL\Utils\Providers;

use XEAF\Rack\Db\Core\DatabaseProvider;

/**
 * Реализует методы провайдера подключения к PostgreSQL
 *
 * @package XEAF\Rack\Db\PgSQL\Utils\Providers
 */
class PgSqlProvider extends DatabaseProvider {

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'pgsql';

    /**
     * @inheritDoc
     */
    public function dateExpression(string $expression, string $locale = null): string {
        $format = $this->_localization->getLanguageVar('pgsql.dateFormat', $locale);
        return "to_char($expression, '$format')";
    }

    /**
     * @inheritDoc
     */
    public function timeExpression(string $expression, string $locale = null): string {
        $format = $this->_localization->getLanguageVar('pgsql.timeFormat', $locale);
        return "to_char($expression, '$format')";
    }

    /**
     * @inheritDoc
     */
    public function dateTimeExpression(string $expression, string $locale = null): string {
        $format = $this->_localization->getLanguageVar('pgsql.dateTimeFormat', $locale);
        return "to_char($expression, '$format')";
    }
}

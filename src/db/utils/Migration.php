<?php

/**
 * Migration.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\Db\Interfaces\IDatabase;
use XEAF\Rack\Db\Interfaces\IMigration;

/**
 * Реализует методы работы с миграциями
 *
 * @package XEAF\Rack\Db\Utils
 */
class Migration implements IMigration {

    /**
     * Имя продукта по умолчанию
     */
    public const DEFAULT_PRODUCT = 'XEAF-RACK-DB';

    /**
     * @inheritDoc
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function version(IDatabase $database, string $product = Migration::DEFAULT_PRODUCT): ?string {
        $sql  = $this->getVersionSQL();
        $data = $database->selectFirst($sql, ['product' => $product]);
        return ($data) ? $data['migration_version'] : null;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function checkVersion(IDatabase $database, string $version, string $product = Migration::DEFAULT_PRODUCT): bool {
        $mv = $this->version($database, $product);
        return version_compare($mv, $version, '>=');
    }

    /**
     * Возвращает текст SQL запроса получения номера версии
     *
     * @return string
     * @noinspection RedundantSuppression
     */
    private function getVersionSQL(): string {
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        return '
            select migration_version 
                from 
                     xeaf_migrations 
                where
                    migration_product = :product
                order by
                    migration_timestamp desc';
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\Db\Interfaces\IMigration
     */
    public static function getInstance(): IMigration {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IMigration);
        return $result;
    }
}

<?php

/**
 * IMigration.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Interfaces;

use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\Db\Utils\Migration;

/**
 * Описывает методы работы с миграциями
 *
 * @package XEAF\Rack\Db\Interfaces
 */
interface IMigration extends IFactoryObject {

    /**
     * Возвращает номер версии миграции
     *
     * @param \XEAF\Rack\Db\Interfaces\IDatabase $database Подключение к базе данных
     * @param string                             $product  Имя продукта
     *
     * @return string|null
     */
    function version(IDatabase $database, string $product = Migration::XEAF_RACK_PRODUCT): ?string;

    /**
     * Проверяет наличие требуемой версии миграции
     *
     * @param \XEAF\Rack\Db\Interfaces\IDatabase $database Подключение к базе данных
     * @param string                             $version  Минимальный номер версии
     * @param string                             $product  Имя продукта
     *
     * @return bool
     */
    function checkVersion(IDatabase $database, string $version, string $product = Migration::XEAF_RACK_PRODUCT): bool;
}

<?php declare(strict_types = 1);

/**
 * IDatabase.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Interfaces;

use XEAF\Rack\API\Interfaces\INamedObject;
use XEAF\Rack\API\Interfaces\IProviderFactory;

/**
 * Описывает методы работы с базой данных
 *
 * @package XEAF\Rack\Db\Interfaces
 */
interface IDatabase extends IDatabaseActions, INamedObject, IProviderFactory {

}

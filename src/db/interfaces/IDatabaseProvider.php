<?php

/**
 * IDatabaseProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Interfaces;

use XEAF\Rack\API\Interfaces\IProvider;

/**
 * Описывает методы провайдера подключения к базе данных
 *
 * @package XEAF\Rack\Db\Interfaces
 */
interface IDatabaseProvider extends IDatabaseActions, IProvider {

 }

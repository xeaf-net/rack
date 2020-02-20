<?php

/**
 * IRestAPI.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Реализует базовые методы для построения провайдеров обращения к внешним API
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IRestAPI {

    /**
     * Возвращает код статуса последнего обращения к API
     *
     * @return int
     */
    function getLastStatusCode(): int;

    /**
     * Возвращает признак состояния ошибки при обращении к стороннему API
     *
     * @return bool
     */
    function getErrorState(): bool;
}

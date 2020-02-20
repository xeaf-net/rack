<?php

/**
 * ISession.php
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
 * Описывает методы провайдера сессии
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ISession extends ISessionActions, IProviderFactory {

    /**
     * Возвращает признак авторизованной сесии
     *
     * @return bool
     */
    function isAuthorized(): bool;
}

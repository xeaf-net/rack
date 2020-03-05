<?php declare(strict_types = 1);

/**
 * ISession.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
    public function isAuthorized(): bool;
}

<?php

/**
 * IProviderFactory.php
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
 * Описывает методы фаюрики провайдеров
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IProviderFactory extends IFactoryObject {

    /**
     * Возвращает признак регистрации провайдера
     *
     * @param string $name Имя провайдера
     *
     * @return bool
     */
    static function isRegistered(string $name): bool;

    /**
     * Регистрирует новый провайдер
     *
     * @param string $name      Имя провайдера
     * @param string $className Имя класса
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    static function registerProvider(string $name, string $className): void;

    /**
     * Отменяет регистрацию провайдера
     *
     * @param string $name Имя провайдера
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    static function unregisterProvider(string $name): void;

    /**
     * Возвращает имя класса провайдера
     *
     * @param string $name Имя провайдера
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    static function getProviderClass(string $name): string;
}

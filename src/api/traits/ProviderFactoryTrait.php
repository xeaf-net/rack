<?php

/**
 * ProviderFactoryTrait.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Traits;

use XEAF\Rack\API\Utils\Exceptions\ProviderException;

/**
 * Содержит методы для реализации интерфейса IProviderFactory
 *
 * @package XEAF\Rack\API\Traits
 */
trait ProviderFactoryTrait {

    /**
     * Список зарегистрированных провайдеров
     * @var array
     */
    private static $_providers = [];

    /**
     * Возвращает признак регистрации провайдера
     *
     * @param string $name Имя провайдера
     *
     * @return bool
     */
    public static function isRegistered(string $name): bool {
        return array_key_exists($name, self::$_providers);
    }

    /**
     * Регистрирует новый провайдер
     *
     * @param string $name      Имя провайдера
     * @param string $className Имя класса
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public static function registerProvider(string $name, string $className): void {
        if (!self::isRegistered($name)) {
            self::$_providers[$name] = $className;
        } else {
            throw ProviderException::providerAlreadyRegistered($name);
        }
    }

    /**
     * Отменяет регистрацию провайдера
     *
     * @param string $name Имя провайдера
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public static function unregisterProvider(string $name): void {
        if (self::isRegistered($name)) {
            unset(self::$_providers[$name]);
        } else {
            throw ProviderException::providerNotRegistered($name);
        }
    }

    /**
     * Возвращает имя класса провайдера
     *
     * @param string $name Имя провайдера
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public static function getProviderClass(string $name): string {
        if (self::isRegistered($name)) {
            return self::$_providers[$name];
        }
        throw ProviderException::providerNotRegistered($name);
    }
}

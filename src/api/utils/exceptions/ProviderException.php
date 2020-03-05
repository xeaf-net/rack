<?php declare(strict_types = 1);

/**
 * ProviderException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе с провайдерами
 *
 * @package XEAF\Rack\API\Utils\Exceptions
 */
class ProviderException extends Exception {

    /**
     * Провайдер не зерегистрирован
     */
    public const PROVIDER_NOT_REGISTERED = 'PRV001';

    /**
     * Провайдер уже зарегистрирован
     */
    public const PROVIDER_ALREADY_REGISTERED = 'PRV002';

    /**
     * Неподдерживаемый класс провайдера
     */
    public const UNSUPPORTED_PROVIDER_CLASS = 'PRV003';

    /**
     * Провайдер не зарегистрирован
     *
     * @param string $name Имя провайдера
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public static function providerNotRegistered(string $name): self {
        return new self(self::PROVIDER_NOT_REGISTERED, [$name]);
    }

    /**
     * Провайдер уже зарегистрирован
     *
     * @param string $name Имя провайдера
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public static function providerAlreadyRegistered(string $name): self {
        return new self(self::PROVIDER_ALREADY_REGISTERED, [$name]);
    }

    /**
     * Неподдерживаемый класс провайдера
     *
     * @param string $name      Имя провайдера
     * @param string $className Имя класса
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public static function unsupportedProviderClass(string $name, string $className): self {
        return new self(self::UNSUPPORTED_PROVIDER_CLASS, [$name, $className]);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = null;
        switch ($code) {
            case self::PROVIDER_NOT_REGISTERED:
                $result = 'Provider not registered [%s].';
                break;
            case self::PROVIDER_ALREADY_REGISTERED:
                $result = 'Provider already registered [%s].';
                break;
            case self::UNSUPPORTED_PROVIDER_CLASS:
                $result = 'Unsupported provider class [%s %s].';
                break;
        }
        return $result;
    }
}

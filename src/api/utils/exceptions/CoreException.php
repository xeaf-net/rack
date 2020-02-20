<?php

/**
 * CoreException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения ядра проекта
 *
 * @package XEAF\Rack\API\Utils\Exceptions
 */
class CoreException extends Exception {

    /**
     * Обращение к неизвестому методу
     */
    public const CALL_TO_UNKNOWN_METHOD = 'COR001';

    /**
     * Ошибка чтения значения свойства
     */
    public const PROPERTY_IS_NOT_READABLE = 'COR002';

    /**
     * Ошибка задания значения свойства
     */
    public const PROPERTY_IS_NOT_WRITABLE = 'COR003';

    /**
     * Внутренняя ошибка рефлексии
     */
    public const INTERNAL_REFLECTION_ERROR = 'COR004';

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = null;
        switch ($code) {
            case self::CALL_TO_UNKNOWN_METHOD:
                $result = 'Call to unknown method %s::%s().';
                break;
            case self::PROPERTY_IS_NOT_READABLE:
                $result = 'Property %s::%s is not readable.';
                break;
            case self::PROPERTY_IS_NOT_WRITABLE:
                $result = 'Property %s::%s is not writable.';
                break;
            case self::INTERNAL_REFLECTION_ERROR:
                $result = 'Internal reflection error.';
                break;
        }
        return $result;
    }

    /**
     * Обращение к неизвестному методу
     *
     * @param string $className Имя класса
     * @param string $name      Имя метода
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public static function callToUnknownMethod(string $className, string $name): self {
        return new self(self::CALL_TO_UNKNOWN_METHOD, [$className, $name]);
    }

    /**
     * Ошибка чтения значения свойства
     *
     * @param string $className Имя класса
     * @param string $name      Имя свойства
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public static function propertyIsNotReadable(string $className, string $name): self {
        return new self(self::PROPERTY_IS_NOT_READABLE, [$className, $name]);
    }

    /**
     * Ошибка задания значения свойства
     *
     * @param string $className Имя класса
     * @param string $name      Имя свойства
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public static function propertyIsNotWritable(string $className, string $name): self {
        return new self(self::PROPERTY_IS_NOT_WRITABLE, [$className, $name]);
    }

    /**
     * Внутренняя ошибка рефлексии
     *
     * @param Throwable $previous Причина возникновения ошибки
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public static function internalReflectionError(Throwable $previous): self {
        return new self(self::INTERNAL_REFLECTION_ERROR, [], $previous);
    }
}

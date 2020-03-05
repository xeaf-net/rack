<?php

/**
 * AccessTypes.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils;

/**
 * Содержит константы видов доступа к свойствам
 *
 * @package  XEAF\Rack\ORM\Utils
 */
class AccessTypes {

    /**
     * Виртуальное свойство
     */
    public const AC_VIRTUAL = 0x00;

    /**
     * Свойство только для чтения
     */
    public const AC_READABLE = 0x01;

    /**
     * Добавляемое свойство
     */
    public const AC_INSERTABLE = 0x02;

    /**
     * Обновляемое свойство
     */
    public const AC_UPDATABLE = 0x04;

    /**
     * Свойство с полным доступом
     */
    public const AC_DEFAULT = self::AC_READABLE | self::AC_INSERTABLE | self::AC_UPDATABLE;
}

<?php

/**
 * DataTypes.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Lex;

/**
 * Содержит константы типов данных
 *
 * @package XEAF\Rack\ORM\Utils\Lex
 */
class DataTypes {

    /**
     * Тип данных - UUID
     */
    public const DT_UUID = 1;

    /**
     * Тип данных - строка символов
     */
    public const DT_STRING = 2;

    /**
     * Тип данных - целое число
     */
    public const DT_INTEGER = 3;

    /**
     * Тип данных - действительное число
     */
    public const DT_NUMERIC = 4;

    /**
     * Тип данных - дата
     */
    public const DT_DATE = 5;

    /**
     * Тип данных - дата и время
     */
    public const DT_DATETIME = 6;

    /**
     * Тип данных - логическое значение
     */
    public const DT_BOOL = 7;

    /**
     * Тип данных - перечисление
     */
    public const DT_ENUM = 8;

    /**
     * Тип данных - массив
     */
    public const DT_ARRAY = 9;

    /**
     * Тип данных - объект
     */
    public const DT_OBJECT = 10;
}

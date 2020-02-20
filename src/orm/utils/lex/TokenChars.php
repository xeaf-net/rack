<?php

/**
 * TokenChars.php
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
 * Содердит константы символов лексем
 *
 * @package XEAF\Rack\ORM\Utils\Lex
 */
class TokenChars {

    /**
     * Символ пробела
     */
    public const SP = "\x20";

    /**
     * Символ перевода каретки
     */
    public const CR = "\x0D";

    /**
     * Символ перехода на начало строки
     */
    public const LF = "\x0A";

    /**
     * Одинарная кавычка (апостроф)
     */
    public const SQ = "\x27";

    /**
     * Заглавная бука A
     */
    public const UA = 'A';

    /**
     * Заглавная бука Z
     */
    public const UZ = 'Z';

    /**
     * Строчная бука a
     */
    public const LA = 'a';

    /**
     * Строчная буква z
     */
    public const LZ = 'z';

    /**
     * Цифра 0
     */
    public const D0 = '0';

    /**
     * Цифра 9
     */
    public const D9 = '9';

    /**
     * Процент
     */
    public const PC = '%';

    /**
     * Знак больше
     */
    public const GT = '>';

    /**
     * Знак меньше
     */
    public const LT = '<';

    /**
     * Знак равно
     */
    public const EQ = '=';

    /**
     * Восклицательный знак
     */
    public const EX = '!';

    /**
     * Открывающая круглая скобка
     */
    public const OB = '(';

    /**
     * Закрывающая круглая скобка
     */
    public const CB = ')';

    /**
     * Точка
     */
    public const PT = '.';

    /**
     * Двоеточие
     */
    public const CL = ':';

    /**
     * Запятая
     */
    public const CM = ',';

    /**
     * Ампесанд
     */
    public const AM = '&';

    /**
     * Символ подчеркивания
     */
    public const US = '_';

    /**
     * Вртикальная черта
     */
    public const PP = '|';

    /**
     * Стоп-смвол
     */
    public const STOP = "\xDA";

    /**
     * Пробельные символы
     */
    public const SPACE = [
        self::SP,
        self::CR,
        self::LF
    ];

    /**
     * Символы операторов
     */
    public const OPERATOR = [
        self::PC,
        self::GT,
        self::LT,
        self::EX,
        self::EQ,
        self::PP,
        self::AM
    ];

    /**
     * Символы разделителей
     */
    public const SEPARATOR = [
        self::PT,
        self::CM,
        self::CL
    ];
}

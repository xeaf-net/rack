<?php

/**
 * TokenOperators.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Lex;

/**
 * Содержит константы операторов
 *
 * @package XEAF\Rack\ORM\Utils\Lex
 */
class TokenOperators {

    /**
     * Открывающая скобка
     */
    public const OPEN_BRACKET = '(';

    /**
     * Закрывающая скобка
     */
    public const CLOSE_BRACKET = ')';

    /**
     * Плюс
     */
    public const NUMERIC_PLUS = '+';

    /**
     * Минус
     */
    public const NUMERIC_MINUS = '-';

    /**
     * Умножение
     */
    public const NUMERIC_MULTIPLY = '*';

    /**
     * Деление
     */
    public const NUMERIC_DIVIDE = '/';

    /**
     * Логическое И
     */
    public const BOOL_AND = '&&';

    /**
     * Логическое ИЛИ
     */
    public const BOOL_OR = '||';

    /**
     * Логическое НЕ
     */
    public const BOOL_NOT = '!';

    /**
     * Логическое Равно
     */
    public const BOOL_EQ = '==';

    /**
     * Логическое Не равно
     */
    public const BOOL_NE = '!=';

    /**
     * Логичесоке Больше
     */
    public const BOOL_GT = '>';

    /**
     * Логическое Больше или равно
     */
    public const BOOL_GE = '>=';

    /**
     * Логическое Меньше
     */
    public const BOOL_LT = '<';

    /**
     * Логическое Меньше или равно
     */
    public const BOOL_LE = '<=';

    /**
     * Логическое Как
     */
    public const BOOL_LIKE = '%%';
}

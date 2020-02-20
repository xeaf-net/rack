<?php

/**
 * TokenTypes.php
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
 * Описывает константы типов лексем
 *
 * @package XEAF\Rack\ORM\Utils\Lex
 */
class TokenTypes {

    /**
     * Нераспознанный идентификатор
     */
    public const ID_UNKNOWN = 000;

    /**
     * Константа
     */
    public const ID_CONSTANT = 101;

    /**
     * Параметр
     */
    public const ID_PARAMETER = 103;

    /**
     * Имя сущности
     */
    public const ID_ENTITY = 104;

    /**
     * Псевдоним сущности
     */
    public const ID_ALIAS = 105;

    /**
     * Имя свойства сущности
     */
    public const ID_PROPERTY = 106;

    /**
     * Константа NULL
     */
    public const ID_NULL = 107;

    /**
     * Константа FALSE
     */
    public const ID_FALSE = 108;

    /**
     * Константа TRUE
     */
    public const ID_TRUE = 109;

    /**
     * Стоп-символ
     */
    public const ID_STOP = 999;

    /**
     * Ключевое слово ASCENDING
     */
    public const KW_ASCENDING = 201;

    /**
     * Ключевое слово BY
     */
    public const KW_BY = 202;

    /**
     * Ключевое слово DESCENDING
     */
    public const KW_DESCENDING = 203;

    /**
     * Ключевое слово FROM
     */
    public const KW_FROM = 204;

    /**
     * Ключевое слово INNER
     */
    public const KW_INNER = 205;

    /**
     * Ключевое слово JOIN
     */
    public const KW_JOIN = 206;

    /**
     * Ключевое слово LEFT
     */
    public const KW_LEFT = 207;

    /**
     * Ключевое слово ON
     */
    public const KW_ON = 208;

    /**
     * Ключевое слово ORDER
     */
    public const KW_ORDER = 209;

    /**
     * Ключевое слово OUTER
     */
    public const KW_OUTER = 210;

    /**
     * Ключевое слово RIGHT
     */
    public const KW_RIGHT = 211;

    /**
     * Ключевое слово WHERE
     */
    public const KW_WHERE = 212;

    /**
     * Оператор Открытая скобка
     */
    public const OP_OPEN_BR = 301;

    /**
     * Оператор Закрытая скобка
     */
    public const OP_CLOSE_BR = 302;

    /**
     * Оператор Плюс
     */
    public const OP_PLUS = 303;

    /**
     * Оператор Минус
     */
    public const OP_MINUS = 304;

    /**
     * Оператор Умножение
     */
    public const OP_MULTIPLY = 305;

    /**
     * Оператор Деление
     */
    public const OP_DIVIDE = 306;

    /**
     * Оператор Логическое умножение
     */
    public const OP_AND = 307;

    /**
     * Оператор Логическое сложение
     */
    public const OP_OR = 308;

    /**
     * Оператор Логическое отрицание
     */
    public const OP_NOT = 309;

    /**
     * Оператор Равно
     */
    public const OP_EQ = 310;

    /**
     * Оператор Не равно
     */
    public const OP_NE = 311;

    /**
     * Оператор Больше
     */
    public const OP_GT = 312;

    /**
     * Больше или равно
     */
    public const OP_GE = 313;

    /**
     * Оператор меньше
     */
    public const OP_LT = 314;

    /**
     * Оператор Меньше или равно
     */
    public const OP_LE = 315;

    /**
     * Оператор Как
     */
    public const OP_LIKE = 316;

    /**
     * Разделитель Точка
     */
    public const SP_DOT = 401;

    /**
     * Разделитель Запятая
     */
    public const SP_COMMA = 402;

    /**
     * Разделитель Двоеточие
     */
    public const SP_COLON = 403;

    /**
     * Коды ключевых слов
     */
    public const KEY_WORD_CODES = [
        KeyWords::ASC        => self::KW_ASCENDING,
        KeyWords::ASCENDING  => self::KW_ASCENDING,
        KeyWords::BY         => self::KW_BY,
        KeyWords::DESC       => self::KW_DESCENDING,
        KeyWords::DESCENDING => self::KW_DESCENDING,
        KeyWords::FALSE      => self::ID_FALSE,
        KeyWords::FROM       => self::KW_FROM,
        KeyWords::INNER      => self::KW_INNER,
        KeyWords::JOIN       => self::KW_JOIN,
        KeyWords::LEFT       => self::KW_LEFT,
        KeyWords::NULL       => self::ID_NULL,
        KeyWords::ON         => self::KW_ON,
        KeyWords::ORDER      => self::KW_ORDER,
        KeyWords::OUTER      => self::KW_OUTER,
        KeyWords::RIGHT      => self::KW_RIGHT,
        KeyWords::TRUE       => self::ID_TRUE,
        KeyWords::WHERE      => self::KW_WHERE
    ];

    /**
     * Коды операторов
     */
    public const OPERATOR_CODES = [
        TokenOperators::OPEN_BRACKET     => self::OP_OPEN_BR,
        TokenOperators::CLOSE_BRACKET    => self::OP_CLOSE_BR,
        TokenOperators::NUMERIC_PLUS     => self::OP_PLUS,
        TokenOperators::NUMERIC_MINUS    => self::OP_MINUS,
        TokenOperators::NUMERIC_MULTIPLY => self::OP_MULTIPLY,
        TokenOperators::NUMERIC_DIVIDE   => self::OP_DIVIDE,
        TokenOperators::BOOL_AND         => self::OP_AND,
        TokenOperators::BOOL_OR          => self::OP_OR,
        TokenOperators::BOOL_NOT         => self::OP_NOT,
        TokenOperators::BOOL_EQ          => self::OP_EQ,
        TokenOperators::BOOL_NE          => self::OP_NE,
        TokenOperators::BOOL_GT          => self::OP_GT,
        TokenOperators::BOOL_GE          => self::OP_GE,
        TokenOperators::BOOL_LT          => self::OP_LT,
        TokenOperators::BOOL_LE          => self::OP_LE,
        TokenOperators::BOOL_LIKE        => self::OP_LIKE
    ];
}

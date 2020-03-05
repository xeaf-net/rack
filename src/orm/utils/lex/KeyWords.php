<?php declare(strict_types = 1);

/**
 * KeyWords.php
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
 * Содержит константы ключевых слов
 *
 * @package XEAF\Rack\ORM\Utils\Lex
 */
class KeyWords {

    /**
     * Ключевое слово ASC
     */
    public const ASC = 'asc';

    /**
     * Ключевое слово ASCENDING
     */
    public const ASCENDING = 'ascending';

    /**
     * Ключевое слово BY
     */
    public const BY = 'by';

    /**
     * Ключевое слово DESC
     */
    public const DESC = 'desc';

    /**
     * Ключевое слово DESCENDING
     */
    public const DESCENDING = 'descending';

    /**
     * Ключевое слово FALSE
     */
    public const FALSE = 'false';

    /**
     * Ключевое слово FILTER
     */
    public const FILTER = 'filter';

    /**
     * Ключевое слово FROM
     */
    public const FROM = 'from';

    /**
     * Ключевое слово INNER
     */
    public const INNER = 'inner';

    /**
     * Ключевое слово JOIN
     */
    public const JOIN = 'join';

    /**
     * Ключевое слово LEFT
     */
    public const LEFT = 'left';

    /**
     * Ключевое слово NULL
     */
    public const NULL = 'null';

    /**
     * Ключевое слово ON
     */
    public const ON = 'on';

    /**
     * Ключевое слово ORDER
     */
    public const ORDER = 'order';

    /**
     * Ключевое слово OUTER
     */
    public const OUTER = 'outer';

    /**
     * Ключевое слово RIGHT
     */
    public const RIGHT = 'right';

    /**
     * Ключевое слово TRUE
     */
    public const TRUE = 'true';

    /**
     * Ключевое слово WHERE
     */
    public const WHERE = 'where';

    /**
     * Список ключевых слов
     */
    public const KEY_WORDS = [
        self::ASC,
        self::ASCENDING,
        self::BY,
        self::DESC,
        self::DESCENDING,
        self::FALSE,
        self::FILTER,
        self::FROM,
        self::INNER,
        self::JOIN,
        self::LEFT,
        self::NULL,
        self::ON,
        self::ORDER,
        self::OUTER,
        self::RIGHT,
        self::TRUE,
        self::WHERE
    ];
}

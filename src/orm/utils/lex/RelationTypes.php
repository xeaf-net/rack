<?php declare(strict_types = 1);

/**
 * RelationTypes.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Lex;

/**
 * Содердит константы типов отношений
 *
 * @package XEAF\Rack\ORM\Utils\Lex
 */
class RelationTypes {

    /**
     * Один ко многим
     */
    public const ONE_TO_MANY = 1;

    /**
     * Многие к одному
     */
    public const MANY_TO_ONE = 2;

    /**
     * Многие ко многим
     */
    public const MANY_TO_MANY = 3;

}

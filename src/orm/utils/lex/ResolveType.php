<?php declare(strict_types = 1);

/**
 * ResolveType.php
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
 * Содержит константы типа разрешения значений
 *
 * @package  XEAF\Rack\ORM\Utils\Lex
 */
class ResolveType {

    /**
     * Нет разрешения значения
     */
    public const NONE = 0;

    /**
     * Отложенное разрешение значения
     */
    public const LAZY = 1;

    /**
     * Разрешение значения в момент обработки
     */
    public const EAGER = 2;

}

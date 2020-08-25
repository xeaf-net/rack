<?php declare(strict_types = 1);

/**
 * ResolveTypes.php
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
 * Содержит константы типов разрешений
 *
 * @package XEAF\Rack\ORM\Utils\Lex
 */
class ResolveTypes {

    /**
     * Отложенное разрешение
     */
    public const LAZY = 1;

    /**
     * Разрешение при загрузке
     */
    public const EAGER = 2;
}

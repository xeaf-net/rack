<?php declare(strict_types = 1);

/**
 * ManyToOneProperty.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\RelationTypes;

/**
 * Реализует методы свойства отношения Многие к одному
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class ManyToOneProperty extends RelationModel {

    /**
     * Конструктор класса
     *
     * @param string $entity Имя сущности
     * @param array  $links  Свойства связи
     */
    public function __construct(string $entity, array $links) {
        parent::__construct(RelationTypes::MANY_TO_ONE, $entity, $links);
    }
}

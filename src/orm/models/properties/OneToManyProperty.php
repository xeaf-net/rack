<?php declare(strict_types = 1);

/**
 * OneToManyProperty.php
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
 * Реализует методы свойства отношения Один ко многим
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class OneToManyProperty extends RelationModel {

    /**
     * Конструктор класса
     *
     * @param string $entity Имя сущности
     * @param array  $links  Свойства связи
     */
    public function __construct(string $entity, array $links) {
        parent::__construct(RelationTypes::ONE_TO_MANY, $entity, $links);
    }
}

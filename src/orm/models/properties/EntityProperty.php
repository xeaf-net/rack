<?php declare(strict_types = 1);

/**
 * EntityProperty.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\AccessTypes;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы свойства типа Сущность
 *
 * @package  XEAF\Rack\ORM\Models\Properties
 */
class EntityProperty extends PropertyModel {

    /**
     * Имя сущности
     * @var string
     */
    private $_entity;

    /**
     * Массив свойств ссылок
     * @var array
     */
    private $_links;

    /**
     * Признак отложенной загрузки
     * @var bool
     */
    private $_lazy;

    /**
     * Конструктор класса
     *
     * @param string $entity Имя сущности
     * @param array  $links  Сассив свойств ссылок
     * @param bool   $lazy   Признак отложенной загрузки
     */
    public function __construct(string $entity, array $links, bool $lazy = true) {
        parent::__construct(DataTypes::DT_ENTITY, 0, 0, '', false, AccessTypes::AC_CALCULATED);
        $this->_entity = $entity;
        $this->_links  = $links;
        $this->_lazy   = $lazy;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return null;
    }
}

<?php declare(strict_types = 1);

/**
 * ForeignKeyProperty.php
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
 * Реализует методы свойства типа Внешний ключ
 *
 * @package  XEAF\Rack\ORM\Models\Properties
 */
class ForeignKeyProperty extends PropertyModel {

    /**
     * Имя сущности
     * @var string
     */
    private $_entity;

    /**
     * Массив свойств внешнего ключа
     * @var array
     */
    private $_keys;

    /**
     * Конструктор класса
     *
     * @param string $entityName Имя сущности
     * @param array  $keys       Массив свойств внешнего ключа
     */
    public function __construct(string $entityName, array $keys) {
        parent::__construct(DataTypes::DT_FOREIGN_KEY, 0, 0, '', false, AccessTypes::AC_EXPANDABLE);
        $this->_entity = $entityName;
        $this->_keys   = $keys;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return null;
    }
}

<?php

/**
 * OrderModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;

/**
 * Описывает свойства модели данных параметров сортировки
 *
 * @property-read string $alias     Псевдоним
 * @property-read string $property  Имя свойства
 * @property-read int    $direction Направление сортировки
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 */
class OrderModel extends DataModel {

    /**
     * Псевдоним
     * @var string
     */
    protected $_alias = '';

    /**
     * Имя свойства
     * @var string
     */
    protected $_property = '';

    /**
     * Направление сортировки
     * @var int
     */
    protected $_direction = TokenTypes::KW_ASCENDING;

    /**
     * Конструктор класса
     *
     * @param string $alias     Псевдоним
     * @param string $property  Имя свойства
     * @param int    $direction Направление сортировки
     */
    public function __construct(string $alias, string $property, int $direction) {
        parent::__construct();
        $this->_alias     = $alias;
        $this->_property  = $property;
        $this->_direction = $direction;
    }

    /**
     * Возвращает псевдоним
     *
     * @return string
     */
    public function getAlias(): string {
        return $this->_alias;
    }

    /**
     * Возвращает имя свойства
     *
     * @return string
     */
    public function getProperty(): string {
        return $this->_property;
    }

    /**
     * Возвращает направление сортировки
     *
     * @return int
     */
    public function getDirection(): int {
        return $this->_direction;
    }
}

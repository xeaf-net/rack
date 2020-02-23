<?php

/**
 * FilterModel.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\DataModel;

/**
 * Описывает свойства модели данных параметров фильтрации
 *
 * @property-read string $alias     Псевдоним
 * @property-read string $property  Имя свойства
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 *
 * @since 1.0.2
 */
class FilterModel extends DataModel {

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
     * Конструктор класса
     *
     * @param string $alias     Псевдоним
     * @param string $property  Имя свойства
     */
    public function __construct(string $alias, string $property) {
        parent::__construct();
        $this->_alias     = $alias;
        $this->_property  = $property;
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
}


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
 * @property-read string $parameter Имя параметра фильтрации
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 *
 * @since   1.0.2
 */
class FilterModel extends DataModel {

    /**
     * Имя параметра фильтрации по умолчанию
     */
    public const FILTER_PARAMETER = '_filter';

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
     * Имя параметра фильтрации
     * @var string
     */
    protected $_parameter = '';

    /**
     * Конструктор класса
     *
     * @param string $alias     Псевдоним
     * @param string $property  Имя свойства
     * @param string $parameter Имя параметра фильтрации
     */
    public function __construct(string $alias, string $property, string $parameter = self::FILTER_PARAMETER) {
        parent::__construct();
        $this->_alias     = $alias;
        $this->_property  = $property;
        $this->_parameter = $parameter;
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
     * Возвращает имя параметра фильтрации
     *
     * @return string
     */
    public function getParameter(): string {
        return $this->_parameter;
    }
}


<?php

/**
 * EnumProperty.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы свойства перечислительного типа
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class EnumProperty extends PropertyModel {

    /**
     * Массив значений допустимых свойств
     * @var array
     */
    private $_enums = [];

    /**
     * Конструктор класса
     *
     * @param string $fieldName Имя поля базы данных
     * @param array  $enums     Массив значений допустимых свойств
     * @param bool   $readOnly  Признак только для чтения
     *
     */
    public function __construct(string $fieldName, array $enums, bool $readOnly = false) {
        parent::__construct(DataTypes::DT_ENUM, 0, 0, $fieldName, false, $readOnly);
        $this->_enums = $enums;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        $arr = $this->enumValues();
        $key = array_key_first($arr);
        return $arr[$key];
    }

    /**
     * Возвращает массив допустимых значений свойств
     *
     * @return array
     */
    public function enumValues(): array {
        return $this->_enums;
    }
}

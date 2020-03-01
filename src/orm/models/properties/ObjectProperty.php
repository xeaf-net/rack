<?php

/**
 * ObjectProperty.php
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
 * Реализует методы свойства типа объект
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class ObjectProperty extends PropertyModel {

    /**
     * Конструктор класса
     *
     * @param string $fieldName Имя поля БД
     * @param bool   $readOnly  Признак поля только для чтения
     */
    public function __construct(string $fieldName = '', bool $readOnly = false) {
        parent::__construct(DataTypes::DT_OBJECT, 0, 0, $fieldName, false, $readOnly);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return null;
    }
}

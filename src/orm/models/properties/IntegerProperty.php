<?php

/**
 * IntegerProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы свойства целочисленного типа
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class IntegerProperty extends PropertyModel {

    /**
     * Конструктор класса
     *
     * @param string $fieldName     Имя поля БД
     * @param bool   $primaryKey    Признак первичного ключа
     * @param bool   $readOnly      Признак поля только для чтения
     * @param bool   $autoIncrement Признак поля с автоинкрементом
     *     */
    public function __construct(string $fieldName, bool $primaryKey = false, bool $readOnly = false, bool $autoIncrement = false) {
        parent::__construct(DataTypes::DT_INTEGER, 0, 0, $fieldName, $primaryKey, $readOnly, $autoIncrement);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return 0;
    }
}

<?php

/**
 * NumericProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы свойства действительного числового типа
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class NumericProperty extends PropertyModel {

    /**
     * Конструктор класса
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $size       Размер
     * @param int    $precision  Точность
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     */
    public function __construct(string $fieldName, int $size, int $precision, bool $primaryKey = false, bool $readOnly = false) {
        parent::__construct(DataTypes::DT_NUMERIC, $size, $precision, $fieldName, $primaryKey, $readOnly);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return 0.0;
    }
}

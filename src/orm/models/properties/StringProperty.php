<?php

/**
 * StringProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\API\Utils\Strings;
use XEAF\Rack\ORM\Utils\AccessTypes;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы свойства строкового типа
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class StringProperty extends PropertyModel {

    /**
     * Конструктор класса
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $length     Длина
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     */
    public function __construct(string $fieldName, int $length, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT) {
        parent::__construct(DataTypes::DT_STRING, $length, 0, $fieldName, $primaryKey, $accessType);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return Strings::EMPTY;
    }
}

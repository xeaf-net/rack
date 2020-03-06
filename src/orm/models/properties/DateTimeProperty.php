<?php declare(strict_types = 1);

/**
 * DateProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\API\Utils\Calendar;
use XEAF\Rack\ORM\Utils\Lex\AccessTypes;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы свойства календарных даты и времени
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class DateTimeProperty extends PropertyModel {

    /**
     * Конструктор класса
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     */
    public function __construct($fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT) {
        parent::__construct(DataTypes::DT_DATETIME, 0, 0, $fieldName, $primaryKey, $accessType);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        $calendar = Calendar::getInstance();
        return $calendar->now();
    }
}


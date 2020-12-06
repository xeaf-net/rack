<?php declare(strict_types = 1);

/**
 * BoolProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
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
 * Реализует методы свойства логического типа
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class BoolProperty extends PropertyModel {

    /**
     * Конструктор класса
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     */
    public function __construct(string $fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT) {
        parent::__construct(DataTypes::DT_BOOL, 0, 0, $fieldName, $primaryKey, $accessType);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue(): bool {
        return false;
    }
}


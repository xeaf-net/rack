<?php

/**
 * UUIDProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\API\Utils\Crypto;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализет методы свойства типа UUID
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class UUIDProperty extends PropertyModel {

    /**
     * Конструктор класса
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     */
    public function __construct(string $fieldName, bool $primaryKey = false, bool $readOnly = false) {
        parent::__construct(DataTypes::DT_UUID, 0, 0, $fieldName, $primaryKey, $readOnly);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        $crypto = Crypto::getInstance();
        return $crypto->generateUUIDv4();
    }
}

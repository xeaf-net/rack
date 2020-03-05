<?php declare(strict_types = 1);

/**
 * UUIDProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\API\Utils\Crypto;
use XEAF\Rack\ORM\Utils\Lex\AccessTypes;
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
     * @param int    $accessType Определение доступа
     */
    public function __construct(string $fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT) {
        parent::__construct(DataTypes::DT_UUID, 0, 0, $fieldName, $primaryKey, $accessType);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        $crypto = Crypto::getInstance();
        return $crypto->generateUUIDv4();
    }
}

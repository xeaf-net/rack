<?php declare(strict_types = 1);

/**
 * TextProperty.php
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

/**
 * Реализует методы свойства текстового типа
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class TextProperty extends StringProperty {

    /**
     * Конструктор класса
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $accessType Определение доступа
     */
    public function __construct(string $fieldName, int $accessType = AccessTypes::AC_DEFAULT) {
        parent::__construct($fieldName, 0, false, $accessType);
    }
}

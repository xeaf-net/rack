<?php

/**
 * TextProperty.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

/**
 * Реализует методы свойства текстового типа
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class TextProperty extends StringProperty {

    /**
     * Конструктор класса
     *
     * @param string $fieldName Имя поля БД
     * @param bool   $readOnly  Признак поля только для чтения
     */
    public function __construct(string $fieldName, bool $readOnly = false) {
        parent::__construct($fieldName, 0, false, $readOnly);
    }
}

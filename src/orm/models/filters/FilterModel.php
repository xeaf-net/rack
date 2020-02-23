<?php

/**
 * FilterModel.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Filters;

use XEAF\Rack\API\Core\DataModel;

/**
 * Содержит бызовые свойства условия фильтрации
 *
 * @package XEAF\Rack\ORM\Models\Filters
 */
class FilterModel extends DataModel {

    /**
     * Конструктор класса
     *
     * @param array $properties Список фильтруемых свойств
     * @param mixed $value      Значение фильтра
     */
    protected function __construct(array $properties = [], $value = null) {
        parent::__construct();
    }

}

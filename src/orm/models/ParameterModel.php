<?php

/**
 * ParameterModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Описывает свойства модели данных параметра запроса
 *
 * @property int   $type  Тип Параметра
 * @property mixed $value Значение параметра
 *
 * @package XEAF\Rack\ORM\Models
 */
class ParameterModel extends DataModel {

    /**
     * Тип данных
     * @var int
     */
    protected $_type = DataTypes::DT_STRING;

    /**
     * Значение
     * @var mixed
     */
    protected $_value = null;

    /**
     * Конструктор класса
     *
     * @param int    $type  Тип параметра
     * @param string $value Значение
     */
    public function __construct(int $type, string $value = null) {
        parent::__construct();
        $this->_type  = $type;
        $this->_value = $value;
    }

    /**
     * Возвращает тип параметра
     *
     * @return int
     */
    public function getType(): int {
        return $this->_type;
    }

    /**
     * Задает тип параметра
     *
     * @param int $type Тип параметра
     *
     * @return void
     */
    public function setType(int $type): void {
        $this->_type = $type;
    }

    /**
     * Возвращает значение параметра
     *
     * @return mixed
     */
    public function getValue() {
        return $this->_value;
    }

    /**
     * Задает значение параметра
     *
     * @param mixed $value Значение параметра
     *
     * @return void
     */
    public function setValue($value): void {
        $this->_value = $value;
    }
}

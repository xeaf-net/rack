<?php declare(strict_types = 1);

/**
 * RelationValue.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Models\Parsers\WithModel;

/**
 * Содержит свойства значения свойства отношения
 *
 * @property-read \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel Модель конструкции WITH
 * @property-read bool                                    $resolved  Признак установки реального значения
 * @property      mixed|null                              $value     Реальное значение
 *
 * @package XEAF\Rack\ORM\Models
 */
class RelationValue extends DataModel {

    /**
     * Модель конструкции WITH
     * @var \XEAF\Rack\ORM\Models\Parsers\WithModel
     */
    private WithModel $_withModel;

    /**
     * Признак установки реального значения
     * @var bool
     */
    private bool $_resolved = false;

    /**
     * Реальное значение
     * @var mixed|null
     */
    private $_value = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel Модель конструкции WITH
     */
    public function __construct(WithModel $withModel) {
        parent::__construct();
        $this->_withModel = $withModel;
    }

    /**
     * Возвращает модель конструкции WITH
     *
     * @return \XEAF\Rack\ORM\Models\Parsers\WithModel
     */
    public function getWithModel(): WithModel {
        return $this->_withModel;
    }

    /**
     * Возвращает прихнак установки реального значения
     * @return bool
     */
    public function getIsResolved(): bool {
        return $this->_resolved;
    }

    /**
     * Возвращает реальное значение
     *
     * @return mixed|null
     */
    public function getValue() {
        return $this->_value;
    }

    /**
     * Задает реальное значение
     *
     * @param mixed|null $value Реальное значение
     *
     * @return void
     */
    public function setValue($value): void {
        $this->_value    = $value;
        $this->_resolved = true;
    }

}

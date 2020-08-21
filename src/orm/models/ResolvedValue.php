<?php declare(strict_types = 1);

/**
 * ResolvedValue.php
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
use XEAF\Rack\ORM\Models\Parsers\ResolveModel;
use XEAF\Rack\ORM\Utils\Lex\ResolveType;

/**
 * Содержит информацию о значении разрешенного совйства
 *
 * @property int                                        $resolveType Тип разрешения
 * @property \XEAF\Rack\ORM\Models\Parsers\ResolveModel $model       Модель разрешения
 * @property mixed|null                                 $value       Значение
 *
 * @package  XEAF\Rack\ORM\Models
 */
class ResolvedValue extends DataModel {

    /**
     * Тип разрешения
     * @var int
     */
    private $_resolveType;

    /**
     * Модель разрешения
     * @var \XEAF\Rack\ORM\Models\Parsers\ResolveModel|null
     */
    private $_model;

    /**
     * Текущее значение
     * @var mixed|null
     */
    private $_value = null;

    /**
     * Конструктор класса
     *
     * @param int                                             $resolveType Тип разрешения
     * @param \XEAF\Rack\ORM\Models\Parsers\ResolveModel|null $model       Модель разрешения
     */
    public function __construct(int $resolveType = ResolveType::NONE, ResolveModel $model = null) {
        parent::__construct();
        $this->_resolveType = $resolveType;
        $this->_model       = $model;
    }

    /**
     * Возвращает тип разрешения
     *
     * @return int
     */
    public function getResolveType(): int {
        return $this->_resolveType;
    }

    /**
     * Задает тип разрешения
     *
     * @param int $resolveType Тип разрешения
     *
     * @return void
     */
    public function setResolveType(int $resolveType): void {
        $this->_resolveType = $resolveType;
    }

    /**
     * Возвращает модель разрешения
     * @return \XEAF\Rack\ORM\Models\Parsers\ResolveModel
     */
    public function getModel(): ?ResolveModel {
        return $this->_model;
    }

    /**
     * Задает модель разрешения
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\ResolveModel|null $model Модель разрешения
     *
     * @return void
     */
    public function setModel(?ResolveModel $model): void {
        $this->_model = $model;
    }

    /**
     * Возвращает текущее значение
     *
     * @return mixed|null
     */
    public function getValue() {
        return $this->_value;
    }

    /**
     * Задает текущее значение
     *
     * @param mixed|null $value Текущее значение
     *
     * @return void
     */
    public function setValue($value): void {
        $this->_value = $value;
    }
}

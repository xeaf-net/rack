<?php declare(strict_types = 1);

/**
 * WithModel.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Models\Properties\RelationModel;
use XEAF\Rack\ORM\Utils\Lex\ResolveTypes;

/**
 * Описывает свойства модели данных разрешаемых связей
 *
 * @property-read string                                    $alias       Псевдоним
 * @property-read string                                    $property    Свойство
 * @property-read int                                       $resolveType Тип разрешения
 * @property-read string                                    $fullAlias   Полное значение псевдонима
 *
 * @property \XEAF\Rack\ORM\Models\Properties\RelationModel $relation    Модель отношения
 * @property \XEAF\Rack\ORM\Core\EntityQuery                $query       Запрос для выбора данных
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 */
class WithModel extends DataModel {

    /**
     * Псевдоним
     * @var string
     */
    private $_alias;

    /**
     * Свойство
     * @var string
     */
    private $_property;

    /**
     * Тип разрешения
     * @var int
     */
    private $_resolveType;

    /**
     * Модель отношения
     * @var \XEAF\Rack\ORM\Models\Properties\RelationModel|null
     */
    private $_relation = null;

    /**
     * Запрос для выбора данных
     * @var \XEAF\Rack\ORM\Core\EntityQuery|null
     */
    private $_query = null;

    /**
     * Конструктор класса
     *
     * @param string $alias       Псевдоним
     * @param string $property    Свойство
     * @param int    $resolveType Тип разрешения
     */
    public function __construct(string $alias, string $property, int $resolveType = ResolveTypes::LAZY) {
        parent::__construct();
        $this->_alias       = $alias;
        $this->_property    = $property;
        $this->_resolveType = $resolveType;
    }

    /**
     * Возвращает псевдоним
     *
     * @return string
     */
    public function getAlias(): string {
        return $this->_alias;
    }

    /**
     * Возвращает свойство
     *
     * @return string
     */
    public function getProperty(): string {
        return $this->_property;
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
     * Возвращет модель отношения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\RelationModel|null
     */
    public function getRelation(): ?RelationModel {
        return $this->_relation;
    }

    /**
     * Задает модель отношения
     *
     * @param \XEAF\Rack\ORM\Models\Properties\RelationModel|null $relation Модель отношения
     *
     * @return void
     */
    public function setRelation(?RelationModel $relation): void {
        $this->_relation = $relation;
    }

    /**
     * Возвращает запрос для выбора данных
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery|null
     */
    public function getQuery(): ?EntityQuery {
        return $this->_query;
    }

    /**
     * Задает запрос для выбора данных
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery|null $query Запрос для выбора данных
     *
     * @return void
     */
    public function setQuery(?EntityQuery $query): void {
        $this->_query = $query;
    }
}

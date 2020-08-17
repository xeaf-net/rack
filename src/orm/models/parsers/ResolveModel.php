<?php declare(strict_types = 1);

/**
 * ResolveModel.php
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
use XEAF\Rack\ORM\Utils\Lex\RelationType;

/**
 * Описывает совйства модели данных параметров разрешения ссылок
 *
 * @property-read string                               $alias        Псевдоним
 * @property-read string                               $property     Свойство
 * @property-read int                                  $resolveType  Тип разрешения
 * @property-read \XEAF\Rack\ORM\Core\EntityQuery|null $query        Запрос для выбора данных
 * @property-read bool                                 $processed    Признак завершения обработки
 * @property      int                                  $relationType Тип отношения
 *
 * @package  XEAF\Rack\ORM\Models\Parsers
 */
class ResolveModel extends DataModel {

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
     * Тип отношения
     * @var int
     */
    private $_relationType = RelationType::UNKNOWN;

    /**
     * Запрос для выбора данных
     * @var \XEAF\Rack\ORM\Core\EntityQuery|null
     */
    private $_query;

    /**
     * Конструктор класса
     *
     * @param string                               $alias       Псевдоним
     * @param string                               $property    Свойство
     * @param int                                  $resolveType Тип разрешения
     * @param \XEAF\Rack\ORM\Core\EntityQuery|null $subquery    Запрос для выбора данных
     */
    public function __construct(string $alias, string $property, int $resolveType, EntityQuery $subquery = null) {
        parent::__construct();
        $this->_alias       = $alias;
        $this->_property    = $property;
        $this->_resolveType = $resolveType;
        $this->_query       = $subquery;
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
     * Возвращает тип отношения
     *
     * @return int
     */
    public function getRelationType(): int {
        return $this->_relationType;
    }

    /**
     * Задает тип отношения
     *
     * @param int $relationType Тип отношения
     *
     * @return void
     */
    public function setRelationType(int $relationType): void {
        $this->_relationType = $relationType;
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
     * Возвращает признак завершения обработки запроса
     *
     * @return bool
     */
    public function getProcessed(): bool {
        return $this->_relationType != RelationType::UNKNOWN;
    }
}

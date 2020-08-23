<?php declare(strict_types = 1);

/**
 * QueryModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models;

use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\ORM\Models\Parsers\AliasModel;
use XEAF\Rack\ORM\Models\Parsers\FilterModel;
use XEAF\Rack\ORM\Models\Parsers\FromModel;
use XEAF\Rack\ORM\Models\Parsers\JoinModel;
use XEAF\Rack\ORM\Models\Parsers\OrderModel;
use XEAF\Rack\ORM\Models\Parsers\WhereModel;
use XEAF\Rack\ORM\Models\Parsers\WithModel;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;
use XEAF\Rack\ORM\Utils\Lex\RelationTypes;
use XEAF\Rack\ORM\Utils\Lex\ResolveTypes;

/**
 * Модель данных разобранного запроса
 *
 * @property-read \XEAF\Rack\API\Interfaces\ICollection $aliasModels  Модели данных конструкции ALIAS
 * @property-read \XEAF\Rack\API\Interfaces\ICollection $fromModels   Модели данных конструкции FROM
 * @property-read \XEAF\Rack\API\Interfaces\ICollection $joinModels   Модели данных конструкции JOIN
 * @property-read \XEAF\Rack\API\Interfaces\ICollection $whereModels  Модели данных конструкции WHERE
 * @property-read \XEAF\Rack\API\Interfaces\ICollection $orderModels  Модели данных конструкции ORDER
 * @property-read \XEAF\Rack\API\Interfaces\IKeyValue   $parameters   Набор параметров запроса
 * @property-read bool                                  isMultiEntity Признак выбора множества сущностей
 *
 * @package XEAF\Rack\ORM\Models
 */
class QueryModel extends DataModel {

    /**
     * Псевдонимы
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_aliasModels = null;

    /**
     * Сущности конструкции FROM
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_fromModels = null;

    /**
     * Сущности конструкции JOIN
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_joinModels = null;

    /**
     * Сущности конструкции WHERE
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_whereModels = null;

    /**
     * Сущности конструкции FILTER
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_filterModels = null;

    /**
     * Сущности конструкции ORDER
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_orderModels = null;

    /**
     * Модели разрешения связей
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_withModels = null;

    /**
     * Определения параметров
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    protected $_parameters = null;

    /**
     * Конструктор класса
     */
    public function __construct() {
        parent::__construct();
        $this->_aliasModels  = new Collection();
        $this->_fromModels   = new Collection();
        $this->_joinModels   = new Collection();
        $this->_whereModels  = new Collection();
        $this->_filterModels = new Collection();
        $this->_orderModels  = new Collection();
        $this->_withModels   = new Collection();
        $this->_parameters   = new KeyValue();
    }

    /**
     * Возвращает коллекцию псевдонимов
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function getAliasModels(): ICollection {
        return $this->_aliasModels;
    }

    /**
     * Добавляет модель данных в коллекцию псевдонимов
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\AliasModel $aliasModel Модель данных псевдонима
     *
     * @return void
     */
    public function addAliasModel(AliasModel $aliasModel): void {
        $this->_aliasModels->push($aliasModel);
    }

    /**
     * Возвращает коллекцию моделей данных конструкции FROM
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function getFromModels(): ICollection {
        return $this->_fromModels;
    }

    /**
     * Добавляет в коллекцию модель данных конструкции FROM
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\FromModel $fromModel Модель конструкции FROM
     *
     * @return void
     */
    public function addFromModel(FromModel $fromModel): void {
        $this->_fromModels->push($fromModel);
    }

    /**
     * Возвращает коллекцию моделей данных конструкции JOIN
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function getJoinModels(): ICollection {
        return $this->_joinModels;
    }

    /**
     * Добавляет в коллекцию модель данных конструкции JOIN
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\JoinModel $joinModel Модель конструкции JOIN
     *
     * @return void
     */
    public function addJoinModel(JoinModel $joinModel): void {
        $this->_joinModels->push($joinModel);
    }

    /**
     * Возвращает коллекцию моделей данных конструкции WHERE
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function getWhereModels(): ICollection {
        return $this->_whereModels;
    }

    /**
     * Добавляет в коллекцию модель данных конструкции WHERE
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\WhereModel $whereModel Модель конструкции WHERE
     *
     * @return void
     */
    public function addWhereModel(WhereModel $whereModel): void {
        $this->_whereModels->push($whereModel);
    }

    /**
     * Возвращает коллекцию моделей данных конструкции FILTER
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     *
     * @since 1.0.2
     */
    public function getFilterModels(): ICollection {
        return $this->_filterModels;
    }

    /**
     * Добавляет в коллекцию модель данных конструкции FILTER
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\FilterModel $filterModel Модель конструкции WHERE
     *
     * @return void
     *
     * @since 1.0.2
     */
    public function addFilterModel(FilterModel $filterModel): void {
        $this->_whereModels->push($filterModel);
    }

    /**
     * Возвращает коллекцию моделей данных конструкции ORDER
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function getOrderModels(): ICollection {
        return $this->_orderModels;
    }

    /**
     * Добавляет в коллекцию модель данных конструкции ORDER
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\OrderModel $orderModel Модель конструкции ORDER
     *
     * @return void
     */
    public function addOrderModel(OrderModel $orderModel): void {
        $this->_orderModels->push($orderModel);
    }

    /**
     * Возвращает модели разрешения связей
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function getWithModels(): ICollection {
        return $this->_withModels;
    }

    /**
     * Возвращает определенный набор параметров
     *
     * @return \XEAF\Rack\API\Interfaces\IKeyValue
     */
    public function getParameters(): IKeyValue {
        return $this->_parameters;
    }

    /**
     * Определяет новый параметр
     *
     * @param string     $name     Имя параметра
     * @param int        $dataType Тип данных
     * @param mixed|null $value    Значение
     * @param bool       $filter   Признак параметра фильтрации
     *
     * @return void
     */
    public function addParameter(string $name, int $dataType = DataTypes::DT_STRING, $value = null, bool $filter = false): void {
        $parameter = new ParameterModel($dataType, $value, $filter);
        $this->_parameters->put($name, $parameter);
    }

    /**
     * Ищет модель FROM по псевдониму
     *
     * @param string $alias Псевдоним
     *
     * @return \XEAF\Rack\ORM\Models\Parsers\FromModel|null
     */
    public function findFromModelByAlias(string $alias): ?FromModel {
        $result = $this->_fromModels->find(function ($item) use ($alias) {
            assert($item instanceof FromModel);
            return $item->getAlias() == $alias;
        });
        if ($result) {
            assert($result instanceof FromModel);
        }
        return $result;
    }

    /**
     * Ищет модель JOIN по псевдониму
     *
     * @param string $alias Псевдоним
     *
     * @return \XEAF\Rack\ORM\Models\Parsers\JoinModel|null
     */
    public function findJoinModelByAlias(string $alias): ?JoinModel {
        $result = $this->_joinModels->find(function ($item) use ($alias) {
            assert($item instanceof JoinModel);
            return $item->getJoinAlias() == $alias;
        });
        if ($result) {
            assert($result instanceof JoinModel);
        }
        return $result;
    }

    /**
     * Возвращает имя сущности по псевдониму
     *
     * @param string $alias Псевдоним
     *
     * @return string|null
     */
    public function findEntityByAlias(string $alias): ?string {
        $result = null;
        $model  = $this->findFromModelByAlias($alias);
        if ($model) {
            $result = $model->getEntity();
        } else {
            $model = $this->findJoinModelByAlias($alias);
            if ($model) {
                $result = $model->getJoinAlias();
            }
        }
        return $result;
    }

    /**
     * Возвращает признак выбора множества сущностей
     *
     * @return bool
     */
    public function getIsMultiEntity(): bool {
        $aliasCount  = $this->_aliasModels->count();
        $entityCount = 0;
        foreach ($this->_withModels as $withModel) {
            assert($withModel instanceof WithModel);
            if ($withModel->getResolveType() == ResolveTypes::EAGER) {
                $relation = $withModel->getRelation();
                if ($relation != null && $relation->getType() == RelationTypes::MANY_TO_ONE) {
                    $entityCount = $entityCount + 1;
                }
            }
        }
        return ($aliasCount - $entityCount) > 1;
    }
}

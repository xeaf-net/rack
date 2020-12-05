<?php declare(strict_types = 1);

/**
 * EntityQuery.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Core;

use Throwable;
use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\API\Utils\Exceptions\CollectionException;
use XEAF\Rack\API\Utils\Exceptions\SerializerException;
use XEAF\Rack\API\Utils\Serializer;
use XEAF\Rack\ORM\Interfaces\IGenerator;
use XEAF\Rack\ORM\Interfaces\IResolver;
use XEAF\Rack\ORM\Models\ParameterModel;
use XEAF\Rack\ORM\Models\Parsers\AliasModel;
use XEAF\Rack\ORM\Models\Parsers\FilterModel;
use XEAF\Rack\ORM\Models\Parsers\FromModel;
use XEAF\Rack\ORM\Models\Parsers\JoinModel;
use XEAF\Rack\ORM\Models\Parsers\OrderModel;
use XEAF\Rack\ORM\Models\Parsers\WithModel;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Models\RelationValue;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Generator;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;
use XEAF\Rack\ORM\Utils\Lex\RelationTypes;
use XEAF\Rack\ORM\Utils\Lex\ResolveTypes;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\Parsers\WhereParser;
use XEAF\Rack\ORM\Utils\QueryParser;
use XEAF\Rack\ORM\Utils\Resolver;
use XEAF\Rack\ORM\Utils\Tokenizer;

/**
 * Реализует методы создания модели XQL запроса
 *
 * @package XEAF\Rack\ORM\Core
 */
class EntityQuery extends DataModel {

    /**
     * Модель запроса
     * @var \XEAF\Rack\ORM\Models\QueryModel
     */
    private QueryModel $_model;

    /**
     * Менеджер сущностей
     * @var \XEAF\Rack\ORM\Core\EntityManager
     */
    private EntityManager $_em;

    /**
     * Объект методов генерации SQL команд
     * @var \XEAF\Rack\ORM\Interfaces\IGenerator
     */
    private IGenerator $_generator;

    /**
     * Объект методов разрешения связей
     * @var \XEAF\Rack\ORM\Interfaces\IResolver
     */
    private IResolver $_resolver;

    /**
     * Конструктор класса
     *
     * @param EntityManager $em  Менеджер сущностей
     * @param string        $xql Текст XQL запроса
     *
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function __construct(EntityManager $em, string $xql) {
        parent::__construct();
        $this->_em        = $em;
        $parser           = QueryParser::getInstance();
        $this->_model     = $parser->buildQueryModel($xql);
        $this->_generator = Generator::getInstance();
        $this->_resolver  = Resolver::getInstance();
    }

    /**
     * Возвращает объект модели запроса
     *
     * @return \XEAF\Rack\ORM\Models\QueryModel
     */
    public function getModel(): QueryModel {
        return $this->_model;
    }

    /**
     * Возвращает объект менеджера сущностей
     *
     * @return \XEAF\Rack\ORM\Core\EntityManager
     */
    public function getEntityManager(): EntityManager {
        return $this->_em;
    }

    /**
     * Добавляет псевдоним сущности для выбора
     *
     * @param string $alias Имя псевдонима
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function select(string $alias): EntityQuery {
        $aliasModel = new AliasModel($alias);
        $this->_model->addAliasModel($aliasModel);
        return $this;
    }

    /**
     * Добавляет источник выбора сущностей
     *
     * @param string      $entity Имя сущности
     * @param string|null $alias  Псевдоним
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function from(string $entity, string $alias = null): EntityQuery {
        $realAlias = $alias ? $alias : $entity;
        $fromModel = new FromModel($entity, $realAlias);
        $this->_model->addFromModel($fromModel);
        return $this;
    }

    /**
     * Добавляет новое соединение LEFT JOIN
     *
     * @param string $joinEntity   Имя присоединяемой сущности
     * @param string $joinAlias    Псевдоним присоединяемой сущности
     * @param string $joinProperty Имя свойства присоединяемой сущности
     * @param string $linkAlias    Псевдоним сущности связи
     * @param string $linkProperty Имя свойства сущности связи
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function leftJoin(string $joinEntity, string $joinAlias, string $joinProperty, string $linkAlias, string $linkProperty): EntityQuery {
        $joinModel = new JoinModel(TokenTypes::KW_LEFT, $joinEntity, $joinAlias, $joinProperty, $linkAlias, $linkProperty);
        $this->_model->addJoinModel($joinModel);
        return $this;
    }

    /**
     * Добавляет новое соединение RIGHT JOIN
     *
     * @param string $joinEntity   Имя присоединяемой сущности
     * @param string $joinAlias    Псевдоним присоединяемой сущности
     * @param string $joinProperty Имя свойства присоединяемой сущности
     * @param string $linkAlias    Псевдоним сущности связи
     * @param string $linkProperty Имя свойства сущности связи
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function rightJoin(string $joinEntity, string $joinAlias, string $joinProperty, string $linkAlias, string $linkProperty): EntityQuery {
        $joinModel = new JoinModel(TokenTypes::KW_RIGHT, $joinEntity, $joinAlias, $joinProperty, $linkAlias, $linkProperty);
        $this->_model->addJoinModel($joinModel);
        return $this;
    }

    /**
     * Добавляет новое соединение INNER JOIN
     *
     * @param string $joinEntity   Имя присоединяемой сущности
     * @param string $joinAlias    Псевдоним присоединяемой сущности
     * @param string $joinProperty Имя свойства присоединяемой сущности
     * @param string $linkAlias    Псевдоним сущности связи
     * @param string $linkProperty Имя свойства сущности связи
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function innerJoin(string $joinEntity, string $joinAlias, string $joinProperty, string $linkAlias, string $linkProperty): EntityQuery {
        $joinModel = new JoinModel(TokenTypes::KW_INNER, $joinEntity, $joinAlias, $joinProperty, $linkAlias, $linkProperty);
        $this->_model->addJoinModel($joinModel);
        return $this;
    }

    /**
     * Добавляет новое соединение OUTER JOIN
     *
     * @param string $joinEntity   Имя присоединяемой сущности
     * @param string $joinAlias    Псевдоним присоединяемой сущности
     * @param string $joinProperty Имя свойства присоединяемой сущности
     * @param string $linkAlias    Псевдоним сущности связи
     * @param string $linkProperty Имя свойства сущности связи
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function outerJoin(string $joinEntity, string $joinAlias, string $joinProperty, string $linkAlias, string $linkProperty): EntityQuery {
        $joinModel = new JoinModel(TokenTypes::KW_OUTER, $joinEntity, $joinAlias, $joinProperty, $linkAlias, $linkProperty);
        $this->_model->addJoinModel($joinModel);
        return $this;
    }

    /**
     * Задает условие отбора
     *
     * @param string $where Текст XQL условия отбора
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function where(string $where): EntityQuery {
        $this->_model->getWhereModels()->clear();
        $this->andWhere($where);
        return $this;
    }

    /**
     * Добавляет условие отбора
     *
     * @param string $where Текст XQL условия отбора
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function andWhere(string $where): EntityQuery {
        $tokenizer   = Tokenizer::getInstance();
        $whereParser = new WhereParser($this->_model);
        $whereParser->parse($tokenizer->tokenize('where ' . $where));
        return $this;
    }

    /**
     * Задает условие фильтрации
     *
     * @param string $alias      Псевдоним
     * @param string $property   Имя свойства
     * @param string $parameter  Имя параметра
     * @param int    $filterType Тип фильтрации
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function filterBy(string $alias, string $property, string $parameter = FilterModel::FILTER_PARAMETER, $filterType = FilterModel::FILTER_LIKE): EntityQuery {
        $this->_model->getFilterModels()->clear();
        return $this->andFilterBy($alias, $property, $parameter, $filterType);
    }

    /**
     * Добавляет условие фильтрации
     *
     * @param string $alias      Псевдоним
     * @param string $property   Имя свойства
     * @param string $parameter  Имя параметра
     * @param int    $filterType Тип фильтрации
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function andFilterBy(string $alias, string $property, string $parameter = FilterModel::FILTER_PARAMETER, $filterType = FilterModel::FILTER_LIKE): EntityQuery {
        $filterModel = new FilterModel($alias, $property, $parameter, $filterType);
        $this->_model->getFilterModels()->push($filterModel);
        return $this;
    }

    /**
     * Задает условие сортировки сущностей
     *
     * @param string $alias    Псевдоним сущности
     * @param string $property Имя свойства сущности
     * @param bool   $reverse  Признак обратной сортировки
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function orderBy(string $alias, string $property, bool $reverse = false): EntityQuery {
        $this->_model->getOrderModels()->clear();
        $this->andOrderBy($alias, $property, $reverse);
        return $this;
    }

    /**
     * Добавляет условие сортировки сущностей
     *
     * @param string $alias    Псевдоним сущности
     * @param string $property Имя свойства сущности
     * @param bool   $reverse  Признак обратной сортировки
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function andOrderBy(string $alias, string $property, bool $reverse = false): EntityQuery {
        $direction  = $reverse ? TokenTypes::KW_DESCENDING : TokenTypes::KW_ASCENDING;
        $orderModel = new OrderModel($alias, $property, $direction);
        $this->_model->addOrderModel($orderModel);
        return $this;
    }

    /**
     * Добавляет определение параметра
     *
     * @param string     $name     Имя параметра
     * @param mixed|null $value    Значение
     * @param int        $dataType Тип данных
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function parameter(string $name, $value, int $dataType = DataTypes::DT_STRING): EntityQuery {
        $this->_model->addParameter($name, $dataType, $value);
        return $this;
    }

    /**
     * Задает определение типа параметра
     *
     * @param string $name     Имя параметра
     * @param int    $dataType Тип параметра
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function parameterType(string $name, int $dataType): EntityQuery {
        $value = null;
        $param = $this->_model->getParameters()->get($name);
        if ($param != null) {
            assert($param instanceof ParameterModel);
            $value = $param->getValue();
        }
        $this->_model->addParameter($name, $dataType, $value);
        return $this;
    }

    /**
     * Добавляет тебование разрешения связей
     *
     * @param string $alias       Псевдоним
     * @param string $property    Свойство
     * @param int    $resolveType Тип разрешения
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function with(string $alias, string $property, int $resolveType): EntityQuery {
        $with = new WithModel($alias, $property, $resolveType);
        $this->_model->getWithModels()->push($with);
        return $this;
    }

    /**
     * Добавляет "ленивое" тебование разрешения связей
     *
     * @param string $alias    Псевдоним
     * @param string $property Свойство
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function withLazy(string $alias, string $property): EntityQuery {
        $with = new WithModel($alias, $property, ResolveTypes::LAZY);
        $this->_model->getWithModels()->push($with);
        return $this;
    }

    /**
     * Добавляет "нетерпеливое" тебование разрешения связей
     *
     * @param string $alias    Псевдоним
     * @param string $property Свойство
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function withEager(string $alias, string $property): EntityQuery {
        $with = new WithModel($alias, $property, ResolveTypes::EAGER);
        $this->_model->getWithModels()->push($with);
        return $this;
    }

    /**
     * Возвращает подзапрос для выбора связанных элементов
     *
     * @param string $alias    Псевоним
     * @param string $property Свойство
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function subquery(string $alias, string $property): EntityQuery {
        $withModel = $this->_model->findWithModel($alias, $property);
        if (!$withModel) {
            throw EntityException::unknownEntityProperty($alias, $property);
        }
        return $this->_resolver->withModelQuery($this->getEntityManager(), $withModel);
    }

    /**
     * Возвращает набор сущностей удовляетворяющих условию отбора
     *
     * @param array $params   Параметры запроса
     * @param int   $count    Количество отбираемых сущностей
     * @param int   $offset   Смещение от начала выбора
     * @param bool  $distinct Признак отбора уникальных значений
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function get(array $params = [], int $count = 0, int $offset = 0, bool $distinct = false): ICollection {
        return $this->internalGet([], $params, $count, $offset, $distinct);
    }

    /**
     * Возвращает набор сущностей удовляетворяющих условию отбора и фильтру
     *
     * @param array $filters  Параметры фильтрации
     * @param array $params   Параметры запроса
     * @param int   $count    Количество отбираемых сущностей
     * @param int   $offset   Смещение от начала выбора
     * @param bool  $distinct Признак отбора уникальных значений
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function getFiltered(array $filters, array $params = [], int $count = 0, int $offset = 0, bool $distinct = false): ICollection {
        return $this->internalGet($filters, $params, $count, $offset, $distinct);
    }

    /**
     * Внутренняя реализация метода получения данных
     *
     * @param array $filters  Условия фильтрации
     * @param array $params   Параметры запроса
     * @param int   $count    Количество отбираемых сущностей
     * @param int   $offset   Смещение от начала выбора
     * @param bool  $distinct Признак отбора уникальных значений
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function internalGet(array $filters, array $params, int $count, int $offset, bool $distinct): ICollection {
        try {
            $this->resolveWithModels($params);
            $sql    = $this->generateSQL(count($filters) > 0, $distinct);
            $prm    = $this->processParameters($filters, $params);
            $data   = $this->_em->getDb()->select($sql, $prm, $count, $offset);
            $result = $this->processRecords($data);
        } catch (Throwable $exception) {
            throw EntityException::internalError($exception);
        }
        return $result;
    }

    /**
     * Возвращает количество сущностей удовляетворяющих условию отбора
     *
     * @param array $params   Параметры запроса
     * @param bool  $distinct Признак отбора уникальных значений
     *
     * @return int
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function getCount(array $params = [], bool $distinct = false): int {
        return $this->internalGetCount([], $params, $distinct);
    }

    /**
     * Возвращает количество сущностей удовляетворяющих условиям отбора и фильтрации
     *
     * @param array $filters  Параметры фильтрации
     * @param array $params   Параметры запроса
     * @param bool  $distinct Признак отбора уникальных значений
     *
     * @return int
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function getFilteredCount(array $filters, array $params = [], bool $distinct = false): int {
        return $this->internalGetCount($filters, $params, $distinct);
    }

    /**
     * Внутренняя реализация методов получения количества записей
     *
     * @param array $filters  Параметры фильтрации
     * @param array $params   Параметры запроса
     * @param bool  $distinct Признак отбора уникальных значений
     *
     * @return int
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function internalGetCount(array $filters, array $params, bool $distinct): int {
        try {
            $sql    = $this->generateCountSQL(count($filters) > 0, $distinct);
            $prm    = $this->processParameters($filters, $params);
            $data   = $this->_em->getDb()->selectFirst($sql, $prm);
            $result = array_shift($data);
        } catch (Throwable $exception) {
            throw EntityException::internalError($exception);
        }
        return $result;
    }

    /**
     * Возвращает текст SQL запроса
     *
     * @param bool $useFilter Признак использования условий фильтрации
     * @param bool $distinct  Признак отбора уникальных значений
     *
     * @return string
     */
    protected function generateSQL(bool $useFilter, bool $distinct): string {
        return $this->_generator->selectSQL($this, $useFilter, $distinct);
    }

    /**
     * Возвращает текст SQL запроса для выбора количества записей
     *
     * @param bool $useFilter Признак использования условий фильтрации
     * @param bool $distinct  Признак отбора уникальных значений
     *
     * @return string
     */
    public function generateCountSQL(bool $useFilter, bool $distinct): string {
        return $this->_generator->selectCountSQL($this, $useFilter, $distinct);
    }

    /**
     * Разрешает связи модели WITH
     *
     * @param array $parameters Параметры вызова основного запроса
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveWithModels(array $parameters): void {
        $withModels = $this->_model->getWithModels();
        foreach ($withModels as $withModel) {
            assert($withModel instanceof WithModel);
            if ($withModel->getRelation() == null) {
                $this->_resolver->resolveWithModel($this, $withModel);
            }
            $withModel->setParameters($parameters);
        }
    }

    /**
     * Обрабатывает параметры запроса
     *
     * @param array $filters Условия фильтрации
     * @param array $params  Массив значений параметров
     *
     * @return array
     */
    protected function processParameters(array $filters, array $params): array {
        $result = $filters;
        $db     = $this->_em->getDb();
        $values = $filters + $params;
        foreach ($this->_model->getParameters() as $name => $parameter) {
            assert($parameter instanceof ParameterModel);
            $value = $values[$name] ?? $parameter->getValue();
            if ($value != null || $parameter->getType() == DataTypes::DT_BOOL) {
                switch ($parameter->getType()) {
                    case  DataTypes::DT_BOOL:
                        $value = $db->formatBool((bool)$value);
                        break;
                    case  DataTypes::DT_DATE:
                        $value = $db->formatDate($value);
                        break;
                    case  DataTypes::DT_DATETIME:
                        $value = $db->formatDateTime($value);
                        break;
                }
            }
            $result[$name] = $value;
        }
        return $result;
    }

    /**
     * Возвращает первый удовляетворяющий условиям отбора набор данных
     *
     * @param array $params Параметры запроса
     *
     * @return \XEAF\Rack\API\Core\DataObject|null
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function getFirst(array $params = []): ?DataObject {
        try {
            $result = null;
            $list   = $this->get($params, 1);
            if (!$list->isEmpty()) {
                $result = $list->item(0);
                assert($result instanceof DataObject);
            }
            return $result;
        } catch (CollectionException $exception) {
            throw EntityException::internalError($exception);
        }
    }

    /**
     * Возвращает первую удовляетворяющую условиям отбора сущность
     *
     * @param array $params Параметры запроса
     *
     * @return \XEAF\Rack\API\Core\DataObject|null
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function getFirstEntity(array $params = []): ?Entity {
        $result = $this->getFirst($params);
        if ($result) {
            assert($result instanceof Entity);
        }
        return $result;
    }

    /**
     * Инициализирует коллекции свойств псевдонимов для быстрой обработки
     *
     * @param \XEAF\Rack\API\Interfaces\IKeyValue $models  Хранилище моделей
     * @param \XEAF\Rack\API\Interfaces\IKeyValue $classes Хранилище классов сущностей
     *
     * @return void
     */
    protected function prepareAliases(IKeyValue $models, IKeyValue $classes): void {
        foreach ($this->_model->getAliasModels() as $alias) {
            assert($alias instanceof AliasModel);
            $name       = $alias->getName();
            $model      = $alias->getModel();
            $tableName  = $model->getTableName();
            $entityName = $this->_em->findByTableName($tableName);
            $className  = $this->_em->getEntityClass($entityName);
            $models->put($name, $model);
            $classes->put($name, $className);
        }
    }

    /**
     * Обрабатывает поля результата с множественными сущностями
     *
     * @param array $data Массив полей результата
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function processRecords(array $data): ICollection {
        $result          = new Collection();
        $aliasModels     = new KeyValue();
        $aliasClassNames = new KeyValue();
        $this->prepareAliases($aliasModels, $aliasClassNames);
        foreach ($data as $record) {
            $multi = [];
            foreach ($aliasModels as $aliasName => $aliasModel) {
                $className  = $aliasClassNames->get($aliasName);
                $properties = $aliasModel->getPropertyByNames();
                $item       = $this->processRecord($aliasName, $properties, $record);
                $entity     = new $className($item);
                assert($entity instanceof Entity);
                if ($entity->getPrimaryKey()) {
                    $this->_em->watch($entity);
                    $multi[$aliasName] = $entity;
                } else {
                    $multi[$aliasName] = null;
                }
            }
            $recordObject = $this->processRelationProperties($multi);
            $result->push($recordObject);
        }
        return $result;
    }

    /**
     * Обрабатывает данные массива записи
     *
     * @param string                              $aliasName  Псевдоним
     * @param \XEAF\Rack\API\Interfaces\IKeyValue $properties Набор свойств сущности
     * @param array                               $record     Массив данных записи
     *
     * @return array
     */
    protected function processRecord(string $aliasName, IKeyValue $properties, array $record): array {
        $result  = [];
        $lcAlias = strtolower($aliasName);
        foreach ($properties as $name => $property) {
            assert($property instanceof PropertyModel);
            if (!$property->getIsRelation()) {
                $fieldAlias    = $lcAlias . '_' . $property->getFieldName();
                $result[$name] = $this->processReadableProperty($property, $record[$fieldAlias]);
            }
        }
        return $result;
    }

    /**
     * Возвращает значения обычного читаемого свойства
     *
     * @param \XEAF\Rack\ORM\Models\Properties\PropertyModel $property Модель свойства
     * @param mixed                                          $value    Значение
     *
     * @return mixed
     */
    protected function processReadableProperty(PropertyModel $property, $value) {
        $result = null;
        if ($value !== null) {
            $db = $this->_em->getDb();
            switch ($property->getDataType()) {
                case DataTypes::DT_INTEGER:
                    $result = (int)$value;
                    break;
                case DataTypes::DT_NUMERIC:
                    $result = (float)$value;
                    break;
                case DataTypes::DT_BOOL:
                    $result = $db->sqlBool($value);
                    break;
                case DataTypes::DT_DATE:
                    $result = $db->sqlDate($value);
                    break;
                case DataTypes::DT_DATETIME:
                    $result = $db->sqlDateTime($value);
                    break;
                case DataTypes::DT_ARRAY:
                    try {
                        $result = Serializer::getInstance()->unserialize($value);
                        if (!is_array($result)) {
                            $result = [];
                        }
                    } catch (SerializerException $exception) {
                        $result = [];
                    }
                    break;
                case DataTypes::DT_OBJECT:
                    try {
                        $result = Serializer::getInstance()->unserialize($value);
                        if (!is_object($result)) {
                            $result = null;
                        }
                    } catch (SerializerException $exception) {
                        $result = null;
                    }
                    break;
                case DataTypes::DT_STRING:
                case DataTypes::DT_UUID:
                case DataTypes::DT_ENUM:
                    $result = $value;
                    break;
            }
        }
        return $result;
    }

    /**
     * Обрабатывает свойсва связей
     *
     * @param array $multi Массив объектов данных
     *
     * @return \XEAF\Rack\API\Core\DataObject
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function processRelationProperties(array $multi): DataObject {
        $result     = $multi;
        $withModels = $this->_model->getWithModels();
        $unsets     = [];
        foreach ($withModels as $withModel) {
            assert($withModel instanceof WithModel);
            $alias    = $withModel->getAlias();
            $property = $withModel->getProperty();
            $entity   = $result[$alias];
            assert($entity instanceof Entity);
            switch ($withModel->getResolveType()) {
                case ResolveTypes::LAZY:
                    $this->_resolver->resolveLazyValue($entity, $withModel);
                    break;
                case ResolveTypes::EAGER:
                    switch ($withModel->getRelation()->getType()) {
                        case RelationTypes::ONE_TO_MANY:
                        case RelationTypes::MANY_TO_MANY:
                            $this->_resolver->resolveEagerValue($entity, $withModel);
                            break;
                        case RelationTypes::MANY_TO_ONE:
                            $fullAlias = $withModel->getFullAlias();
                            $value     = new RelationValue($withModel);
                            $value->setValue($multi[$fullAlias]);
                            $entity->setRelationValue($property, $value);
                            $unsets[] = $fullAlias;
                            // unset($result[$fullAlias]);
                            break;
                    }
                    break;
            }
        }
        foreach ($unsets as $alias) {
            unset($result[$alias]);
        }
        $keys = array_keys($result);
        return count($result) > 1 ? new DataObject($result) : $result[$keys[0]];
    }

    /**
     * Обрабатывает параметры для подчиненных запросов
     *
     * @param \XEAF\Rack\API\Interfaces\IKeyValue     $parameters Набор параметров
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel  Модель связи WITH
     *
     * @return void
     */
    protected function processRelationParameters(IKeyValue $parameters, WithModel $withModel): void {
        $query = $withModel->getQuery();
        foreach ($parameters as $name => $parameter) {
            assert($parameter instanceof ParameterModel);
            $query->parameter($name, $parameter->getValue());
        }
    }
}

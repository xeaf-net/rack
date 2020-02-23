<?php

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
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\ParameterModel;
use XEAF\Rack\ORM\Models\Parsers\AliasModel;
use XEAF\Rack\ORM\Models\Parsers\FromModel;
use XEAF\Rack\ORM\Models\Parsers\JoinModel;
use XEAF\Rack\ORM\Models\Parsers\OrderModel;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Generator;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\Parsers\WhereParser;
use XEAF\Rack\ORM\Utils\QueryParser;
use XEAF\Rack\ORM\Utils\Tokenizer;

/**
 * Реализует методы создания модели XQL запроса
 *
 * @package XEAF\Rack\ORM\Core
 */
class EntityQuery extends DataModel {

    /**
     * Модель запроса
     * @var \XEAF\Rack\ORM\Models\QueryModel|null
     */
    private $_model = null;

    /**
     * Менеджер сущностей
     * @var \XEAF\Rack\ORM\Core\EntityManager
     */
    private $_em = null;

    /**
     * Конструктор класса
     *
     * @param EntityManager $em  Менеджер сущностей
     * @param string        $xql Текст XQL запроса
     *
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     */
    public function __construct(EntityManager $em, string $xql) {
        parent::__construct();
        $this->_em    = $em;
        $parser       = QueryParser::getInstance();
        $this->_model = $parser->buildQueryModel($xql);
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
     * @param string $entity Имя сущности
     * @param string $alias  Псевдоним
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function from(string $entity, string $alias): EntityQuery {
        $fromModel = new FromModel($entity, $alias);
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
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
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
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     */
    public function andWhere(string $where): EntityQuery {
        $tokenizer   = Tokenizer::getInstance();
        $whereParser = new WhereParser($this->_model);
        $whereParser->parse($tokenizer->tokenize('where ' . $where));
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
     * Возвращает набор сущностей удовляетворяющих условию отбора
     *
     * @param array $params Параметры запроса
     * @param int   $count  Количество отбираемых сущностей
     * @param int   $offset Смещение от начала выбора
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function get(array $params = [], int $count = 0, int $offset = 0): ICollection {
        try {
            $sql   = $this->generateSQL();
            $prm   = $this->processParameters($params);
            $data  = $this->_em->getDb()->select($sql, $prm, $count, $offset);
            $multi = $this->_model->getAliasModels()->count() > 1;
            if (!$multi) {
                $result = $this->processSingleRecords($data);
            } else {
                $result = $this->processMultipleRecords($data);
            }
        } catch (Throwable $exception) {
            throw EntityException::internalError($exception);
        }
        return $result;
    }

    /**
     * Возвращает количество сущностей удовляетворяющих условию отбора
     *
     * @param array $params Параметры запроса
     *
     * @return int
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function getCount(array $params = []): int {
        try {
            $sql    = $this->generateCountSQL();
            $prm    = $this->processParameters($params);
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
     * @return string
     */
    public function generateSQL(): string {
        $gen = Generator::getInstance();
        return $gen->selectSQL($this);
    }

    /**
     * Возвращает текст SQL запроса для выбора количества записей
     *
     * @return string
     */
    public function generateCountSQL(): string {
        $gen = Generator::getInstance();
        return $gen->selectCountSQL($this);
    }

    /**
     * Обрабатывает параметры запроса
     *
     * @param array $params Массив значений параметров
     *
     * @return array
     */
    protected function processParameters(array $params): array {
        $result = [];
        $db     = $this->_em->getDb();
        foreach ($this->_model->getParameters() as $name => $parameter) {
            assert($parameter instanceof ParameterModel);
            $value = $params[$name] ?? $parameter->getValue();
            if ($value != null) {
                switch ($parameter->getType()) {
                    case  DataTypes::DT_BOOL:
                        $value = $db->formatBool($value);
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
     * Возвращает первую удовляетворяющую условиям отбора сущность
     *
     * @param array $params Параметры запроса
     *
     * @return \XEAF\Rack\API\Core\DataObject|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function getFirst(array $params): ?DataObject {
        $result = null;
        $list   = $this->get($params, 1);
        if (!$list->isEmpty()) {
            $result = $list->item(0);
            assert($result instanceof DataObject);
        }
        return $result;
    }

    /**
     * Обрабатывает поля результата с единичной сущностью
     *
     * @param array $data Массив полей результата
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function processSingleRecords(array &$data): ICollection {
        $result = new Collection();
        $alias  = $this->_model->getAliasModels()->first();
        assert($alias instanceof AliasModel);
        $model      = $alias->getModel();
        $tableName  = $model->getTableName();
        $entityName = $this->_em->findByTableName($tableName);
        $className  = $this->_em->getEntityClass($entityName);
        $properties = $model->getPropertyByNames();
        foreach ($data as $record) {
            $item   = $this->processRecord($properties, $record);
            $entity = new $className($item);
            $this->_em->watch($entity);
            $result->push($entity);
        }
        return $result;
    }

    /**
     * Обрабатывает поля результата с множественными сущностями
     *
     * @param array $data Массив полей результата
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function processMultipleRecords(array &$data): ICollection {
        $result         = new Collection();
        $aliasModel     = new KeyValue();
        $aliasClassName = new KeyValue();
        foreach ($this->_model->getAliasModels() as $alias) {
            assert($alias instanceof AliasModel);
            $name       = $alias->getName();
            $model      = $alias->getModel();
            $tableName  = $model->getTableName();
            $entityName = $this->_em->findByTableName($tableName);
            $className  = $this->_em->getEntityClass($entityName);
            $aliasModel->put($name, $model);
            $aliasClassName->put($name, $className);
        }
        foreach ($data as $record) {
            $multi = [];
            foreach ($this->_model->getAliasModels() as $alias) {
                assert($alias instanceof AliasModel);
                $aliasName = $alias->getName();
                $model     = $aliasModel->get($aliasName);
                assert($model instanceof EntityModel);
                $className  = $aliasClassName->get($aliasName);
                $properties = $model->getPropertyByNames();
                $item       = $this->processRecord($properties, $record);
                $entity     = new $className($item);
                $this->_em->watch($entity);
                $multi[$aliasName] = $entity;
            }
            $recordObject = new DataObject($multi);
            $result->push($recordObject);
        }
        return $result;
    }

    /**
     * Обрабатывает данные массива записи
     *
     * @param \XEAF\Rack\API\Interfaces\IKeyValue $properties Набор свойств сущности
     * @param array                               $record     Массив данных записи
     *
     * @return array
     */
    protected function processRecord(IKeyValue $properties, array &$record): array {
        $result = [];
        $db     = $this->_em->getDb();
        foreach ($properties as $name => $property) {
            assert($property instanceof PropertyModel);
            $value = $record[$property->getFieldName()];
            switch ($property->getDataType()) {
                case DataTypes::DT_BOOL:
                    $result[$name] = $db->sqlBool($value);
                    break;
                case DataTypes::DT_DATE:
                    $result[$name] = $db->sqlDate($value);
                    break;
                case DataTypes::DT_DATETIME:
                    $result[$name] = $db->sqlDateTime($value);
                    break;
                default:
                    $result[$name] = $value;
            }
        }
        return $result;
    }
}

<?php declare(strict_types = 1);

/**
 * Generator.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\ORM\Core\Entity;
use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Interfaces\IGenerator;
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\ParameterModel;
use XEAF\Rack\ORM\Models\Parsers\AliasModel;
use XEAF\Rack\ORM\Models\Parsers\FilterModel;
use XEAF\Rack\ORM\Models\Parsers\FromModel;
use XEAF\Rack\ORM\Models\Parsers\JoinModel;
use XEAF\Rack\ORM\Models\Parsers\OrderModel;
use XEAF\Rack\ORM\Models\Parsers\WhereModel;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Models\TokenModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;

/**
 * Реализует методы генерации текстов SQL запросов и команд
 *
 * @package XEAF\Rack\ORM\Utils
 */
class Generator implements IGenerator {

    /**
     * Менеджер сущностей
     * @var \XEAF\Rack\ORM\Core\EntityManager
     */
    private $_em = null;

    /**
     * Хранилище разрешенных псевдонимов
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_aliases;

    /**
     * Хранилище моделей сущностей
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_entities = null;

    /**
     * @inheritDoc
     */
    public function __construct() {
        $this->_aliases = new KeyValue();
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function selectSQL(EntityQuery $query, bool $useFilter): string {
        $this->_aliases->clear();
        $this->_em       = $query->getEntityManager();
        $this->_entities = $this->_em->getEntities();
        $model           = $query->getModel();
        $condition       = $this->selectSQLConditions($query, $useFilter);
        $aliasSQL        = $this->generateAliasSQL($model->getAliasModels());
        return 'select ' . $aliasSQL . ' ' . $condition;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function selectCountSQL(EntityQuery $query, bool $useFilter): string {
        $this->_aliases->clear();
        $this->_em       = $query->getEntityManager();
        $this->_entities = $this->_em->getEntities();
        $condition       = $this->selectSQLConditions($query, $useFilter);
        return 'select count(*) as _count ' . $condition;
    }

    /**
     * Возвращает SQL код условий отбора записей
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery $query     Объект запроса
     * @param bool                            $useFilter Признак использования условий фильтрации
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function selectSQLConditions(EntityQuery $query, bool $useFilter): string {
        $model    = $query->getModel();
        $fromSQL  = $this->generateFromSQL($model->getFromModels());
        $joinSQL  = $this->generateJoinSQL($model->getJoinModels());
        $whereSQL = $this->generateWhereSQL($model->getWhereModels(), $model->getParameters());
        if ($useFilter) {
            $filterSQL = $this->generateFilterSQL($query);
            if ($filterSQL) {
                if ($whereSQL) {
                    $whereSQL .= " and ($filterSQL)";
                } else {
                    $whereSQL = "where ($filterSQL)";
                }
            }
        }
        $orderSQL = $this->generateOrderSQL($model->getOrderModels());
        return implode(' ', [$fromSQL, $joinSQL, $whereSQL, $orderSQL]);
    }

    /**
     * @inheritDoc
     * @noinspection RedundantSuppression
     */
    public function insertSQL(Entity $entity): string {
        $storage = EntityStorage::getInstance();
        $result  = $storage->getInsertSQL($entity->getClassName());
        if ($result == null) {
            $fields     = [];
            $params     = [];
            $model      = $entity->getModel();
            $tableName  = $model->getTableName();
            $properties = $model->getPropertyByNames();
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                if ($property->getIsInsertable()) {
                    $params[] = ':' . $name;
                    $fields[] = $property->getFieldName();
                }
            }
            /** @noinspection SqlNoDataSourceInspection */
            $result = 'insert into ' . $tableName . '(' . implode(',', $fields) . ') values (' . implode(',', $params) . ')';
            $storage->putInsertSQL($entity->getClassName(), $result);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function updateSQL(Entity $entity): string {
        $storage = EntityStorage::getInstance();
        $result  = $storage->getUpdateSQL($entity->getClassName());
        if ($result == null) {
            $lines      = [];
            $keys       = [];
            $model      = $entity->getModel();
            $tableName  = $model->getTableName();
            $properties = $model->getPropertyByNames();
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                $fieldName = $property->getFieldName();
                $paramName = ':' . $name;
                $line      = "$fieldName=$paramName";
                if ($property->getPrimaryKey()) {
                    $keys[] = $line;
                } elseif ($property->getIsUpdatable()) {
                    $lines[] = $line;
                }
            }
            $result = 'update ' . $tableName . ' set ' . implode(',', $lines) . ' where ' . implode(' and ', $keys);
            $storage->putUpdateSQL($entity->getClassName(), $result);
        }
        return $result;
    }

    /**
     * @inheritDoc
     * @noinspection RedundantSuppression
     */
    public function deleteSQL(Entity $entity): string {
        $storage = EntityStorage::getInstance();
        $result  = $storage->getDeleteSQL($entity->getClassName());
        if ($result == null) {
            $keys       = [];
            $model      = $entity->getModel();
            $tableName  = $model->getTableName();
            $properties = $model->getPropertyByNames();
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                $fieldName = $property->getFieldName();
                $paramName = ':' . $name;
                if ($property->getPrimaryKey()) {
                    $keys[] = "$fieldName=$paramName";
                }
            }
            /** @noinspection SqlNoDataSourceInspection */
            $result = 'delete from ' . $tableName . ' where ' . implode(' and ', $keys);
            $storage->putUpdateSQL($entity->getClassName(), $result);
        }
        return $result;
    }

    /**
     * Генерирует SQL для конструкции ALIAS
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $aliasModels Список моделей данных
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function generateAliasSQL(ICollection $aliasModels): string {
        $result = [];
        foreach ($aliasModels as $aliasModel) {
            assert($aliasModel instanceof AliasModel);
            $alias      = $aliasModel->getName();
            $model      = $this->entityModelByAlias($alias);
            $properties = $model->getPropertyByNames();
            $aliasModel->setModel($model);
            foreach ($properties as $name => $property) {
                assert($property instanceof PropertyModel);
                $fieldName = $property->getFieldName();
                $result[]  = "$alias.$fieldName";
            }
        }
        return implode(',', $result);
    }

    /**
     * Генерирует SQL для конструкции FROM
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $fromModels Список моделей данных
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function generateFromSQL(ICollection $fromModels): string {
        $result = [];
        foreach ($fromModels as $fromModel) {
            assert($fromModel instanceof FromModel);
            $entity = $fromModel->getEntity();
            $alias  = $fromModel->getAlias();
            if ($this->_entities->get($entity) == null) {
                throw EntityException::unknownEntity($entity);
            }
            $this->_aliases->put($alias, $entity);
            $tableName = $this->tableNameByAlias($alias);
            $result[]  = "$tableName $alias";
        }
        return 'from ' . implode(',', $result);
    }

    /**
     * Генерирует SQL для конструкции JOIN
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $joinModels Список моделей данных
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function generateJoinSQL(ICollection $joinModels): string {
        $result = [];
        foreach ($joinModels as $joinModel) {
            assert($joinModel instanceof JoinModel);
            $joinType     = $this->joinTypeName($joinModel->getType());
            $joinEntity   = $joinModel->getJoinEntity();
            $joinAlias    = $joinModel->getJoinAlias();
            $joinProperty = $joinModel->getJoinProperty();
            $linkAlias    = $joinModel->getLinkAlias();
            $linkProperty = $joinModel->getLinkProperty();
            if ($this->_aliases->get($joinAlias) != null) {
                throw EntityException::invalidJoinAlias($joinAlias);
            }
            if ($this->_aliases->get($linkAlias) == null) {
                throw EntityException::invalidJoinAlias($linkAlias);
            }
            $this->_aliases->put($joinAlias, $joinEntity);
            $joinTable = $this->tableNameByAlias($joinAlias);
            $joinField = $this->fieldNameByAlias($joinAlias, $joinProperty);
            $linkField = $this->fieldNameByAlias($linkAlias, $linkProperty);
            $result[]  = "$joinType join $joinTable $joinAlias on $joinAlias.$joinField = $linkAlias.$linkField";
        }
        return implode(' ', $result);
    }

    /**
     * Генерирует SQL для конструкции WHERE
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $whereModels Список моделей данных
     * @param \XEAF\Rack\API\Interfaces\IKeyValue   $parameters  Набор параметров
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function generateWhereSQL(ICollection $whereModels, IKeyValue $parameters): string {
        $result   = [];
        $alias    = '';
        $first    = true;
        $dataType = DataTypes::DT_STRING;
        foreach ($whereModels as $whereModel) {
            assert($whereModel instanceof WhereModel);
            if (!$first) {
                $result[] = ' and ';
            } else {
                $first = false;
            }
            $result[] = '(';
            $tokens   = $whereModel->getTokens();
            foreach ($tokens as $token) {
                assert($token instanceof TokenModel);
                switch ($token->getType()) {
                    case TokenTypes::ID_STOP:
                        break;
                    case TokenTypes::SP_DOT:
                        $result[count($result) - 1] .= '.';
                        break;
                    case TokenTypes::OP_NOT:
                        $result[] = 'not';
                        break;
                    case TokenTypes::OP_AND:
                        $result[] = 'and';
                        break;
                    case TokenTypes::OP_OR:
                        $result[] = 'or';
                        break;
                    case TokenTypes::OP_EQ:
                        $result[] = '=';
                        break;
                    case TokenTypes::OP_NE:
                        $result[] = '<>';
                        break;
                    case TokenTypes::OP_LIKE:
                        $result[] = 'like';
                        break;
                    case TokenTypes::ID_ALIAS:
                        $alias    = $token->getText();
                        $result[] = $alias;
                        break;
                    case TokenTypes::ID_PROPERTY:
                        $model                      = $this->propertyModelByAlias($alias, $token->getText());
                        $dataType                   = $model->getDataType();
                        $result[count($result) - 1] .= $model->getFieldName();
                        break;
                    case TokenTypes::ID_PARAMETER:
                        $paramName = $token->getText();
                        if ($parameters->get($paramName) == null) {
                            $paramModel = new ParameterModel($dataType, null, false);
                            $parameters->put($paramName, $paramModel);
                        }
                        $result[count($result) - 1] .= $paramName;
                        break;
                    case TokenTypes::ID_FALSE:
                        $result[] = '0';
                        break;
                    case TokenTypes::ID_TRUE:
                        $result[] = '1';
                        break;
                    case TokenTypes::ID_NULL:
                        $index = count($result) - 1;
                        if ($result[$index] == '=') {
                            $result[$index] = 'is';
                        } else {
                            $result[$index] = 'is not';
                        }
                        $result[] = $token->getText();
                        break;
                    default:
                        $result[] = $token->getText();
                        break;
                }
            }
            $result[] = ')';
        }
        return (!$result) ? '' : 'where ' . implode(' ', $result);
    }

    /**
     * Генерирует SQL для конструкции FILTER
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery $query Объект запроса
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     *
     * @since 1.0.2
     */
    protected function generateFilterSQL(EntityQuery $query): string {
        $result       = [];
        $queryModel   = $query->getModel();
        $filterModels = $queryModel->getFilterModels();
        foreach ($filterModels as $filterModel) {
            assert($filterModel instanceof FilterModel);
            $alias    = $filterModel->getAlias();
            $property = $filterModel->getProperty();
            $field    = $alias . '.' . $this->fieldNameByAlias($alias, $property);
            $model    = $this->propertyModelByAlias($alias, $property);
            switch ($filterModel->getFilterType()) {
                case FilterModel::FILTER_LIKE:
                    $filterSQL = $this->processLikeFilterModel($field, $model->getDataType(), $filterModel, $queryModel->getParameters());
                    break;
                case FilterModel::FILTER_BETWEEN:
                    $filterSQL = $this->processBetweenFilterModel($field, $model->getDataType(), $filterModel, $queryModel->getParameters());
                    break;
                default:
                    $filterSQL = '';
                    break;
            }
            if ($filterSQL) {
                $result[] = $filterSQL;
            }
        }
        return implode(' and ', $result);
    }

    /**
     * Обрабатывает модель данных фильтра типа LIKE
     *
     * @param string                                    $field       Имя поля SQL
     * @param int                                       $dataType    Тип данных
     * @param \XEAF\Rack\ORM\Models\Parsers\FilterModel $filterModel Модель данных
     * @param \XEAF\Rack\API\Interfaces\IKeyValue       $parameters  Набор параметров запроса
     *
     * @return string
     *
     * @since 1.0.2
     */
    protected function processLikeFilterModel(string $field, int $dataType, FilterModel $filterModel, IKeyValue $parameters): string {
        $db = $this->_em->getDb();
        $filterModel->setType(DataTypes::DT_STRING);
        switch ($dataType) {
            case DataTypes::DT_DATE:
                $left = $db->upperCaseExpression($db->dateExpression($field));
                break;
            case DataTypes::DT_DATETIME:
                $left = $db->upperCaseExpression($db->dateTimeExpression($field));
                break;
            default:
                $left = $db->upperCaseExpression($field);
                break;
        }
        $parameter = $filterModel->getParameter();
        if ($parameters->get($parameter) == null) {
            $paramModel = new ParameterModel(DataTypes::DT_STRING, null, true);
            $parameters->put($parameter, $paramModel);
        }
        $right = $db->upperCaseExpression(':' . $parameter);
        return "$left like $right";
    }

    /**
     * Обрабатывает модель данных фильтра типа BETWEEN
     *
     * @param string                                    $field       Имя поля SQL
     * @param int                                       $dataType    Тип данных
     * @param \XEAF\Rack\ORM\Models\Parsers\FilterModel $filterModel Модель данных
     * @param \XEAF\Rack\API\Interfaces\IKeyValue       $parameters  Набор параметров запроса
     *
     * @return string
     *
     * @since 1.0.2
     */
    protected function processBetweenFilterModel(string $field, int $dataType, FilterModel $filterModel, IKeyValue $parameters) {
        $filterModel->setType($dataType);
        $minParameter = FilterModel::MIN_FILTER_PREFIX . $filterModel->getParameter();
        $maxParameter = FilterModel::MAX_FILTER_PREFIX . $filterModel->getParameter();
        $parameters->put($minParameter, new ParameterModel($dataType, null, true));
        $parameters->put($maxParameter, new ParameterModel($dataType, null, true));
        return "$field >= :$minParameter and $field <= :$maxParameter";
    }

    /**
     * Генерирует SQL для конструкции ORDER
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $orderModels Список моделей данных
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function generateOrderSQL(ICollection $orderModels): string {
        $result = [];
        foreach ($orderModels as $orderModel) {
            assert($orderModel instanceof OrderModel);
            // $tableName = $this->tableNameByAlias($orderModel->getAlias());
            $tableAlias = $orderModel->getAlias();
            $fieldName  = $this->fieldNameByAlias($orderModel->getAlias(), $orderModel->getProperty());
            $direction  = $orderModel->getDirection() == TokenTypes::KW_DESCENDING ? 'descending' : '';
            $result[]   = "$tableAlias.$fieldName $direction";
        }
        return (!$result) ? '' : 'order by ' . implode(', ', $result);
    }

    /**
     * Возвращает идентификатора типа присоединения
     *
     * @param int $joinType Код типа присоединения
     *
     * @return string
     */
    private function joinTypeName(int $joinType): string {
        $result = '';
        switch ($joinType) {
            case TokenTypes::KW_LEFT:
                $result = 'left';
                break;
            case TokenTypes::KW_RIGHT:
                $result = 'right';
                break;
            case TokenTypes::KW_INNER:
                $result = 'inner';
                break;
            case TokenTypes::KW_OUTER:
                $result = 'outer';
                break;
        }
        return $result;
    }

    /**
     * Возвращает модель сущности по псевдониму
     *
     * @param string $alias Псевдоним
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    private function entityModelByAlias(string $alias): EntityModel {
        $entity = (string)$this->_aliases->get($alias);
        if (empty($entity)) {
            throw EntityException::unknownEntityAlias($alias);
        }
        $result = $this->_entities->get($entity);
        if ($result == null) {
            throw EntityException::unknownEntity($entity);
        }
        assert($result instanceof EntityModel);
        return $result;
    }

    /**
     * Возвращает имя таблицы по псевдониму
     *
     * @param string $alias Псевдоним
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    private function tableNameByAlias(string $alias): string {
        $model = $this->entityModelByAlias($alias);
        return $model->getTableName();
    }

    /**
     * Возвращает модель свойства по псевдониму
     *
     * @param string $alias    Псевдоним
     * @param string $property Имя свойства
     *
     * @return \XEAF\Rack\ORM\Models\Properties\PropertyModel
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    private function propertyModelByAlias(string $alias, string $property): PropertyModel {
        $model  = $this->entityModelByAlias($alias);
        $result = $model->getPropertyByName($property);
        if ($result == null) {
            $name = (string)$this->_aliases->get($alias);
            throw EntityException::unknownEntityProperty($name, $property);
        }
        return $result;
    }

    /**
     * Возвращает имя поля БД по псевдониму сущности и свойству
     *
     * @param string $alias    Псевдоним сущности
     * @param string $property Имя свойства
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    private function fieldNameByAlias(string $alias, string $property): string {
        $model = $this->propertyModelByAlias($alias, $property);
        return $model->getFieldName();
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\ORM\Interfaces\IGenerator
     */
    public static function getInstance(): IGenerator {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IGenerator);
        return $result;
    }
}

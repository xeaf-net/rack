<?php declare(strict_types = 1);

/**
 * Resolver.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\Parsers\ResolveModel;
use XEAF\Rack\ORM\Models\Properties\OneToManyProperty;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;
use XEAF\Rack\ORM\Utils\Lex\RelationType;

/**
 * Реализует методы разрашения отношений
 *
 * @package  XEAF\Rack\ORM\Utils
 */
class Resolver implements IFactoryObject {

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * Разрешает отношения в запросе
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery $query Объект запроса
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function resolveRelations(EntityQuery $query): void {
        $models = $query->getModel()->getResolveModels();
        foreach ($models as $model) {
            assert($model instanceof ResolveModel);
            if ($model->getRelationType() == RelationType::UNKNOWN) {
                $this->resolveRelation($query, $model);
            }
        }
    }

    /**
     * Разрешает найденное отношение
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery            $query Объект запроса
     * @param \XEAF\Rack\ORM\Models\Parsers\ResolveModel $model Модель отношения
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveRelation(EntityQuery $query, ResolveModel $model): void {
        $alias      = $model->getAlias();
        $queryModel = $query->getModel();
        $entityName = $queryModel->findEntityByAlias($alias);
        if (!$entityName) {
            throw EntityException::unknownEntityAlias($alias);
        }
        $entityModel = $query->getEntityManager()->getEntityModel($entityName);
        if (!$entityModel) {
            throw EntityException::unknownEntity($entityName);
        }
        $property = $entityModel->getPropertyByName($model->getProperty());
        if (!$property) {
            throw EntityException::unknownEntityProperty($entityName, $model->getProperty());
        }
        switch ($property->getDataType()) {
            case DataTypes::DT_ONE_TO_MANY:
                assert($property instanceof OneToManyProperty);
                $this->resolveOneToMany($entityModel, $property, $model);
                break;
            case DataTypes::DT_MANY_TO_ONE:
                $this->resolveManyToOne($model);
                break;
        }
    }

    /**
     * Разрешает отношение Один ко многим
     *
     * @param \XEAF\Rack\ORM\Models\EntityModel                  $entityModel Модель сущности
     * @param \XEAF\Rack\ORM\Models\Properties\OneToManyProperty $property    Модель свойства
     * @param \XEAF\Rack\ORM\Models\Parsers\ResolveModel         $model       Модель отношения
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveOneToMany(EntityModel $entityModel, OneToManyProperty $property, ResolveModel $model): void {
        // 'users' => self:oneToMany('users', ['roleId'])
        // $query->resolve('roles','users');
        $query         = $model->getQuery();
        $foreignEntity = $property->getEntity();
        $foreignKeys   = $property->getKeys();
        $primaryKeys   = $entityModel->getPrimaryKeyNames();
        $query->select($foreignEntity)->from($foreignEntity);
        if (count($foreignKeys) != count($primaryKeys)) {
            throw EntityException::incorrectNumberOfLinkKeys($model->getAlias(), $model->getProperty());
        }
        for ($key = 0; $key < count($primaryKeys); $key++) {
            $prop  = $foreignKeys[$key];
            $param = $primaryKeys[$key];
            $query->andWhere("$foreignEntity.$prop == :$param");
            $query->parameter("$param", null);
        }
        $model->setRelationType(RelationType::ONE_TO_MANY);
    }

    /**
     * Разрешает отношение многие к одному
     *
     * @param \XEAF\Rack\ORM\Models\Parsers\ResolveModel $model Модель отношения
     *
     * @return void
     */
    protected function resolveManyToOne(ResolveModel $model): void {
        // $query->resolve('users','role');
        $model->setRelationType(RelationType::MANY_TO_ONE);
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\ORM\Utils\Resolver
     */
    public static function getInstance(): Resolver {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof Resolver);
        return $result;
    }
}

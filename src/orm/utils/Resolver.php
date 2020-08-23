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
use XEAF\Rack\ORM\Core\EntityManager;
use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\Parsers\FromModel;
use XEAF\Rack\ORM\Models\Parsers\JoinModel;
use XEAF\Rack\ORM\Models\Parsers\WithModel;
use XEAF\Rack\ORM\Models\Properties\ManyToOneProperty;
use XEAF\Rack\ORM\Models\Properties\OneToManyProperty;
use XEAF\Rack\ORM\Models\Properties\RelationModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\RelationTypes;
use XEAF\Rack\ORM\Utils\Lex\ResolveTypes;

/**
 * Реализует методы разрешения ссылок в запросах
 *
 * @package XEAF\Rack\ORM\Utils
 */
class Resolver implements IFactoryObject {

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * Разрешает ссылку конструкции WITH
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery         $query     Объект запроса
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel Объект модели WITH
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function resolveWith(EntityQuery $query, WithModel $withModel): void {
        $em          = $query->getEntityManager();
        $entityName  = $this->findEntityName($query->getModel(), $withModel);
        $entityModel = $this->findEntityModel($em, $entityName);
        $property    = $entityModel->getPropertyByName($withModel->getProperty());
        if (!$property || !$property->getIsRelation()) {
            throw EntityException::unknownEntityProperty($entityName, $withModel->getProperty());
        }
        assert($property instanceof RelationModel);
        $withModel->setRelation($property);
        switch ($property->getType()) {
            case RelationTypes::ONE_TO_MANY:
                assert($property instanceof OneToManyProperty);
                $this->resolveOneToMany($em, $entityModel, $withModel, $property);
                break;
            case RelationTypes::MANY_TO_ONE:
                assert($property instanceof ManyToOneProperty);
                $this->resolveManyToOne($query, $entityModel, $withModel, $property);
                break;
        }
    }

    /**
     * Разрешает отношение Один ко многим
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager                  $em          Менеджер сущностей
     * @param \XEAF\Rack\ORM\Models\EntityModel                  $entityModel Модель сущности
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel            $withModel   Объект модели WITH
     * @param \XEAF\Rack\ORM\Models\Properties\OneToManyProperty $property    Свойство отношения
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveOneToMany(EntityManager $em, EntityModel $entityModel, WithModel $withModel, OneToManyProperty $property): void {
        $query       = $withModel->getQuery();
        $entity      = $property->getEntity();
        $primaryKeys = $entityModel->getPrimaryKeyNames();
        $parameters  = $withModel->getRelation()->getLinks();
        if (!$query) {
            $query = $em->query('');
            $withModel->setQuery($query);
        }
        $query->select($entity)->from($entity);
        $key = 0;
        foreach ($primaryKeys as $primaryKey) {
            $param = $parameters[$key++];
            $query->andWhere("$entity.$param == :$primaryKey");
            $query->parameter($primaryKey, null);
        }
    }

    /**
     * Разрешает отношение Многие к одному
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery                    $query       Объект запроса
     * @param \XEAF\Rack\ORM\Models\EntityModel                  $entityModel Модель сущности
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel            $withModel   Объект модели WITH
     * @param \XEAF\Rack\ORM\Models\Properties\ManyToOneProperty $property    Свойство отношения
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveManyToOne(EntityQuery $query, EntityModel $entityModel, WithModel $withModel, ManyToOneProperty $property): void {
        switch ($withModel->getResolveType()) {
            case ResolveTypes::LAZY:
                $this->resolveLazyManyToOne($query->getEntityManager(), $entityModel, $withModel, $property);
                break;
            case ResolveTypes::EAGER:
                $this->resolveEagerManyToOne($query, $entityModel, $withModel, $property);
                break;
        }
    }

    /**
     * Разрешает "ленивое" отношение Многие к одному
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager                  $em          Менеджер сущностей
     * @param \XEAF\Rack\ORM\Models\EntityModel                  $entityModel Модель сущности
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel            $withModel   Объект модели WITH
     * @param \XEAF\Rack\ORM\Models\Properties\ManyToOneProperty $property    Свойство отношения
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveLazyManyToOne(EntityManager $em, EntityModel $entityModel, WithModel $withModel, ManyToOneProperty $property): void {
        $query = $withModel->getQuery();
        if (!$query) {
            $query = $em->query('');
            $withModel->setQuery($query);
        }
        $entity      = $property->getEntity();
        $primaryKeys = $entityModel->getPrimaryKeyNames();
        $foreignKeys = $property->getLinks();
        $query->select($entity)->from($entity);
        $key = 0;
        foreach ($primaryKeys as $primaryKey) {
            $foreignKey = $foreignKeys[$key++];
            $query->andWhere("$entity.$primaryKey == :$foreignKey");
            $query->parameter($foreignKey, null);
        }
    }

    /**
     * Разрешает "нетерпеливое" отношение Многие к одному
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery                    $query       Объект запроса
     * @param \XEAF\Rack\ORM\Models\EntityModel                  $entityModel Модель сущности
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel            $withModel   Объект модели WITH
     * @param \XEAF\Rack\ORM\Models\Properties\ManyToOneProperty $property    Свойство отношения
     *
     * @return void
     */
    protected function resolveEagerManyToOne(EntityQuery $query, EntityModel $entityModel, WithModel $withModel, ManyToOneProperty $property): void {
        $alias      = $withModel->getAlias();
        $fullAlias  = $alias . '_' . $withModel->getProperty();
        $entity     = $property->getEntity();
        $primaryKey = implode('_', $entityModel->getPrimaryKeyNames());
        $foreignKey = implode('_', $property->getLinks());
        $query->select($fullAlias)->leftJoin($entity, $fullAlias, $primaryKey, $alias, $foreignKey);
    }

    /**
     * Ищет модель сущности
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager $em         Менеджер сущностей
     * @param string                            $entityName Имя сущности
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function findEntityModel(EntityManager $em, string $entityName): EntityModel {
        $result = $em->getEntities()->get($entityName);
        if (!$result) {
            throw EntityException::unknownEntity($entityName);
        }
        assert($result instanceof EntityModel);
        return $result;
    }

    /**
     * Ищет имя сущности по псевдониму
     *
     * @param \XEAF\Rack\ORM\Models\QueryModel        $qm Модель запроса
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $wm Модель конструкции WITH
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function findEntityName(QueryModel $qm, WithModel $wm): string {
        $result = $this->findFromEntity($qm, $wm);
        if (!$result) {
            $result = $this->findJoinEntity($qm, $wm);
        }
        if (!$result) {
            throw EntityException::unknownEntityAlias($wm->getAlias());
        }
        return $result;
    }

    /**
     * Ищет имя сущности в конструкции FROM
     *
     * @param \XEAF\Rack\ORM\Models\QueryModel        $qm Модель запроса
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $wm Модель конструкции WITH
     *
     * @return string|null
     */
    protected function findFromEntity(QueryModel $qm, WithModel $wm): ?string {
        $models = $qm->getFromModels();
        foreach ($models as $model) {
            assert($model instanceof FromModel);
            if ($model->getAlias() == $wm->getAlias()) {
                return $model->getEntity();
            }
        }
        return null;
    }

    /**
     * Ищет имя сущности в конструкции JOIN
     *
     * @param \XEAF\Rack\ORM\Models\QueryModel        $qm Модель запроса
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $wm Модель конструкции WITH
     *
     * @return string|null
     */
    protected function findJoinEntity(QueryModel $qm, WithModel $wm): ?string {
        $models = $qm->getJoinModels();
        foreach ($models as $model) {
            assert($model instanceof JoinModel);
            if ($model->getJoinAlias() == $wm->getAlias()) {
                return $model->getJoinEntity();
            }
        }
        return null;
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\ORM\Utils\Resolver
     */
    public static function getInstance(): Resolver {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof Resolver);
        return $result;
    }
}

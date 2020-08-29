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
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\ORM\Core\Entity;
use XEAF\Rack\ORM\Core\EntityManager;
use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Interfaces\IResolver;
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\Parsers\FromModel;
use XEAF\Rack\ORM\Models\Parsers\JoinModel;
use XEAF\Rack\ORM\Models\Parsers\WithModel;
use XEAF\Rack\ORM\Models\Properties\ManyToOneProperty;
use XEAF\Rack\ORM\Models\Properties\OneToManyProperty;
use XEAF\Rack\ORM\Models\Properties\RelationModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Models\RelationValue;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\RelationTypes;
use XEAF\Rack\ORM\Utils\Lex\ResolveTypes;

/**
 * Реализует методы разрешения ссылок в запросах
 *
 * @package XEAF\Rack\ORM\Utils
 */
class Resolver implements IResolver {

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function withModelQuery(EntityManager $entityManager, WithModel $withModel): EntityQuery {
        $query = $withModel->getQuery();
        if (!$query) {
            $query = $entityManager->query('');
            $withModel->setQuery($query);
        }
        return $query;
    }

    /**
     * @inheritDoc
     */
    public function resolveWithModel(EntityQuery $query, WithModel $withModel): void {
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
                $this->resolveOneToMany($em, $entityName, $withModel, $property);
                break;
            case RelationTypes::MANY_TO_ONE:
                assert($property instanceof ManyToOneProperty);
                $this->resolveManyToOne($query, $entityModel, $withModel, $property);
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function resolveLazyValue(Entity $entity, WithModel $withModel): void {
        $property = $withModel->getProperty();
        $value    = new RelationValue($withModel);
        $entity->setRelationValue($property, $value);
    }

    /**
     * @inheritDoc
     */
    public function resolveEagerValue(Entity $entity, WithModel $withModel): RelationValue {
        $data     = null;
        $query    = $withModel->getQuery();
        $property = $withModel->getProperty();
        $params   = $query->getModel()->getParameters()->keys();
        foreach ($params as $param) {
            $prop = ltrim($param, '__');
            if ($entity->getModel()->propertyExists($prop)) {
                $query->parameter($param, $entity->{$prop});
            }
        }
        switch ($withModel->getRelation()->getType()) {
            case RelationTypes::ONE_TO_MANY:
                $data = $query->get($withModel->getParameters());
                break;
            case RelationTypes::MANY_TO_ONE:
                $data = $query->getFirst($withModel->getParameters());
                break;
        }
        $value = new RelationValue($withModel);
        $value->setValue($data);
        $entity->setRelationValue($property, $value);
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function relationToArray(Entity $entity, string $name, RelationModel $property, array $data, array $cleanups): array {
        $value  = null;
        $result = $data;
        $exists = $entity->getRelationValue($name) != null;
        $em     = $entity->getModel()->getEntityManager();
        if ($exists) {
            $value = $entity->{$name};
        }
        switch ($property->getType()) {
            case RelationTypes::ONE_TO_MANY:
                if ($exists) {
                    $result[$name] = $this->oneToManyToArray($name, $value, $property, $cleanups);
                }
                break;
            case RelationTypes::MANY_TO_ONE:
                if ($value && !in_array($name, $cleanups)) {
                    $result[$name] = $value->toArray([], $cleanups);
                } else {
                    $entity        = $property->getEntity();
                    $result[$name] = $this->manyToOneToArray($em, $entity, $property, $data);
                }
                break;
        }
        // PK! OK!
        $links = $property->getLinks();
        foreach ($links as $link) {
            unset($result[$link]);
        }
        return $result;
    }

    /**
     * Разрешает отношение Один ко многим
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager                  $em         Менеджер сущностей
     * @param string                                             $entityName Имя разбираемой сущности
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel            $withModel  Объект модели WITH
     * @param \XEAF\Rack\ORM\Models\Properties\OneToManyProperty $property   Свойство отношения
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveOneToMany(EntityManager $em, string $entityName, WithModel $withModel, OneToManyProperty $property): void {
        $query    = $this->withModelQuery($em, $withModel);
        $entity   = $property->getEntity();
        $relation = $withModel->getRelation();
        $links    = $relation->getLinks();
        if (!$links) {
            $links = $this->resolveOneToManyLinks($em, $entity, $entityName);
            $relation->setLinks($links);
        }
        $linksPK = $this->resolveLinksPK($em, $entity, $links);
        $query->select($entity)->from($entity);
        // PK! OK!
        foreach ($linksPK as $link => $primaryKey) {
            $param = "__$primaryKey";
            $query->andWhere("$entity.$link == :$param");
            $query->parameter($param, null);
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
                $this->resolveLazyManyToOne($query->getEntityManager(), $withModel, $property);
                break;
            case ResolveTypes::EAGER:
                $this->resolveEagerManyToOne($query, $entityModel, $withModel, $property);
                break;
        }
    }

    /**
     * Разрешает "ленивое" отношение Многие к одному
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager                  $em        Менеджер сущностей
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel            $withModel Объект модели WITH
     * @param \XEAF\Rack\ORM\Models\Properties\ManyToOneProperty $property  Свойство отношения
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveLazyManyToOne(EntityManager $em, WithModel $withModel, ManyToOneProperty $property): void {
        $query   = $this->withModelQuery($em, $withModel);
        $entity  = $property->getEntity();
        $links   = $property->getLinks();
        $linksPK = $this->resolveLinksPK($em, $entity, $links);
        $query->select($entity)->from($entity);
        // PK! OK!
        foreach ($linksPK as $foreignKey => $primaryKey) {
            $param = "__$foreignKey";
            $query->andWhere("$entity.$primaryKey == :$param");
            $query->parameter($param, null);
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
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveEagerManyToOne(EntityQuery $query, EntityModel $entityModel, WithModel $withModel, ManyToOneProperty $property): void {
        $alias     = $withModel->getAlias();
        $fullAlias = $withModel->getFullAlias();
        $entity    = $property->getEntity();
        $links     = $property->getLinks();
        $linksPK   = $this->resolveLinksPK($query->getEntityManager(), $entity, $links);
        if (count($links) != 1) {
            throw EntityException::unsupportedFeature();
        }
        // PK! OK!
        foreach ($linksPK as $foreignKey => $primaryKey) {
            $primaryKey = implode('_', $entityModel->getPrimaryKeyNames());
            $query->select($fullAlias)->leftJoin($entity, $fullAlias, $primaryKey, $alias, $foreignKey);
        }
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
     * Преобразует сущность связи Один ко многим в массив
     *
     * @param string                                         $name     Имя свойства
     * @param \XEAF\Rack\API\Interfaces\ICollection|null     $value    Коллекция объектов сущностей
     * @param \XEAF\Rack\ORM\Models\Properties\RelationModel $property Модель свойства
     * @param array                                          $cleanups Идентификаторы очищаемых сущностей связей
     *
     * @return array
     */
    protected function oneToManyToArray(string $name, ?ICollection $value, RelationModel $property, array $cleanups): array {
        $list = [];
        if ($value->count() > 0) {
            $map   = [];
            $first = true;
            foreach ($value as $item) {
                assert($item instanceof Entity);
                if ($first) {
                    if (in_array($name, $cleanups)) {
                        $map = $item->getModel()->getPrimaryKeyNames();
                    }
                    $first = false;
                }
                $list[] = $item->toArray($map, $cleanups);
            }
        }
        return $list;
    }

    /**
     * Преобразует сущность связи Многие к одному в массив
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager|null         $em       Менеджер сущностей
     * @param string                                         $entity   Имя сущности
     * @param \XEAF\Rack\ORM\Models\Properties\RelationModel $property Модель свойства
     * @param array                                          $data     Набор исходных данных
     *
     * @return array|null
     */
    protected function manyToOneToArray(?EntityManager $em, string $entity, RelationModel $property, array $data): ?array {
        $result = [];
        // PK! OK!
        if ($em != null) {
            $links   = $property->getLinks();
            $linksPK = $this->resolveLinksPK($em, $entity, $links);
            foreach ($linksPK as $link => $primaryKey) {
                $value = $data[$link];
                if ($value == null) {
                    return null;
                }
                $result[$primaryKey] = $value;
            }
        }
        return $result;
    }

    /**
     * Возвращает массив разрешенных первичных ключей для связей
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager $em     Менеджер сущностей
     * @param string                            $entity Имя сущности
     * @param array                             $links  Исходынй массив связей
     *
     * @return array
     */
    protected function resolveLinksPK(EntityManager $em, string $entity, array $links): array {
        $result      = [];
        $model       = $em->getEntityModel($entity);
        $primaryKeys = $model->getPrimaryKeyNames();
        $key         = 0;
        foreach ($links as $link) {
            $result[$link] = $primaryKeys[$key++];
        }
        return $result;
    }

    /**
     * Возвращает массив ссылок связей
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager $em         Менеджер сущостей
     * @param string                            $fromEntity Имя сущности источника
     * @param string                            $toEntity   Имя сущности назначения
     *
     * @return array
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function resolveOneToManyLinks(EntityManager $em, string $fromEntity, string $toEntity): array {
        $model      = $em->getEntityModel($fromEntity);
        $properties = $model->getPropertyByNames();
        foreach ($properties as $name => $property) {
            if ($property instanceof ManyToOneProperty && $property->getEntity() == $toEntity) {
                return $property->getLinks();
            }
        }
        throw EntityException::unresolvedLink($fromEntity, $toEntity);
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\ORM\Interfaces\IResolver
     */
    public static function getInstance(): IResolver {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IResolver);
        return $result;
    }
}

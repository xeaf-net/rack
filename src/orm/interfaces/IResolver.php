<?php declare(strict_types = 1);

/**
 * IResolver.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Interfaces;

use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\ORM\Core\Entity;
use XEAF\Rack\ORM\Core\EntityManager;
use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Models\Parsers\WithModel;
use XEAF\Rack\ORM\Models\RelationValue;

/**
 * Описывает методы разрешения ссылок в запросах
 *
 * @package XEAF\Rack\ORM\Utils
 */
interface IResolver extends IFactoryObject {

    /**
     * Возвращает запрос конструкции WITH
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager       $entityManager Менеджер сущностей
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel     Модель конструкции WITH
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function withModelQuery(EntityManager $entityManager, WithModel $withModel): EntityQuery;

    /**
     * Разрешает ссылку конструкции WITH
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery         $query     Объект запроса
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel Объект модели WITH
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function resolveWithModel(EntityQuery $query, WithModel $withModel): void;

    /**
     * Разрешает "ленивое" значение
     *
     * @param \XEAF\Rack\ORM\Core\Entity              $entity    Объект сущности
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel Модель конструкции WITH
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function resolveLazyValue(Entity $entity, WithModel $withModel): void;

    /**
     * Разрешает "нетерпеливое" значение
     *
     * @param \XEAF\Rack\ORM\Core\Entity              $entity     Объект сущности
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel  Модель конструкции WITH
     *
     * @return \XEAF\Rack\ORM\Models\RelationValue
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function resolveEagerValue(Entity $entity, WithModel $withModel): RelationValue;

}

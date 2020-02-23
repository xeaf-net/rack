<?php

/**
 * IGenerator.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Interfaces;

use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\ORM\Core\Entity;
use XEAF\Rack\ORM\Core\EntityQuery;

/**
 * Описывает методы генерации текстов SQL запросов и команд
 *
 * @package XEAF\Rack\ORM\Interfaces
 */
interface IGenerator extends IFactoryObject {

    /**
     * Возвращает текст SQL запроса для выбора записей
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery $query Объект запроса
     *
     * @return string
     */
    function selectSQL(EntityQuery $query): string;

    /**
     * Возвращает текст SQL запроса для выбора количества записей
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery $query Объект запроса
     *
     * @return string
     */
    function selectCountSQL(EntityQuery $query): string;

    /**
     * Возвращает текст SQL команды для вставки записи
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return string
     */
    function insertSQL(Entity $entity): string;

    /**
     * Возвращает текст SQL команды для изменения записи
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return string
     */
    function updateSQL(Entity $entity): string;

    /**
     * Возвращает текст SQL команды для удаления записи
     *
     * @param \XEAF\Rack\ORM\Core\Entity $entity Объект сущности
     *
     * @return string
     */
    function deleteSQL(Entity $entity): string;
}

<?php

/**
 * IEntityStorage.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Interfaces;

use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\ORM\Models\EntityModel;

/**
 * Описывает методы хранилища объектов ORM
 *
 * @package XEAF\Rack\ORM\Interfaces
 */
interface IEntityStorage extends IFactoryObject {

    /**
     * Возвращает модель сущности
     *
     * @param string $className Имя класса сущности
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel|null
     */
    function getModel(string $className): ?EntityModel;

    /**
     * Сохраняет информацию о модели сущности
     *
     * @param string                            $className   Имя класса сущности
     * @param \XEAF\Rack\ORM\Models\EntityModel $entityModel Модель сущности
     *
     * @return void
     */
    function putModel(string $className, EntityModel $entityModel): void;

    /**
     * Возвращает текст SQL команды вставки записи
     *
     * @param string $className Имя класса
     *
     * @return string|null
     */
    function getInsertSQL(string $className): ?string;

    /**
     * Сохраняет текст SQL команды вставки записи
     *
     * @param string $className Имя класса
     * @param string $sql       Текст SQL команды
     *
     * @return void
     */
    function putInsertSQL(string $className, string $sql): void;

    /**
     * Возвращает текст SQL команды изменения записи
     *
     * @param string $className Имя класса
     *
     * @return string|null
     */
    function getUpdateSQL(string $className): ?string;

    /**
     * Сохраняет текст SQL команды изменения записи
     *
     * @param string $className Имя класса
     * @param string $sql       Текст SQL команды
     *
     * @return void
     */
    function putUpdateSQL(string $className, string $sql): void;

    /**
     * Возвращает текст SQL команды удаления записи
     *
     * @param string $className Имя класса
     *
     * @return string|null
     */
    function getDeleteSQL(string $className): ?string;

    /**
     * Сохраняет текст SQL команды изменения записи
     *
     * @param string $className Имя класса
     * @param string $sql       Текст SQL команды
     *
     * @return void
     */
    function putDeleteSQL(string $className, string $sql): void;
}

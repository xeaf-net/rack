<?php declare(strict_types = 1);

/**
 * IPolitics.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы предоставления информации о политике приложения
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IPolitics extends IFactoryObject {

    /**
     * Признак разрешения использования нативной сессии
     *
     * @return bool
     */
    public function allowNativeSession(): bool;

    /**
     * Признак разрешения использования режима отладки
     *
     * @return bool
     */
    public function allowDebugMode(): bool;

    /**
     * Принудительное назначение кода 200 в заголовке FormResult
     *
     * @return bool
     */
    public function forceFormResult200(): bool;

    /**
     * Добавление штампа даты и времени в ответ
     *
     * @return bool
     */
    public function dataResultTimestamp(): bool;

    /**
     * Штамп даты и времени в ответе передается в формате JS
     *
     * @return bool
     */
    public function dataResultJSTimestamp(): bool;
}

<?php

/**
 * ISessionActions.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы работы с сессиями
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ISessionActions extends IKeyValue {

    /**
     * Возвращает идентификатор сессии
     *
     * @return string|null
     */
    function getId(): ?string;

    /**
     * Задает идентификатор сесии
     *
     * @param string|null $sessionId Идентификатор сесии
     *
     * @return void
     */
    function setId(?string $sessionId): void;

    /**
     * Возвращает идентификатор пользователя сессии
     *
     * @return string|null
     */
    function getUserId(): ?string;

    /**
     * Задает идентификатор пользователя сессии
     *
     * @param string|null $userId Идентификатор пользователя
     *
     * @return void
     */
    function setUserId(?string $userId): void;

    /**
     * Возвращет имя локали сессии
     *
     * @return string|null
     */
    function getLocale(): ?string;

    /**
     * Задет имя локали сессии
     *
     * @param string|null $locale Имя локали
     *
     * @return void
     */
    function setLocale(?string $locale): void;

    /**
     * Загружает значения переменных сессии
     *
     * @return void
     */
    function loadSessionVars(): void;

    /**
     * Сохраняет значения переменных сессии
     *
     * @return void
     */
    function saveSessionVars(): void;

    /**
     * Возвращает признак нативной сессии
     *
     * @return bool
     */
    function isNative(): bool;
}

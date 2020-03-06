<?php declare(strict_types = 1);

/**
 * ISessionActions.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
    public function getId(): ?string;

    /**
     * Задает идентификатор сесии
     *
     * @param string|null $sessionId Идентификатор сесии
     *
     * @return void
     */
    public function setId(?string $sessionId): void;

    /**
     * Возвращает идентификатор API приложения
     *
     * @return string
     */
    public function getApiId(): string;

    /**
     * Задает идетификатор API приложения
     *
     * @param string $apiId Идентификатор API приложения
     *
     * @return void
     */
    public function setApiId(string $apiId): void;

    /**
     * Возвращает идентификатор пользователя сессии
     *
     * @return string|null
     */
    public function getUserId(): ?string;

    /**
     * Задает идентификатор пользователя сессии
     *
     * @param string|null $userId Идентификатор пользователя
     *
     * @return void
     */
    public function setUserId(?string $userId): void;

    /**
     * Возвращет имя локали сессии
     *
     * @return string|null
     */
    public function getLocale(): ?string;

    /**
     * Задет имя локали сессии
     *
     * @param string|null $locale Имя локали
     *
     * @return void
     */
    public function setLocale(?string $locale): void;

    /**
     * Загружает значения переменных сессии
     *
     * @return void
     */
    public function loadSessionVars(): void;

    /**
     * Сохраняет значения переменных сессии
     *
     * @return void
     */
    public function saveSessionVars(): void;

    /**
     * Возвращает признак нативной сессии
     *
     * @return bool
     */
    public function isNative(): bool;
}

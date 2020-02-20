<?php

/**
 * StaticSessionProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Sessions;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\ISessionProvider;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Crypto;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Utils\Session;

/**
 * Реализует методы статического провайдера сессии
 *
 * @package XEAF\Rack\API\Utils\Sessions
 */
class StaticSessionProvider extends KeyValue implements ISessionProvider {

    use NamedObjectTrait;

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'static';

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name = $name;
        $this->setId(Crypto::getInstance()->generateUUIDv4());
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string {
        return $this->get(Session::SESSION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setId(?string $sessionId): void {
        $this->put(Session::SESSION_ID, $sessionId);
    }

    /**
     * @inheritDoc
     */
    public function getUserId(): ?string {
        return $this->get(Session::USER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setUserId(?string $userId): void {
        $this->put(Session::USER_ID, $userId);
    }

    /**
     * @inheritDoc
     */
    public function getLocale(): ?string {
        return $this->get(Session::LOCALE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setLocale(?string $locale): void {
        $this->put(Session::LOCALE_NAME, $locale);
    }

    /**
     * @inheritDoc
     */
    public function loadSessionVars(): void {
        // Ничего не делать
    }

    /**
     * @inheritDoc
     */
    public function saveSessionVars(): void {
        // Ничего не делать
    }

    /**
     * @inheritDoc
     */
    public function isNative(): bool {
        return false;
    }
}

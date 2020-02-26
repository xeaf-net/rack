<?php

/**
 * Session.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\ISession;
use XEAF\Rack\API\Interfaces\ISessionProvider;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Models\Config\ProviderConfig;
use XEAF\Rack\API\Traits\ProviderFactoryTrait;

/**
 * Реализует методы работы с сессиями
 *
 * @package XEAF\Rack\API\Utils
 */
class Session implements ISession {

    use ProviderFactoryTrait;

    /**
     * Переменная идентификатора сессии
     */
    public const SESSION_ID = 'X-Session';

    /**
     * Переменная идентификатора JWT сессии
     */
    public const SESSION_AUTH = 'Authorization';

    /**
     * Переменная установленной локали
     */
    public const LOCALE_NAME = 'X-Locale';

    /**
     * Переменная идентификатора пользователя
     */
    public const USER_ID = 'X-User';

    /**
     * Префикс имени переменной сессии
     */
    public const SESSION_VAR_PREFIX = 'session-';

    /**
     * Провайдер сессии
     * @var \XEAF\Rack\API\Interfaces\ISessionProvider|null
     */
    private $_provider = null;

    /**
     * Конструктор класса
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public function __construct() {
        $this->_provider = $this->createProvider();
        $this->loadSessionVars();
    }

    /**
     * @inheritDoc
     */
    public function clear(): void {
        $this->_provider->clear();
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool {
        return $this->_provider->isEmpty();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $defaultValue = null) {
        return $this->_provider->get($key, $defaultValue);
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, $value = null): void {
        $this->_provider->put($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void {
        $this->_provider->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool {
        return $this->_provider->exists($key);
    }

    /**
     * @inheritDoc
     */
    public function keys(): array {
        return $this->_provider->keys();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array {
        return $this->_provider->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string {
        return $this->_provider->getId();
    }

    /**
     * @inheritDoc
     */
    public function setId(?string $sessionId): void {
        $this->_provider->setId($sessionId);
    }

    /**
     * @inheritDoc
     */
    public function getUserId(): ?string {
        return $this->_provider->getUserId();
    }

    /**
     * @inheritDoc
     */
    public function setUserId(?string $userId): void {
        $this->_provider->setUserId($userId);
    }

    /**
     * @inheritDoc
     */
    public function getLocale(): ?string {
        return $this->_provider->getLocale();
    }

    /**
     * @inheritDoc
     */
    public function setLocale(?string $locale): void {
        $this->_provider->setLocale($locale);
    }

    /**
     * @inheritDoc
     */
    public function loadSessionVars(): void {
        $this->_provider->loadSessionVars();
    }

    /**
     * @inheritDoc
     */
    public function saveSessionVars(): void {
        $this->_provider->saveSessionVars();
    }

    /**
     * @inheritDoc
     */
    public function isAuthorized(): bool {
        return $this->getUserId() != null;
    }

    /**
     * @inheritDoc
     */
    public function isNative(): bool {
        return $this->_provider->isNative();
    }

    /**
     * @inheritDoc
     */
    public function current() {
        return $this->_provider->current();
    }

    /**
     * @inheritDoc
     */
    public function next() {
        $this->_provider->next();
    }

    /**
     * @inheritDoc
     */
    public function key() {
        return $this->_provider->key();
    }

    /**
     * @inheritDoc
     */
    public function valid() {
        return $this->_provider->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind() {
        $this->_provider->rewind();
    }

    /**
     * Возвращает объект провайдера сессий
     *
     * @return \XEAF\Rack\API\Interfaces\ISessionProvider
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    private function createProvider(): ISessionProvider {
        $config    = ProviderConfig::getInstance(PortalConfig::SECTION_NAME, 'session');
        $provider  = $config->getProvider();
        $className = self::getProviderClass($provider);
        return new $className(Factory::DEFAULT_NAME);
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\ISession
     */
    public static function getInstance(): ISession {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ISession);
        return $result;
    }
}

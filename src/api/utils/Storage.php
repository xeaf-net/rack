<?php

/**
 * Storage.php
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
use XEAF\Rack\API\Interfaces\IStorage;
use XEAF\Rack\API\Interfaces\IStorageProvider;
use XEAF\Rack\API\Models\Config\ProviderConfig;
use XEAF\Rack\API\Models\Config\StorageConfig;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Traits\ProviderFactoryTrait;

/**
 * Реализует методы именованного хранилища Ключ-Значение
 *
 * @package XEAF\Rack\API\Utils
 */
class Storage implements IStorage {

    use NamedObjectTrait;
    use ProviderFactoryTrait;

    /**
     * Максимальное время жизни значений
     */
    public const MAX_TTL = Calendar::SECONDS_PER_DAY * 365 * 1000;

    /**
     * Объект провайдера
     * @var IStorageProvider|null
     */
    private $_provider = null;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name     = $name;
        $this->_provider = $this->createProvider();
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
    public function put(string $key, $value = null, int $ttl = 0): void {
        $this->_provider->put($key, $value, $ttl);
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
     * Возвращает объект провайдера хранилища
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    private function createProvider(): IStorageProvider {
        $config    = ProviderConfig::getInstance(StorageConfig::SECTION_NAME, $this->getName());
        $provider  = $config->getProvider();
        $className = self::getProviderClass($provider);
        return new $className($this->getName());
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
     * Возвращает единичный экземпляр объекта
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IStorage
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): IStorage {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof IStorage);
        return $result;
    }
}

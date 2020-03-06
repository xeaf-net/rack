<?php declare(strict_types = 1);

/**
 * CacheStorage.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\ICacheStorage;
use XEAF\Rack\API\Traits\NamedObjectTrait;

/**
 * Реализует методы работы с кешем
 *
 * @package XEAF\Rack\API\Utils
 */
class CacheStorage implements ICacheStorage {

    use NamedObjectTrait;

    /**
     * Имя переменной хранилища свйзок тегов и ключей
     */
    protected const CACHE_KEYS = 'cache-keys';

    /**
     * Префикс имени переменой хранилища значений кеша
     */
    protected const CACHE_DATA_PREFIX = 'cache-data-';

    /**
     * Связки тегов и ключей
     * @var array
     */
    private $_keyTags = [];

    /**
     * Хранилище значений
     * @var \XEAF\Rack\API\Interfaces\IStorage
     */
    private $_storage = null;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name    = $name;
        $this->_storage = Storage::getInstance($name);
        $this->warmCache();
    }

    /**
     * Возвращает значение из кеша
     *
     * @param string $key          Ключ
     * @param null   $defaultValue Значение по умолчанию
     *
     * @return mixed|null
     */
    public function get(string $key, $defaultValue = null) {
        $var = $this->storageVar($key);
        return $this->_storage->get($var, $defaultValue);
    }

    /**
     * Сохраняет значение в кеше
     *
     * @param string $key   Ключ
     * @param null   $value Значение
     * @param int    $ttl   Время жизни
     * @param array  $tags  Набор тегов
     *
     * @return void
     */
    public function put(string $key, $value = null, int $ttl = 0, array $tags = []): void {
        $var = $this->storageVar($key);
        $this->_storage->put($var, $value, $ttl);
        $this->_keyTags[$key] = $tags;
        $this->updateCache();
    }

    /**
     * Возвращает набор связанных со значением тегов
     *
     * @param string $key Ключ
     *
     * @return array
     */
    public function getTags(string $key): array {
        return $this->_keyTags[$key] ?? [];
    }

    /**
     * Связывает значение кеша с одним или несколькими тегами
     *
     * @param string $key  Ключ
     * @param array  $tags Массив тегов
     *
     * @return void
     */
    public function setTags(string $key, array $tags): void {
        $this->_keyTags[$key] = $tags;
        $this->updateCache();
    }

    /**
     * Отменяет валидность значения кеша
     *
     * @param string $key Ключ
     *
     * @return void
     */
    public function invalidate(string $key): void {
        $var = $this->storageVar($key);
        $this->_storage->delete($var);
        unset($this->_keyTags[$key]);
        $this->updateCache();
    }

    /**
     * Отменяет валидность всех связанных с тегом переменных
     *
     * @param string $tag Тег
     *
     * @return void
     */
    public function invalidateTag(string $tag): void {
        $update = false;
        foreach ($this->_keyTags as $key => $tags) {
            if (in_array($tag, $tags)) {
                $var = $this->storageVar($key);
                $this->_storage->delete($var);
                unset($this->_keyTags[$key]);
                $update = true;
            }
        }
        if ($update) {
            $this->updateCache();
        }
    }

    /**
     * Отменяет валидность всех значений кеша
     *
     * @return void
     */
    public function invalidateAll(): void {
        foreach ($this->_keyTags as $key => $tags) {
            $var = $this->storageVar($key);
            $this->_storage->delete($var);
        }
        $this->_keyTags = [];
        $this->updateCache();
    }

    /**
     * Подготавливает кеш к работе
     *
     * @return void
     */
    protected function warmCache(): void {
        $this->_keyTags = $this->_storage->get(self::CACHE_KEYS, []);
    }

    /**
     * Обновление данных кеша
     *
     * @return void
     */
    protected function updateCache(): void {
        $this->_storage->put(self::CACHE_KEYS, $this->_keyTags);
    }

    /**
     * Возвращает имя переменной хранилища значений кеша
     *
     * @param string $key ключ
     *
     * @return string
     */
    protected function storageVar(string $key): string {
        return self::CACHE_DATA_PREFIX . $key;
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\API\Interfaces\ICacheStorage
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): ICacheStorage {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof ICacheStorage);
        return $result;
    }
}

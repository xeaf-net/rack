<?php declare(strict_types = 1);

/**
 * Cookie.php
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
use XEAF\Rack\API\Interfaces\ICookies;
use XEAF\Rack\API\Models\Config\PortalConfig;

/**
 * Реализует методы работы с Cookie
 *
 * @package XEAF\Rack\API\Utils
 */
class Cookie implements ICookies {

    /**
     * Текущая позиция итерации
     * @var int|null
     */
    private ?int $_position = null;

    /**
     * Ключи позиций итерации
     * @var array
     */
    private array $_positionKeys = [];

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function clear(): void {
        $keys = $this->keys();
        foreach ($keys as $key) {
            $this->delete($key);
        }
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool {
        return count($_COOKIE) == 0;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $defaultValue = null) {
        return $_COOKIE[$key] ?? $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, $value = null, int $ttl = 0): void {
        $expire        = $ttl == 0 ? 0 : time() + $ttl;
        $url           = PortalConfig::getInstance()->getUrl();
        $domain        = parse_url($url, PHP_URL_HOST);
        $_COOKIE[$key] = $value;
        setcookie($key, $value, $expire, '/', $domain);
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void {
        self::put($key, '', -Calendar::SECONDS_PER_HOUR);
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool {
        return isset($key, $_COOKIE);
    }

    /**
     * @inheritDoc
     */
    public function keys(): array {
        return array_keys($_COOKIE);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array {
        return $_COOKIE;
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\ICookies
     */
    public static function getInstance(): ICookies {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ICookies);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function current() {
        if ($this->_position === null) {
            $result = null;
        } else {
            $key    = $this->key();
            $result = $_COOKIE[$key];
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function next() {
        if ($this->_position !== null) {
            $this->_position++;
        }
    }

    /**
     * @inheritDoc
     */
    public function key() {
        return $this->_positionKeys[$this->_position];
    }

    /**
     * @inheritDoc
     */
    public function valid() {
        return $this->_position !== null && $this->_position < count($_COOKIE);
    }

    /**
     * @inheritDoc
     */
    public function rewind() {
        $this->_positionKeys = $this->keys();
        $this->_position = count($_COOKIE) > 0 ? 0 : null;
    }
}

<?php declare(strict_types = 1);

/**
 * KeyValue.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\IKeyValue;

/**
 * Реализует методы хранилища ключ - значения
 *
 * @package XEAF\Rack\API\Core
 */
class KeyValue implements IKeyValue {

    /**
     * Хранилище объектов
     * @var array
     */
    private array $_values = [];

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
     * @inheritDoc
     */
    public function clear(): void {
        $this->_values = [];
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool {
        return count($this->_values) == 0;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $defaultValue = null) {
        if ($this->exists($key)) {
            return $this->_values[$key];
        }
        return $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, $value = null): void {
        $this->_values[$key] = $value;
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void {
        if ($this->exists($key)) {
            unset($this->_values[$key]);
            $this->rewind();
        }
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool {
        return array_key_exists($key, $this->_values);
    }

    /**
     * @inheritDoc
     */
    public function keys(): array {
        return array_keys($this->_values);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array {
        return $this->_values;
    }

    /**
     * @inheritDoc
     */
    public function current() {
        if ($this->_position === null) {
            $result = null;
        } else {
            $key    = $this->key();
            $result = $this->_values[$key];
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
        return $this->_position !== null && $this->_position < count($this->_values);
    }

    /**
     * @inheritDoc
     */
    public function rewind() {
        $this->_positionKeys = $this->keys();
        $this->_position     = count($this->_values) > 0 ? 0 : null;
    }
}

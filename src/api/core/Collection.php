<?php declare(strict_types = 1);

/**
 * Collection.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Utils\Exceptions\CollectionException;

/**
 * Реализует методы работы с коллекцией объектов
 *
 * @package XEAF\Rack\API\Core
 */
class Collection implements ICollection {

    /**
     * Хранилище объектов коллекции
     * @var array
     */
    protected $_data = [];

    /**
     * Текущая позиция итерации
     * @var int|null
     */
    private $_position = null;

    /**
     * Признак возможности сохранять дубликаты
     * @var bool
     */
    protected $_duplicates = true;

    /**
     * Конструктор класса
     *
     * @param bool $duplicates Признак возможности сохранять дубликаты
     */
    public function __construct(bool $duplicates = true) {
        $this->_duplicates = $duplicates;
    }

    /**
     * @inheritDoc
     */
    public function clear(): void {
        $this->_data = [];
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool {
        return count($this->_data) == 0;
    }

    /**
     * @inheritDoc
     */
    public function count(): int {
        return count($this->_data);
    }

    /**
     * @inheritDoc
     */
    public function exists(DataObject $item): bool {
        $index = $this->indexOf($item);
        return $index >= 0;
    }

    /**
     * @inheritDoc
     */
    public function indexOf(DataObject $item): int {
        $index = array_search($item, $this->_data);
        return $index === false ? -1 : $index;
    }

    /**
     * @inheritDoc
     */
    public function item(int $index): DataObject {
        if ($index < 0 || $index > $this->count() - 1) {
            throw CollectionException::indexOutOfRange($index);
        }
        return $this->_data[$index];
    }

    /**
     * @inheritDoc
     */
    public function reorder(callable $compare): void {
        usort($this->_data, $compare);
        $this->rewind();
    }

    /**
     * @inheritDoc
     */
    public function toArray(array $map = []): array {
        $result = [];
        foreach ($this->_data as $key => $value) {
            assert($value instanceof DataObject);
            $result[$key] = $value->toArray($map);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function pop(): DataObject {
        if (!$this->isEmpty()) {
            $this->rewind();
            return array_shift($this->_data);
        }
        throw CollectionException::collectionIsEmpty();
    }

    /**
     * @inheritDoc
     */
    public function unpop(DataObject $item): void {
        if ($this->_duplicates || !$this->exists($item)) {
            array_unshift($this->_data, $item);
            $this->rewind();
        }
    }

    /**
     * @inheritDoc
     */
    public function push(DataObject $item): void {
        if ($this->_duplicates || !$this->exists($item)) {
            $this->_data[] = $item;
            $this->rewind();
        }
    }

    /**
     * @inheritDoc
     */
    public function unpush(): DataObject {
        if ($this->count() > 0) {
            $result = array_pop($this->_data);
            $this->rewind();
            return $result;
        }
        throw CollectionException::collectionIsEmpty();
    }

    /**
     * @inheritDoc
     */
    public function first(): DataObject {
        if (!$this->isEmpty()) {
            return $this->_data[0];
        }
        throw CollectionException::collectionIsEmpty();
    }

    /**
     * @inheritDoc
     */
    public function last(): DataObject {
        $count = $this->count();
        if ($count > 0) {
            return $this->_data[$count - 1];
        }
        throw CollectionException::collectionIsEmpty();
    }

    /**
     * @inheritDoc
     */
    public function current() {
        return $this->_position === null ? null : $this->_data[$this->_position];
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
        return $this->_position;
    }

    /**
     * @inheritDoc
     */
    public function valid() {
        return $this->_position !== null && $this->_position < $this->count();
    }

    /**
     * @inheritDoc
     */
    public function rewind() {
        $this->_position = $this->count() > 0 ? 0 : null;
    }
}

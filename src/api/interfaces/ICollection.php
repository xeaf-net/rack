<?php declare(strict_types = 1);

/**
 * ICollection.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use Iterator;
use XEAF\Rack\API\Core\DataObject;

/**
 * Описывает методы работы с коллекцией объектов
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ICollection extends Iterator {
    /**
     * Очищает коллекцию объектов
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Возвращает признак пустой коллекции
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Возвращает количество элементов в коллекции
     *
     * @return int
     */
    public function count(): int;

    /**
     * Проверяет существование элемента в коллекции
     *
     * @param \XEAF\Rack\API\Core\DataObject $item Проверяемый элемент
     *
     * @return bool
     */
    public function exists(DataObject $item): bool;

    /**
     * Возвращает индекс элемента в коллекции
     *
     * @param \XEAF\Rack\API\Core\DataObject $item
     *
     * @return int
     */
    public function indexOf(DataObject $item): int;

    /**
     * Возвращает значение элемента коллекции по индексу
     *
     * @param int $index Индекс
     *
     * @return \XEAF\Rack\API\Core\DataObject
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     */
    public function item(int $index): DataObject;

    /**
     * Изменяет порядок сортировки объектов
     *
     * @param callable $compare Функция сравнения объектов коллекции
     *
     * @return void
     */
    public function reorder(callable $compare): void;

    /**
     * Возвращает массив элементов
     *
     * @param array $map Карта возвращаемых свойств
     *
     * @return array
     */
    public function toArray(array $map = []): array;

    /**
     * Извлекает объект из коллекции
     *
     * @return \XEAF\Rack\API\Core\DataObject
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     */
    public function pop(): DataObject;

    /**
     * Отменяет последнюю операцию pop
     *
     * @param \XEAF\Rack\API\Core\DataObject $item Элемент коллекции
     *
     * @return void
     */
    public function unpop(DataObject $item): void;

    /**
     * Помещает объект в коллекцию
     *
     * @param \XEAF\Rack\API\Core\DataObject $item Элемент коллекции
     *
     * @return void
     */
    public function push(DataObject $item): void;

    /**
     * Отменяет поседюю операцию push
     *
     * @return \XEAF\Rack\API\Core\DataObject
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     */
    public function unpush(): DataObject;

    /**
     * Возвращает первый элемент коллекции
     *
     * @return \XEAF\Rack\API\Core\DataObject
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     */
    public function first(): DataObject;

    /**
     * Возвращает последний элемент коллекции
     *
     * @return mixed
     * @throws \XEAF\Rack\API\Utils\Exceptions\CollectionException
     */
    public function last(): DataObject;
}

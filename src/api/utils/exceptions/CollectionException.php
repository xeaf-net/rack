<?php

/**
 * CollectionException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе с коллекциями
 *
 * @package XEAF\Rack\API\Utils\Exceptions
 */
class CollectionException extends Exception {

    /**
     * Индекс выходит за границы диапазона
     */
    public const INDEX_OUT_OF_RANGE = 'COL001';

    /**
     * В коллекции нет элементов
     */
    public const COLLECTION_IS_EMPTY = 'COL002';

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = null;
        switch ($code) {
            case self::INDEX_OUT_OF_RANGE:
                $result = 'Index out of range %d.';
                break;
            case self::COLLECTION_IS_EMPTY:
                $result = 'Collection is empty.';
                break;
        }
        return $result;
    }

    /**
     * Индекс выходит за пределы диапазона
     *
     * @param int $index Значение индекса
     *
     * @return static
     */
    public static function indexOutOfRange(int $index): self {
        return new self(self::INDEX_OUT_OF_RANGE, [$index]);
    }

    /**
     * В коллекции нет элементов
     *
     * @return static
     */
    public static function collectionIsEmpty(): self {
        return new self(self::COLLECTION_IS_EMPTY);
    }
}

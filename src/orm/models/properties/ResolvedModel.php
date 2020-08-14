<?php declare(strict_types = 1);

/**
 * ResolvedModel.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Utils\Lex\AccessTypes;

/**
 * Реализует методы рразрешаемого свойства
 *
 * @property-read string                          $entity Имя сущности
 * @property-read array                           $keys   Массив ключей связи
 * @property      \XEAF\Rack\ORM\Core\EntityQuery $query  Запрос дя получения данных
 *
 * @package  XEAF\Rack\ORM\Models\Properties
 */
abstract class ResolvedModel extends PropertyModel {

    /**
     * Имя сущности
     * @var string
     */
    private $_entity;

    /**
     * Массив свойств внешнего ключа
     * @var array
     */
    private $_keys;

    /**
     * Запрос на получение данных коллекции
     * @var \XEAF\Rack\ORM\Core\EntityQuery
     */
    private $_query = null;

    /**
     * Конструктор класса
     *
     * @param int    $dataType   Идентификатор типа данных
     * @param string $entityName Имя сущности
     * @param array  $keys       Массив свойств внешнего ключа
     */
    public function __construct(int $dataType, string $entityName, array $keys) {
        parent::__construct($dataType, 0, 0, '', false, AccessTypes::AC_EXPANDABLE);
        $this->_entity = $entityName;
        $this->_keys   = $keys;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return null;
    }

    /**
     * Возвращает идентификатор сущности
     *
     * @return string
     */
    public function getEntity(): string {
        return $this->_entity;
    }

    /**
     * Возвращает массив ключей связи
     *
     * @return array
     */
    public function getKeys(): array {
        return $this->_keys;
    }

    /**
     * Возвращает запрос для получения данных коллекции
     *
     * @return \XEAF\Rack\ORM\Core\EntityQuery
     */
    public function getQuery(): EntityQuery {
        return $this->_query;
    }

    /**
     * Задает запрос для получения данных
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery $query Объект запроса
     *
     * @return void
     */
    public function setQuery(EntityQuery $query): void {
        $this->_query = $query;
    }
}

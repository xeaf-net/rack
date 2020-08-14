<?php declare(strict_types = 1);

/**
 * EntityModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Utils\Lex\AccessTypes;

/**
 * Реализует свойства модели сущности
 *
 * @property-read string                              $tableName            Имя таблицы БД
 * @property-read \XEAF\Rack\API\Interfaces\IKeyValue $propertyByNames      Набор свойств по имени свойства
 * @property-read \XEAF\Rack\API\Interfaces\IKeyValue $propertyByFieldNames Набор свойств по имени поля БД
 * @property bool                                     $unresolved           Признак наличия неразрешенных ссылок
 *
 * @package XEAF\Rack\ORM\Models
 */
class EntityModel extends DataModel {

    /**
     * Имя таблицы БД
     * @var string
     */
    private $_tableName;

    /**
     * Массив наименований свойст первичного ключа
     * @var array
     */
    private $_primaryKeyNames;

    /**
     * Набор свойств по имени свойства
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_propertyByNames;

    /**
     * Набор свойств по имени поля БД
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_propertyByFieldNames;

    /**
     * Признак наличия неразрешенных ссылок
     * @var bool
     */
    private $_unresolved = false;

    /**
     * Конструктор класса
     *
     * @param string $tableName  Имя таблицы БД
     * @param array  $properties Массив свойств сущности
     */
    public function __construct(string $tableName, array $properties) {
        parent::__construct();
        $this->_tableName            = $tableName;
        $this->_primaryKeyNames      = [];
        $this->_propertyByNames      = new KeyValue();
        $this->_propertyByFieldNames = new KeyValue();
        foreach ($properties as $name => $property) {
            assert($property instanceof PropertyModel);
            $this->_propertyByNames->put($name, $property);
            $this->_propertyByFieldNames->put($property->getFieldName(), $property);
            if ($property->getPrimaryKey()) {
                $this->_primaryKeyNames[] = $name;
            }
            if ($property->getAccessType() == AccessTypes::AC_EXPANDABLE) {
                $this->_unresolved = true;
            }
        }
    }

    /**
     * Возвращает имя таблицы БД
     *
     * @return string
     */
    public function getTableName(): string {
        return $this->_tableName;
    }

    /**
     * Возвращает массив имен свойств первичного ключа
     *
     * @return array
     */
    public function getPrimaryKeyNames(): array {
        return $this->_primaryKeyNames;
    }

    /**
     * Возвращает информацию о свойстве модели сущности по имени
     *
     * @param string $name Имя свойства
     *
     * @return \XEAF\Rack\ORM\Models\Properties\PropertyModel|null
     */
    public function getPropertyByName(string $name): ?PropertyModel {
        $result = $this->_propertyByNames->get($name);
        if ($result != null) {
            assert($result instanceof PropertyModel);
        }
        return $result;
    }

    /**
     * Возвращает информацию о свойстве модели сущности по имени поля БД
     *
     * @param string $fieldName Имя поля БД
     *
     * @return \XEAF\Rack\ORM\Models\Properties\PropertyModel
     */
    public function getPropertyByFiledName(string $fieldName): PropertyModel {
        $result = $this->_propertyByFieldNames->get($fieldName);
        if ($result != null) {
            assert($result instanceof PropertyModel);
        }
        return $result;
    }

    /**
     * Возвращает информацию о свойствах модели сущности по именам свойств
     *
     * @return \XEAF\Rack\API\Interfaces\IKeyValue
     */
    public function getPropertyByNames(): IKeyValue {
        return $this->_propertyByNames;
    }

    /**
     * Возвращает информацию о свойствах модели сущности по именам полей БД
     *
     * @return \XEAF\Rack\API\Interfaces\IKeyValue
     */
    public function getPropertyByFieldNames(): IKeyValue {
        return $this->_propertyByFieldNames;
    }

    /**
     * Возвращает значение признака неразрешенных ссылок
     *
     * @return bool
     */
    public function getUnresolved(): bool {
        return $this->_unresolved;
    }

    /**
     * Задает значение признака неразрешенных ссыолк
     *
     * @param bool $unresolved Значение признака неразрешенных ссылок
     *
     * @return void
     */
    public function setUnresolved(bool $unresolved): void {
        $this->_unresolved = $unresolved;
    }
}

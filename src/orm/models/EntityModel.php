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
use XEAF\Rack\ORM\Core\EntityManager;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;

/**
 * Реализует свойства модели сущности
 *
 * @property-read string                              $tableName            Имя таблицы БД
 * @property-read \XEAF\Rack\API\Interfaces\IKeyValue $propertyByNames      Набор свойств по имени свойства
 * @property-read \XEAF\Rack\API\Interfaces\IKeyValue $propertyByFieldNames Набор свойств по имени поля БД
 *
 * @property \XEAF\Rack\ORM\Core\EntityManager|null   $entityManager        Менеджер сущностей
 *
 * @package XEAF\Rack\ORM\Models
 */
class EntityModel extends DataModel {

    /**
     * Имя таблицы БД
     * @var string
     */
    private string $_tableName;

    /**
     * Массив наименований свойст первичного ключа
     * @var array
     */
    private array $_primaryKeyNames;

    /**
     * Набор свойств по имени свойства
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private IKeyValue $_propertyByNames;

    /**
     * Набор свойств по имени поля БД
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private IKeyValue $_propertyByFieldNames;

    /**
     * Менеджер сущностей
     * @var \XEAF\Rack\ORM\Core\EntityManager|null
     */
    private ?EntityManager $_entityManager = null;

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
     * Возвращает объект менеджера сущностей
     *
     * @return \XEAF\Rack\ORM\Core\EntityManager|null
     */
    public function getEntityManager(): ?EntityManager {
        return $this->_entityManager;
    }

    /**
     * Задает объект менеджера сущностей
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager|null $entityManager Менеджер сущностей
     *
     * @return void
     */
    public function setEntityManager(?EntityManager $entityManager): void {
        $this->_entityManager = $entityManager;
    }

    /**
     * Возвращает признак существования свойства
     *
     * @param string $name Имя свойства
     *
     * @return bool
     */
    public function propertyExists(string $name): bool {
        return $this->_propertyByNames->exists($name);
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
}

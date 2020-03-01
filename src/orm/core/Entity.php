<?php

/**
 * Entity.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Core;

use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Utils\Formatter;
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\Properties\EnumProperty;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Utils\EntityStorage;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы объекта сущности
 *
 * @package XEAF\Rack\ORM\Core
 */
abstract class Entity extends DataObject {

    /**
     * Ссылка на объект модели сущности
     * @var \XEAF\Rack\ORM\Models\EntityModel|null
     */
    private $_model = null;

    /**
     * Вычисленное значение первичного ключа
     * @var string|null
     */
    private $_primaryKey = null;

    /**
     * Вычисленное значение ключа слежения
     * @var string|null
     */
    private $_entityWatchingId = null;

    /**
     * Конструктор класса
     *
     * @param array $data Данные инициализаии
     */
    public function __construct(array $data = []) {
        $this->initializeModel();
        parent::__construct($this->createInitData($data));
    }

    /**
     * Инициализирует объект модели сущности
     *
     * @return void
     */
    protected function initializeModel(): void {
        $storage      = EntityStorage::getInstance();
        $className    = $this->getClassName();
        $this->_model = $storage->getModel($className);
        if ($this->_model == null) {
            $this->_model = $this->createEntityModel();
            $storage->putModel($className, $this->_model);
        }
    }

    /**
     * Возвращает массив данных инициализации
     *
     * @param array $data Исходные данные инициализации
     *
     * @return array
     */
    protected function createInitData(array $data): array {
        $result     = [];
        $properties = $this->_model->getPropertyByNames();
        foreach ($properties as $name => $property) {
            if (array_key_exists($name, $data)) {
                $result[$name] = $data[$name];
            } else {
                assert($property instanceof PropertyModel);
                $result[$name] = $property->getDefaultValue();
            }
        }
        return $result;
    }

    /**
     * Возвращает информацию о модели сущности
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel
     */
    public function getModel(): EntityModel {
        return $this->_model;
    }

    /**
     * Возвращает значение первичного ключа
     *
     * @return string|null
     */
    public function getPrimaryKey(): ?string {
        if ($this->_primaryKey == null) {
            $result = [];
            foreach ($this->_model->getPrimaryKeyNames() as $name) {
                $value = $this->{$name};
                if ($value == null) {
                    return null;
                } else {
                    $result[] = $value;
                }
            }
            $this->_primaryKey = implode(':', $result);
        }
        return $this->_primaryKey;
    }

    /**
     * Возвращает идентификатор слежения
     *
     * @return string|null
     */
    public function getEntityWatchingId(): ?string {
        if ($this->_entityWatchingId == null) {
            $primaryKey = $this->getPrimaryKey();
            if ($primaryKey != null) {
                $this->_entityWatchingId = md5($this->getClassName() . ':' . $primaryKey);
            }
        }
        return $this->_entityWatchingId;
    }

    /**
     * Возвращает массив отформатированных значений свойств
     *
     * @param array $map Карта возвращаемых свойств
     *
     * @return array
     */
    public function toFormattedArray(array $map = []): array {
        $result = parent::toArray($map);
        $model  = $this->getModel();
        $names  = array_keys($result);
        $fmt    = Formatter::getInstance();
        foreach ($names as $name) {
            $type = $model->getPropertyByName($name)->getDataType();
            switch ($type) {
                case DataTypes::DT_DATE:
                    $result[$name] = $fmt->formatDate($result[$name]);
                    break;
                case DataTypes::DT_DATETIME:
                    $result[$name] = $fmt->formatDateTime($result[$name]);
                    break;
            }
        }
        return $result;
    }

    /**
     * Вызывается перед сохранением значения сущности
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager $entityManager Менеджер сущностей
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforePersist(EntityManager $entityManager): void {
        foreach ($this->getModel()->getProperties() as $name => $property) {
            assert($property instanceof PropertyModel);
            switch ($property->getDataType()) {
                case DataTypes::DT_ENUM:
                    $this->checkEnumValue($name, $property);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Проверяет корректность значения свойтсва типа перечисление
     *
     * @param string                                        $name
     * @param \XEAF\Rack\ORM\Models\Properties\EnumProperty $property
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function checkEnumValue(string $name, EnumProperty $property): void {
        $value = $this->$name;
        $enums = $property->enumValues();
        if (!in_array($value, $enums)) {
            throw EntityException::invalidEnumValue($value);
        }
    }

    /**
     * вызывается после сохранения значения сущности
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager $entityManager Менеджер сущностей
     *
     * @return void
     */
    public function afterPersist(EntityManager $entityManager): void {
        // Ничего не делать
    }

    /**
     * Вызывается перед удалением значения сущности
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager $entityManager Менеджер сущностей
     *
     * @return void
     */
    public function beforeDelete(EntityManager $entityManager): void {
        // Ничего не делать
    }

    /**
     * вызывается после сохранения значения сущности
     *
     * @param \XEAF\Rack\ORM\Core\EntityManager $entityManager Менеджер сущностей
     *
     * @return void
     */
    public function afterDelete(EntityManager $entityManager): void {
        // Ничего не делать
    }

    /**
     * Создает новый объект модели сужности
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel
     */
    abstract protected function createEntityModel(): EntityModel;
}

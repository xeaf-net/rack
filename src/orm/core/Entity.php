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
use XEAF\Rack\ORM\Models\Properties\ArrayProperty;
use XEAF\Rack\ORM\Models\Properties\BoolProperty;
use XEAF\Rack\ORM\Models\Properties\DateProperty;
use XEAF\Rack\ORM\Models\Properties\DateTimeProperty;
use XEAF\Rack\ORM\Models\Properties\EnumProperty;
use XEAF\Rack\ORM\Models\Properties\IntegerProperty;
use XEAF\Rack\ORM\Models\Properties\NumericProperty;
use XEAF\Rack\ORM\Models\Properties\ObjectProperty;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Models\Properties\StringProperty;
use XEAF\Rack\ORM\Models\Properties\TextProperty;
use XEAF\Rack\ORM\Models\Properties\UUIDProperty;
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
                case DataTypes::DT_OBJECT:
                    $result[$name] = (array)$result[$name];
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
        foreach ($this->getModel()->getPropertyByNames() as $name => $property) {
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

    /**
     * Создает описание свойства типа UUID
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\UUIDProperty
     */
    public static function uuid(string $fieldName, bool $primaryKey = false, bool $readOnly = false): UUIDProperty {
        return new UUIDProperty($fieldName, $primaryKey, $readOnly);
    }

    /**
     * Создает описание свойства строкового типа
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $length     Длина
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\StringProperty
     */
    public static function string(string $fieldName, int $length = 255, bool $primaryKey = false, bool $readOnly = false): StringProperty {
        return new StringProperty($fieldName, $length, $primaryKey, $readOnly);
    }

    /**
     * Создает описание свойства строкового типа для текста
     *
     * @param string $fieldName Имя поля БД
     * @param bool   $readOnly  Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\TextProperty
     */
    public static function text(string $fieldName, bool $readOnly = false): TextProperty {
        return new TextProperty($fieldName, $readOnly);
    }

    /**
     * Создает описание свойства целочисленного типа
     *
     * @param string $fieldName     Имя поля БД
     * @param bool   $primaryKey    Признак первичного ключа
     * @param bool   $readOnly      Признак поля только для чтения
     * @param bool   $autoIncrement Признак поля с автоинкрементом
     *
     * @return IntegerProperty
     */
    public static function integer(string $fieldName, bool $primaryKey = false, bool $readOnly = false, bool $autoIncrement = false): IntegerProperty {
        return new IntegerProperty($fieldName, $primaryKey, $readOnly, $autoIncrement);
    }

    /**
     * Создает описание свойства действительного типа
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $size       Размер
     * @param int    $precision  Точность
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\NumericProperty
     */
    public static function numeric(string $fieldName, int $size = 15, int $precision = 2, bool $primaryKey = false, bool $readOnly = false): NumericProperty {
        return new NumericProperty($fieldName, $size, $precision, $primaryKey, $readOnly);
    }

    /**
     * Создает описание свойства типа календарной даты
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\DateProperty
     */
    public static function date(string $fieldName, bool $primaryKey = false, bool $readOnly = false): DateProperty {
        return new DateProperty($fieldName, $primaryKey, $readOnly);
    }

    /**
     * Создает описание свойства типа календарных даты и времени
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\DateTimeProperty
     */
    public static function dateTime(string $fieldName, bool $primaryKey = false, bool $readOnly = false): DateTimeProperty {
        return new DateTimeProperty($fieldName, $primaryKey, $readOnly);
    }

    /**
     * Создает описание свойства логического типа
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param bool   $readOnly   Признак поля только для чтения
     *
     * @return BoolProperty
     */
    public static function bool(string $fieldName, bool $primaryKey = false, bool $readOnly = false): BoolProperty {
        return new BoolProperty($fieldName, $primaryKey, $readOnly);
    }

    /**
     * Создает описания свойства типа перечисление
     *
     * @param string $fieldName Имя поля БД
     * @param array  $enums     Возможные значения свойства
     * @param bool   $readOnly  Признак полч только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\EnumProperty
     */
    public static function enum(string $fieldName, array $enums, bool $readOnly = false): EnumProperty {
        return new EnumProperty($fieldName, $enums, $readOnly);
    }

    /**
     * Создает описание свойства типа массив
     *
     * @param string $fieldName Имя поля БД
     * @param bool   $readOnly  Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\ArrayProperty
     */
    public static function array(string $fieldName, bool $readOnly = false): ArrayProperty {
        return new ArrayProperty($fieldName, $readOnly);
    }

    /**
     * Создает описание свойства типа объект
     *
     * @param string $fieldName Имя поля БД
     * @param bool   $readOnly  Признак поля только для чтения
     *
     * @return \XEAF\Rack\ORM\Models\Properties\ObjectProperty
     */
    public static function object(string $fieldName, bool $readOnly = false): ObjectProperty {
        return new ObjectProperty($fieldName, $readOnly);
    }
}

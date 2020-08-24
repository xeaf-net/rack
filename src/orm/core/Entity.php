<?php declare(strict_types = 1);

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
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Utils\Formatter;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\ORM\Models\EntityModel;
use XEAF\Rack\ORM\Models\Properties\ArrayProperty;
use XEAF\Rack\ORM\Models\Properties\BoolProperty;
use XEAF\Rack\ORM\Models\Properties\DateProperty;
use XEAF\Rack\ORM\Models\Properties\DateTimeProperty;
use XEAF\Rack\ORM\Models\Properties\EnumProperty;
use XEAF\Rack\ORM\Models\Properties\IntegerProperty;
use XEAF\Rack\ORM\Models\Properties\ManyToOneProperty;
use XEAF\Rack\ORM\Models\Properties\NumericProperty;
use XEAF\Rack\ORM\Models\Properties\ObjectProperty;
use XEAF\Rack\ORM\Models\Properties\OneToManyProperty;
use XEAF\Rack\ORM\Models\Properties\PropertyModel;
use XEAF\Rack\ORM\Models\Properties\StringProperty;
use XEAF\Rack\ORM\Models\Properties\TextProperty;
use XEAF\Rack\ORM\Models\Properties\UUIDProperty;
use XEAF\Rack\ORM\Models\RelationValue;
use XEAF\Rack\ORM\Utils\EntityStorage;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\AccessTypes;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;
use XEAF\Rack\ORM\Utils\Resolver;

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
     * Значения свойств отношений
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_relationValues;

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
        $this->_relationValues = new KeyValue();
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
            assert($property instanceof PropertyModel);
            if (!$property->getIsRelation()) {
                if (array_key_exists($name, $data)) {
                    $result[$name] = $data[$name];
                } else {
                    $result[$name] = $property->getDefaultValue();
                }
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
                $value = $this->{$name} ?? null;
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
     * Возвращает значение свойства отношения
     *
     * @param string $name Имя свойства
     *
     * @return \XEAF\Rack\ORM\Models\RelationValue|null
     */
    public function getRelationValue(string $name): ?RelationValue {
        $result = $this->_relationValues->get($name);
        if ($result) {
            assert($result instanceof RelationValue);
        }
        return $result;
    }

    /**
     * Задает значение свойства отношения
     *
     * @param string                              $name  Имя свойства
     * @param \XEAF\Rack\ORM\Models\RelationValue $value Модель значения
     *
     * @return void
     */
    public function setRelationValue(string $name, RelationValue $value): void {
        $this->_relationValues->put($name, $value);
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
     * @inheritDoc
     *
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function __get(string $name) {
        $value = $this->getRelationValue($name);
        if ($value) {
            if (!$value->getIsResolved()) {
                $resolver = Resolver::getInstance();
                $value    = $resolver->resolveEagerValue($this, $value->getWithModel());
            }
            return $value->getValue();
        }
        return parent::__get($name);
    }

    /**
     * @inheritDoc
     */
    public function toArray(array $map = []): array {
        $result     = parent::toArray($map);
        $empty      = count($map) == 0;
        $properties = $this->_model->getPropertyByNames();
        foreach ($properties as $name => $property) {
            if ($empty || in_array($name, $map)) {
                assert($property instanceof PropertyModel);
                if (!$property->getIsRelation()) {
                    switch ($property->getDataType()) {
                        case DataTypes::DT_INTEGER:
                        case DataTypes::DT_DATE:
                        case DataTypes::DT_DATETIME:
                            $result[$name] = (int)$result[$name];
                            break;
                        case DataTypes::DT_BOOL:
                            $result[$name] = (bool)$result[$name];
                            break;
                        case DataTypes::DT_NUMERIC:
                            $result[$name] = (float)$result[$name];
                            break;
                    }
                } elseif ($this->_relationValues->exists($name)) {
                    $value = $this->{$name};
                    if ($value instanceof ICollection) {
                        $result[$name] = $value->toArray();
                    } else {
                        $result[$name] = $value;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Задает значения свойств сущности из массива
     *
     * @param array $data Массив значений
     *
     * @return void
     */
    public function assign(array $data): void {
        foreach ($data as $name => $value) {
            $property = $this->_model->getPropertyByName($name);
            if ($property != null) {
                $this->{$name} = $value;
            }
        }
    }

    /**
     * Задает значения свойств из параметров вызова приложения
     *
     * @return void
     */
    public function assignParameters(): void {
        $parameters = Parameters::getInstance();
        $properties = $this->_model->getPropertyByNames();
        foreach ($properties as $name => $property) {
            assert($property instanceof PropertyModel);
            if (!$property->getIsRelation() && $parameters->exists($name)) {
                $value = null;
                switch ($property->dataType) {
                    case DataTypes::DT_INTEGER:
                        $value = $parameters->getInteger($name, 0);
                        break;
                    case DataTypes::DT_DATE:
                    case DataTypes::DT_DATETIME:
                        $value = $parameters->getInteger($name);
                        break;
                    case DataTypes::DT_BOOL:
                        $value = $parameters->getBool($name);
                        break;
                    case DataTypes::DT_NUMERIC:
                        $value = $parameters->getFloat($name, 0.00);
                        break;
                    default:
                        $value = $parameters->getString($name);
                        break;
                }
                $this->{$name} = $value;
            }
        }
    }

    /**
     * Возвращает массив отформатированных значений свойств
     *
     * @param array $map Карта возвращаемых свойств
     *
     * @return array
     */
    public function toFormattedArray(array $map = []): array {
        $result = $this->toArray($map);
        $model  = $this->getModel();
        $names  = array_keys($result);
        $fmt    = Formatter::getInstance();
        foreach ($names as $name) {
            $property = $model->getPropertyByName($name);
            if ($property != null) {
                $type = $property->getDataType();
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
                    assert($property instanceof EnumProperty);
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
        $value = $this->{$name};
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
     * Вызывается после сохранения значения сущности
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
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\UUIDProperty
     */
    protected static function uuid(string $fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT): UUIDProperty {
        return new UUIDProperty($fieldName, $primaryKey, $accessType);
    }

    /**
     * Создает описание свойства строкового типа
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $length     Длина
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\StringProperty
     */
    protected static function string(string $fieldName, int $length = 255, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT): StringProperty {
        return new StringProperty($fieldName, $length, $primaryKey, $accessType);
    }

    /**
     * Создает описание свойства строкового типа для текста
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\TextProperty
     */
    protected static function text(string $fieldName, int $accessType = AccessTypes::AC_DEFAULT): TextProperty {
        return new TextProperty($fieldName, $accessType);
    }

    /**
     * Создает описание свойства целочисленного типа
     *
     * @param string $fieldName     Имя поля БД
     * @param bool   $primaryKey    Признак первичного ключа
     * @param int    $accessType    Определение доступа
     * @param bool   $autoIncrement Признак поля с автоинкрементом
     *
     * @return IntegerProperty
     */
    protected static function integer(string $fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT, bool $autoIncrement = false): IntegerProperty {
        return new IntegerProperty($fieldName, $primaryKey, $accessType, $autoIncrement);
    }

    /**
     * Создает описание свойства действительного типа
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $size       Размер
     * @param int    $precision  Точность
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\NumericProperty
     */
    protected static function numeric(string $fieldName, int $size = 15, int $precision = 2, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT): NumericProperty {
        return new NumericProperty($fieldName, $size, $precision, $primaryKey, $accessType);
    }

    /**
     * Создает описание свойства типа календарной даты
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\DateProperty
     */
    protected static function date(string $fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT): DateProperty {
        return new DateProperty($fieldName, $primaryKey, $accessType);
    }

    /**
     * Создает описание свойства типа календарных даты и времени
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\DateTimeProperty
     */
    protected static function dateTime(string $fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT): DateTimeProperty {
        return new DateTimeProperty($fieldName, $primaryKey, $accessType);
    }

    /**
     * Создает описание свойства логического типа
     *
     * @param string $fieldName  Имя поля БД
     * @param bool   $primaryKey Признак первичного ключа
     * @param int    $accessType Определение доступа
     *
     * @return BoolProperty
     */
    protected static function bool(string $fieldName, bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT): BoolProperty {
        return new BoolProperty($fieldName, $primaryKey, $accessType);
    }

    /**
     * Создает описания свойства типа Перечисление
     *
     * @param string $fieldName  Имя поля БД
     * @param array  $enums      Возможные значения свойства
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\EnumProperty
     */
    protected static function enum(string $fieldName, array $enums, int $accessType = AccessTypes::AC_DEFAULT): EnumProperty {
        return new EnumProperty($fieldName, $enums, $accessType);
    }

    /**
     * Создает описание свойства типа Массив
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\ArrayProperty
     */
    protected static function array(string $fieldName, int $accessType = AccessTypes::AC_DEFAULT): ArrayProperty {
        return new ArrayProperty($fieldName, $accessType);
    }

    /**
     * Создает описание свойства типа Объект
     *
     * @param string $fieldName  Имя поля БД
     * @param int    $accessType Определение доступа
     *
     * @return \XEAF\Rack\ORM\Models\Properties\ObjectProperty
     */
    protected static function object(string $fieldName, int $accessType = AccessTypes::AC_DEFAULT): ObjectProperty {
        return new ObjectProperty($fieldName, $accessType);
    }

    /**
     * Создает описание свойства отношения Один ко многим
     *
     * @param string $entity Имя сущности
     * @param array  $links  Свойства связи
     *
     * @return \XEAF\Rack\ORM\Models\Properties\OneToManyProperty
     */
    protected static function oneToMany(string $entity, array $links): OneToManyProperty {
        return new OneToManyProperty($entity, $links);
    }

    /**
     * Создает описание свойства отношения Многин к одному
     *
     * @param string $entity Имя сущности
     * @param array  $links  Свойства связи
     *
     * @return \XEAF\Rack\ORM\Models\Properties\ManyToOneProperty
     */
    protected static function manyToOne(string $entity, array $links): ManyToOneProperty {
        return new ManyToOneProperty($entity, $links);
    }

    /**
     * Создает описание свойства даты и времени создания записи
     *
     * @param string $fieldName Имя поля БД
     *
     * @return \XEAF\Rack\ORM\Models\Properties\DateTimeProperty
     */
    protected static function createdTime(string $fieldName): DateTimeProperty {
        $accessType = AccessTypes::AC_READABLE;
        return new DateTimeProperty($fieldName, false, $accessType);
    }

    /**
     * Создает описание свойства даты и времени изменния записи
     *
     * @param string $fieldName Имя поля БД
     *
     * @return \XEAF\Rack\ORM\Models\Properties\DateTimeProperty
     */
    protected static function modifiedTime(string $fieldName): DateTimeProperty {
        $accessType = AccessTypes::AC_READABLE | AccessTypes::AC_UPDATABLE;
        return new DateTimeProperty($fieldName, false, $accessType);
    }
}

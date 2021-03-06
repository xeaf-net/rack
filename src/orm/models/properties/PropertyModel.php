<?php declare(strict_types = 1);

/**
 * PropertyModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Utils\Lex\AccessTypes;

/**
 * Реализует методы свойства сущности
 *
 * @property-read int    $dataType      Тип данных
 * @property-read string $fieldName     Имя поля БД
 * @property-read int    $size          Размер
 * @property-read int    $precision     Точность
 * @property-read bool   $primaryKey    Признак первичного ключа
 * @property-read int    $accessType    Определение доступа
 * @property-read bool   $autoIncrement Признак поля с автоинкрементом
 * @property-read mixed  $defaultValue  Значение по умолчанию
 *
 * @property-read bool   $isReadable    Признак читаемого свойства
 * @property-read bool   $isInsertable  Признак вставляемого свойства
 * @property-read bool   $isUpdatable   Признак изменяемого свойства
 * @property-read bool   $isRelation    Признак свойства отношения
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
abstract class PropertyModel extends DataModel {

    /**
     * Тип данных свойства
     * @var int
     */
    private int $_dataType;

    /**
     * Размер
     * @var int
     */
    private int $_size;

    /**
     * Точность
     * @var int
     */
    private int $_precision;

    /**
     * Имя поля таблицы БД
     * @var string
     */
    private string $_fieldName;

    /**
     * Признак первичного ключа
     * @var bool
     */
    private bool $_primaryKey;

    /**
     * Определение доступа
     * @var int
     */
    private int $_accessType;

    /**
     * Признак поля с автоинкрементом
     * @var bool
     */
    private bool $_autoIncrement;

    /**
     * Конструктор класса
     *
     * @param int    $dataType      Тип данных
     * @param int    $size          Размер
     * @param int    $precision     Точность
     * @param string $fieldName     Имя поля таблицы БД
     * @param bool   $primaryKey    Признак первичного ключа
     * @param int    $accessType    Определение доступа
     * @param bool   $autoIncrement Признак поля с автоинкрементом
     */
    public function __construct(int $dataType, int $size, int $precision, string $fieldName = '', bool $primaryKey = false, int $accessType = AccessTypes::AC_DEFAULT, bool $autoIncrement = false) {
        parent::__construct();
        $this->_dataType      = $dataType;
        $this->_size          = $size;
        $this->_precision     = $precision;
        $this->_fieldName     = $fieldName;
        $this->_primaryKey    = $primaryKey;
        $this->_accessType    = $accessType;
        $this->_autoIncrement = $autoIncrement;
    }

    /**
     * Возвращает тип данных
     *
     * @return int
     */
    public function getDataType(): int {
        return $this->_dataType;
    }

    /**
     * Возвращает размер
     *
     * @return int
     */
    public function getSize(): int {
        return $this->_size;
    }

    /**
     * Возвращает точность
     *
     * @return int
     */
    public function getPrecision(): int {
        return $this->_precision;
    }

    /**
     * Возвращает имя поля БД
     *
     * @return string
     */
    public function getFieldName(): string {
        return $this->_fieldName;
    }

    /**
     * Возвращает признак поля первичного ключа
     *
     * @return bool
     */
    public function getPrimaryKey(): bool {
        return $this->_primaryKey;
    }

    /**
     * Возвращает определение доступа к свойству
     *
     * @return int
     */
    public function getAccessType(): int {
        return $this->getAutoIncrement() ? AccessTypes::AC_READABLE : $this->_accessType;
    }

    /**
     * Возвращает признак поля с автоинкрементом
     *
     * @return bool
     */
    public function getAutoIncrement(): bool {
        return $this->_autoIncrement;
    }

    /**
     * Возвращает признак читаемого свойства
     *
     * @return bool
     */
    public function getIsReadable(): bool {
        return ($this->getAccessType() & AccessTypes::AC_READABLE) > 0;
    }

    /**
     * Возвращает признак вставляемого свойства
     *
     * @return bool
     */
    public function getIsInsertable(): bool {
        return ($this->getAccessType() & AccessTypes::AC_INSERTABLE) > 0;
    }

    /**
     * Возвращает признак обновляемого свойства
     *
     * @return bool
     */
    public function getIsUpdatable(): bool {
        return ($this->getAccessType() & AccessTypes::AC_UPDATABLE) > 0;
    }

    /**
     * Возвращает признак свойства отношения
     *
     * @return bool
     */
    public function getIsRelation(): bool {
        return $this->getAccessType() == AccessTypes::AC_RELATION;
    }

    /**
     * Возвращает значение по умолчанию
     *
     * @return mixed|null
     */
    abstract public function getDefaultValue();
}

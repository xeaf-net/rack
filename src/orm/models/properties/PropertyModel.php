<?php

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

/**
 * Реализует методы свойства сущности
 *
 * @property-read int    $dataType      Тип данных
 * @property-read string $fieldName     Имя поля БД
 * @property-read int    $size          Размер
 * @property-read int    $precision     Точность
 * @property-read bool   $primaryKey    Признак первичного ключа
 * @property-read bool   $readOnly      Признак поля только для чтения
 * @property-read bool   $autoIncrement Признак поля с автоинкрементом
 * @property-read mixed  $defaultValue  Значение по умолчанию
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
abstract class PropertyModel extends DataModel {

    /**
     * Тип данных свойства
     * @var int
     */
    private $_dataType = 0;

    /**
     * Размер
     * @var int
     */
    private $_size = 0;

    /**
     * Точность
     * @var int
     */
    private $_precision = 0;

    /**
     * Имя поля таблицы БД
     * @var string
     */
    private $_fieldName = '';

    /**
     * Признак первичного ключа
     * @var bool
     */
    private $_primaryKey = false;

    /**
     * Признак поля только для чтения
     * @var bool
     */
    private $_readOnly = false;

    /**
     * Признак поля с автоинкрементом
     * @var bool
     */
    private $_autoIncrement = false;

    /**
     * Конструктор класса
     *
     * @param int    $dataType      Тип данных
     * @param int    $size          Размер
     * @param int    $precision     Точность
     * @param string $fieldName     Имя поля таблицы БД
     * @param bool   $primaryKey    Признак первичного ключа
     * @param bool   $readOnly      Признак поля только для чтения
     * @param bool   $autoIncrement Признак поля с автоинкрементом
     */
    public function __construct(int $dataType, int $size, int $precision, string $fieldName = '', bool $primaryKey = false, bool $readOnly = false, bool $autoIncrement = false) {
        parent::__construct();
        $this->_dataType      = $dataType;
        $this->_size          = $size;
        $this->_precision     = $precision;
        $this->_fieldName     = $fieldName;
        $this->_primaryKey    = $primaryKey;
        $this->_readOnly      = $readOnly;
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
     * @inheritDoc
     */
    public function getReadOnly(): bool {
        return $this->_readOnly || $this->getAutoIncrement();
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
     * Возвращает значение по умолчанию
     *
     * @return mixed|null
     */
    abstract public function getDefaultValue();
}

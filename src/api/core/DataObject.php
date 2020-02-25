<?php

/**
 * DataObject.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Interfaces\IKeyValue;

/**
 * Реализует базовые методы объектов данных
 *
 * @property-read string $className  Идентификатор класса объекта
 * @property-read array  $properties Идентификаторы свойств
 *
 * @package XEAF\Rack\API\Core
 */
class DataObject extends StdObject {

    /**
     * Префикс метода чтения значения свойства
     */
    private const GETTER_PREFIX = 'get';

    /**
     * Префикс метода задания значения свойства
     */
    private const SETTER_PREFIX = 'set';

    /**
     * Префикс переменной свойства
     */
    private const PROPERTY_VAR_PREFIX = '_';

    /**
     * Уровень вложенности инициализации
     * @var int
     */
    private static $initialization = 0;

    /**
     * Конструктор класса
     *
     * @param array $data Данные инициализации
     */
    public function __construct(array $data = []) {
        self::$initialization++;
        foreach ($data as $key => $value) {
            $this->{$key} = is_array($value) ? self::fromArray($value) : $value;
        }
        self::$initialization--;
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, $value): void {
        $methodName = self::propertySetter($name);
        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
        } else {
            $this->undefinedSetter($name, $value);
        }
    }

    /**
     * Возвращает массив идентификаторов свойств объекта
     *
     * @return array
     */
    public function getProperties(): array {
        $result = [];
        foreach ($this as $key => $value) {
            $result[] = ltrim($key, self::PROPERTY_VAR_PREFIX);
        }
        return $result;
    }

    /**
     * Возвращает представление данных объекта в виде массива
     *
     * @param array $map Карта свойств
     *
     * @return array
     */
    public function toArray(array $map = []): array {
        $result     = [];
        $properties = $this->getProperties();
        foreach ($properties as $property) {
            if (count($map) == 0 || in_array($property, $map)) {
                $data = $this->{$property};
                if ($data instanceof ICollection) {
                    $subMap            = $map['property'] ?? [];
                    $result[$property] = $data->toArray($subMap);
                } else if ($data instanceof DataObject) {
                    $subMap            = $map['property'] ?? [];
                    $result[$property] = $data->toArray($subMap);
                } else if ($data instanceof IKeyValue) {
                    $result[$property] = $data->toArray();
                } else {
                    $result[$property] = $data;
                }
            }
        }
        return $result;
    }

    /**
     * Создает новый объект по данным инициализации
     *
     * @param array $data Данные иинициализации
     *
     * @return static
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }

    /**
     * Возвращает идентификатор метода чтения значения свойства
     *
     * @param string $name Имя свойства
     *
     * @return string
     */
    private static function propertyGetter(string $name): string {
        return self::GETTER_PREFIX . ucfirst($name);
    }

    /**
     * @inheritDoc
     */
    protected function undefinedGetter(string $name) {
        $methodName = self::propertyGetter($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }
        return parent::undefinedGetter($name);
    }

    /**
     * @inheritDoc
     */
    protected function undefinedSetter(string $name, $value): void {
        if (self::$initialization > 0) {
            $this->{$name} = $value;
        } else {
            parent::undefinedSetter($name, $value);
        }
    }

    /**
     * Возвращает идентификатор метода задания значения свойства
     *
     * @param string $name Имя свойства
     *
     * @return string
     */
    private static function propertySetter(string $name): string {
        return self::SETTER_PREFIX . ucfirst($name);
    }
}

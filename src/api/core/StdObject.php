<?php

/**
 * StdObject.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Utils\Exceptions\CoreException;
use XEAF\Rack\API\Utils\Reflection;

/**
 * Базовый класс для всех классов объектов проекта
 *
 * @property-read string $className Имя класса объекта
 *
 * @package XEAF\Rack\API\Core
 */
abstract class StdObject {

    /**
     * Возвращает идентификатор класса объекта
     *
     * @return string
     */
    public function getClassName(): string {
        return get_class($this);
    }

    /**
     * Возвращает имя файла реализации класса
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function getClassFileName(): string {
        $reflection = Reflection::getInstance();
        return $reflection->classFileName($this->getClassName());
    }

    /**
     * Возвращает значение неизвестного свойства объекта
     *
     * @param string $name Имя свойства
     *
     * @return mixed
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function __get(string $name) {
        return $this->undefinedGetter($name);
    }

    /**
     * Задает значение неизвестного свойства объекта
     *
     * @param string $name  Имя свойства
     * @param mixed  $value Значение
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function __set(string $name, $value): void {
        $this->undefinedSetter($name, $value);
    }

    /**
     * Обрабатывает обращение к неизвестному методу
     *
     * @param string $name      Имя метода
     * @param array  $arguments Аргументы вызова метода
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function __call(string $name, array $arguments) {
        throw CoreException::callToUnknownMethod($this->getClassName(), $name);
    }

    /**
     * Обрабатывает вызов неизвестного метода чтения значения свойства
     *
     * @param string $name Имя свойства
     *
     * @return mixed
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function undefinedGetter(string $name) {
        throw CoreException::propertyIsNotReadable($this->getClassName(), $name);
    }

    /**
     * Обрабатывает вызов неизвестного метода задания значения свойства
     *
     * @param string $name  Имя свойства
     * @param mixed  $value Значение
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     *
     * @noinspection PhpUnusedParameterInspection
     */
    protected function undefinedSetter(string $name, $value): void {
        throw CoreException::propertyIsNotWritable($this->getClassName(), $name);
    }
}

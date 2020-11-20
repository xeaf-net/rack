<?php declare(strict_types = 1);

/**
 * IReflection.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы работы с отражениями
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IReflection extends IFactoryObject {

    /**
     * Возвращает сокращенное имя класса
     *
     * @param string $className Имя класса
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function shortClassName(string $className): string;

    /**
     * Возвращает имя файла реализации класса
     *
     * @param string $className Имя класса
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function classFileName(string $className): string;

    /**
     * Возвращает имя класс текущего исполняемого модуля
     *
     * @return string
     */
    public function moduleClassName(): string;

    /**
     * Возвращает имя файла класса текущего исполняемого модуля
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function moduleClassFileName(): string;

    /**
     * Создает объект dependency injection
     *
     * @param string $className Имя класса
     *
     * @return mixed
     */
    public function createInjectable(string $className);

    /**
     * Возвращает результат исполнения метода dependency injection
     *
     * @param object $object Объект класса DI
     * @param string $method Имя метода
     *
     * @return mixed
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function returnInjectable(object $object, string $method);
}

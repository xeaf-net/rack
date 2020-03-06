<?php declare(strict_types = 1);

/**
 * IActionArgs.php
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
 * Описывает методы контейнера параметров вызова приложения
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IActionArgs extends IFactoryObject {

    /**
     * Возвращает имя метода
     *
     * @return string
     */
    public function getMethodName(): string;

    /**
     * Возвращает имя узла маршрута
     *
     * @return string
     */
    public function getActionNode(): string;

    /**
     * Возвращает путь действия
     *
     * @return string|null
     */
    public function getActionPath(): ?string;

    /**
     * Врзвращает режим вызова действия
     *
     * @return string|null
     */
    public function getActionMode(): ?string;

    /**
     * Возвращает идентификатор объекта действия
     *
     * @return string|null
     */
    public function getObjectId(): ?string;

    /**
     * Возвращает путь объекта действия
     *
     * @return string|null
     */
    public function getObjectPath(): ?string;

    /**
     * Возвращает полный URL вызова действия
     *
     * @return string|null
     */
    public function getCurrentURL(): ?string;

    /**
     * Возвращает значение параметра
     *
     * @param string     $name         Имя параметра
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     */
    public function get(string $name, $defaultValue = null);

    /**
     * Возвращает значение параметра заголовка
     *
     * @param string     $name         Имя параметра
     * @param mixed|null $defaultValue Значение по умолчанию
     *
     * @return mixed
     */
    public function getHeader(string $name, $defaultValue = null);

    /**
     * Возвращает список параметров заголовков
     *
     * @return array
     */
    public function getHeaders(): array;
}

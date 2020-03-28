<?php declare(strict_types = 1);

/**
 * IVersions.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы получения информации о версии
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IVersions extends IFactoryObject {

    /**
     * Возвращает версию приложения
     *
     * @return string
     */
    public function getAppVersion(): string;

    /**
     * Возвращает версию библиотеки
     *
     * @return string
     */
    public function getRackVersion(): string;
}

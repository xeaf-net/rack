<?php

/**
 * IAssets.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы регистратора ресурсов
 *
 * @package  XEAF\Rack\Db\Utils
 */
interface IAssets extends IFactoryObject {

    /**
     * Возвращает массив определенных папок публичных ресурсов
     *
     * @return array
     */
    function getPublicFolders(): array;

    /**
     * Регистрирует папку публичного ресурса
     *
     * @param string $folderPath Путь к папке
     *
     * @return void
     */
    function registerPublicFolder(string $folderPath): void;

    /**
     * Возвращает директорию скомпилированных ресурсов
     *
     * @return string
     */
    function getDistRootFolder(): string;

    /**
     * Возвращает директорию скомпилированных публичных ресурсов
     *
     * @param string $fileType Тип файла
     *
     * @return string
     */
    function getDistPublicFolder(string $fileType): string;

        /**
     * Возвращает путь к папке модулей Node.js
     *
     * @return string
     */
    function getNodeModulesPath(): string;
}

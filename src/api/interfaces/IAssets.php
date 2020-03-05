<?php declare(strict_types = 1);

/**
 * IAssets.php
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
    public function getPublicFolders(): array;

    /**
     * Регистрирует папку публичного ресурса
     *
     * @param string $folderPath Путь к папке
     *
     * @return void
     */
    public function registerPublicFolder(string $folderPath): void;

    /**
     * Возвращает директорию скомпилированных ресурсов
     *
     * @return string
     */
    public function getDistRootFolder(): string;

    /**
     * Возвращает директорию скомпилированных публичных ресурсов
     *
     * @param string $fileType Тип файла
     *
     * @return string
     */
    public function getDistPublicFolder(string $fileType): string;

        /**
     * Возвращает путь к папке модулей Node.js
     *
     * @return string
     */
    public function getNodeModulesPath(): string;
}

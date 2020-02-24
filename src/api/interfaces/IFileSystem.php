<?php

/**
 * IFileSystem.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use XEAF\Rack\API\Utils\FileSystem;

/**
 * Описывает методы работы с файловой системой
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IFileSystem extends IFactoryObject {

    /**
     * Возвращает признак существования файла
     *
     * @param string|null $filePath Путь к файлу
     *
     * @return bool
     */
    function fileExists(?string $filePath): bool;

    /**
     * Возвращает признак существования папки
     *
     * @param string|null $folderPath Путь к папке
     * @param bool        $create     Признак необходимости создания
     *
     * @return bool
     */
    function folderExists(?string $folderPath, bool $create = false): bool;

    /**
     * Безопасно удаляет файл
     *
     * @param string|null $filePath Путь к файлу
     *
     * @return bool
     */
    function deleteFile(?string $filePath): bool;

    /**
     * Безопасно удаляет папку
     *
     * @param string|null $folderPath Путь к папке
     *
     * @return bool
     */
    function deleteFolder(?string $folderPath): bool;

    /**
     * Возвращает имя файла без расширения
     *
     * @param string $filePath Имя файла
     *
     * @return string
     */
    function trimFileNameExt(string $filePath): string;

    /**
     * Заменяет расширение в имени файла
     *
     * @param string $filePath Путь к файлу
     * @param string $newExt   Новое расширение
     *
     * @return string
     */
    function changeFileNameExt(string $filePath, string $newExt): string;

    /**
     * Возвращает путь к минимизированной версии файла
     *
     * @param string $filePath Путь к файлу
     *
     * @return string
     */
    function minimizedFilePath(string $filePath): string;

    /**
     * Возвращает признак существования минимизированной версии файла
     *
     * @param string $filePath Путьк файлу
     *
     * @return bool
     *
     * @since 1.0.2
     */
    function minimizedFileExists(string $filePath): bool;

    /**
     * Возвращает директорию файла
     *
     * @param string $filePath Путь к файлу
     *
     * @return string
     */
    function fileDir(string $filePath): string;

    /**
     * Возвращает имя файла
     *
     * @param string $filePath Путь к файлу
     *
     * @return string
     */
    function fileName(string $filePath): string;

    /**
     * Возвращает имя файла с расширением
     *
     * @param string $filePath Путь к файлу
     *
     * @return string
     */
    function fileBaseName(string $filePath): string;

    /**
     * Возвращает расширение имени файла
     *
     * @param string $filePath Путь к файлу
     *
     * @return string
     */
    function fileNameExt(string $filePath): string;

    /**
     * Проверяет корректность имени файла
     *
     * @param string $fileName Имя файла
     *
     * @return bool
     */
    function validateFileName(string $fileName): bool;

    /**
     * Очищает имя файла от недопустимых символов
     *
     * @param string $fileName    Имя файла
     * @param string $replaceWith Замена для недопустимых символов
     *
     * @return string
     */
    function sanitizeFileName(string $fileName, string $replaceWith = ''): string;

    /**
     * Выводит файл блоками
     *
     * @param string $filePath  Путь к файлу
     * @param int    $chunkSize Размер блока
     *
     * @return void
     */
    function readFileChunks(string $filePath, int $chunkSize = FileSystem::CHUNK_SIZE): void;
}

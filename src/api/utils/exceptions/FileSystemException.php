<?php

/**
 * FileSystemException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе с файловой системой
 *
 * @package XEAF\Rack\API\Utils\Exceptions
 */
class FileSystemException extends Exception {

    /**
     * Файл не найден
     */
    public const FILE_NOT_FOUND = 'FS001';

    /**
     * Директория файлов не найдена
     */
    public const FOLDER_NOT_FOUND = 'FS002';

    /**
     * Не могу создать файл
     */
    public const COULD_NOT_CREATE_FILE = 'FS003';

    /**
     * Не могу создать директорию файлов
     */
    public const COULD_NOT_CREATE_FOLDER = 'FS004';

    /**
     * Не могу удалить файл
     */
    public const COULD_NOT_DELETE_FILE = 'FS005';

    /**
     * Не могу удалить директорию файлов
     */
    public const COULD_NOT_DELETE_FOLDER = 'FS006';

    /**
     * Не мону изменить директорию файлов
     */
    public const COULD_NOT_CHANGE_FOLDER = 'FS007';

    /**
     * Файл не найден
     *
     * @param string $path Путь к файлу
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FileSystemException
     */
    public static function fileNotFound(string $path): self {
        return new self(self::FILE_NOT_FOUND, [$path]);
    }

    /**
     * Директория не найдена
     *
     * @param string $path Путь к директории файлов
     *
     * @return static
     */
    public static function folderNotFound(string $path): self {
        return new self(self::FOLDER_NOT_FOUND, [$path]);
    }

    /**
     * Не могу создать файл
     *
     * @param string $path Путь к файлу
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FileSystemException
     */
    public static function couldNotCreateFile(string $path): self {
        return new self(self::COULD_NOT_CREATE_FILE, [$path]);
    }

    /**
     * Не могу создать директорию файлов
     *
     * @param string $path Путь к директории файлов
     *
     * @return static
     */
    public static function couldNotCreateFolder(string $path): self {
        return new self(self::COULD_NOT_CREATE_FOLDER, [$path]);
    }

    /**
     * Не могу удалить файл
     *
     * @param string $path Путь к файлу
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FileSystemException
     */
    public static function couldNotDeleteFile(string $path): self {
        return new self(self::COULD_NOT_DELETE_FILE, [$path]);
    }

    /**
     * Не могу удалить директорию файлов
     *
     * @param string $path Путь к директории файлов
     *
     * @return static
     */
    public static function couldNotDeleteFolder(string $path): self {
        return new self(self::COULD_NOT_DELETE_FOLDER, [$path]);
    }

    /**
     * Не могу изменить директорию файлов
     *
     * @param string $path Путь к директории файлов
     *
     * @return static
     */
    public static function couldNotChangeFolder(string $path): self {
        return new self(self::COULD_NOT_CHANGE_FOLDER, [$path]);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = null;
        switch ($code) {
            case self::FILE_NOT_FOUND:
                $result = 'File not found [%s].';
                break;
            case self::FOLDER_NOT_FOUND:
                $result = 'Folder not found [%s].';
                break;
            case self::COULD_NOT_CREATE_FILE:
                $result = 'Could not create file [%s].';
                break;
            case self::COULD_NOT_CREATE_FOLDER:
                $result = 'Could not create folder [%s].';
                break;
            case self::COULD_NOT_DELETE_FILE:
                $result = 'Could not delete file [%s].';
                break;
            case self::COULD_NOT_DELETE_FOLDER:
                $result = 'Could not delete folder [%s].';
                break;
            case self::COULD_NOT_CHANGE_FOLDER:
                $result = 'Could not change folder [%s].';
                break;
        }
        return $result;
    }
}

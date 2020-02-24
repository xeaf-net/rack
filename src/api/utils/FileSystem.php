<?php

/**
 * FileSystem.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IFileSystem;

/**
 * Реализует методы работы с файловой системой
 *
 * @package XEAF\Rack\API\Utils
 */
class FileSystem implements IFileSystem {

    /**
     * Режим новой папки
     */
    public const FOLDER_MODE = 0777;

    /**
     * Размер передаваемого блока файла
     */
    public const CHUNK_SIZE = 8192;

    /**
     * Суффикс имени минимизированного файла
     */
    public const MINIMIZED_SUFFIX = 'min';

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function fileExists(?string $filePath): bool {
        return ($filePath != null && file_exists($filePath) && !is_dir($filePath));
    }

    /**
     * @inheritDoc
     */
    public function folderExists(?string $folderPath, bool $create = false): bool {
        $result = false;
        if ($folderPath != null) {
            $result = is_dir($folderPath);
            if (!$result && $create && !$this->fileExists($folderPath)) {
                mkdir($folderPath, self::FOLDER_MODE, true);
                $result = self::folderExists($folderPath);
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function deleteFile(?string $filePath): bool {
        $result = true;
        if ($this->fileExists($filePath)) {
            unlink($filePath);
            $result = !$this->fileExists($filePath);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function deleteFolder(?string $folderPath): bool {
        $result = true;
        if ($this->folderExists($folderPath)) {
            $objects = scandir($folderPath);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($folderPath . "/" . $object) && !is_link($folderPath . "/" . $object)) {
                        $this->deleteFolder($folderPath . "/" . $object);
                    } else {
                        unlink($folderPath . "/" . $object);
                    }
                }
            }
            rmdir($folderPath);
            $result = !$this->folderExists($folderPath);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function trimFileNameExt(string $filePath): string {
        return $this->fileDir($filePath) . '/' . $this->fileName($filePath);
    }

    /**
     * @inheritDoc
     */
    public function changeFileNameExt(string $filePath, string $newExt): string {
        return $this->trimFileNameExt($filePath) . '.' . $newExt;
    }

    /**
     * @inheritDoc
     */
    public function minimizedFilePath(string $filePath): string {
        $fileType = $this->fileNameExt($filePath);
        return $this->changeFileNameExt($filePath, self::MINIMIZED_SUFFIX) . '.' . $fileType;
    }

    /**
     * @inheritDoc
     */
    public function minimizedFileExists(string $filePath): bool {
        $minFileName = $this->minimizedFilePath($filePath);
        return $this->fileExists($minFileName);
    }

    /**
     * @inheritDoc
     */
    public function fileDir(string $filePath): string {
        return pathinfo($filePath, PATHINFO_DIRNAME);
    }

    /**
     * @inheritDoc
     */
    public function fileName(string $filePath): string {
        return pathinfo($filePath, PATHINFO_FILENAME);
    }

    /**
     * @inheritDoc
     */
    public function fileBaseName(string $filePath): string {
        return pathinfo($filePath, PATHINFO_BASENAME);
    }

    /**
     * @inheritDoc
     */
    public function fileNameExt(string $filePath): string {
        return strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    }

    /**
     * @inheritDoc
     */
    public function validateFileName(string $fileName): bool {
        return $this->sanitizeFileName($fileName) == $fileName;
    }

    /**
     * @inheritDoc
     */
    public function sanitizeFileName(string $fileName, string $replaceWith = ''): string {
        return trim(str_replace(array_merge(array_map('chr', range(0, 31)), [
            '<',
            '>',
            ':',
            '"',
            '/',
            '\\',
            '|',
            '?',
            '*'
        ]), $replaceWith, $fileName));
    }

    /**
     * @inheritDoc
     */
    public function readFileChunks(string $filePath, int $chunkSize = self::CHUNK_SIZE): void {
        if (self::fileExists($filePath)) {
            $handle = fopen($filePath, "rb");
            while (!feof($handle)) {
                $chunk = fread($handle, $chunkSize);
                print $chunk;
            }
            fclose($handle);
        }
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\IFileSystem
     */
    public static function getInstance(): IFileSystem {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IFileSystem);
        return $result;
    }
}

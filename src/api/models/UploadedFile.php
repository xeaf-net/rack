<?php declare(strict_types = 1);

/**
 * UploadedFile.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models;

use XEAF\Rack\API\Core\DataModel;

/**
 * Описывает свойства информации о загруженной файле
 *
 * @property string $name     Имя файла
 * @property string $mime     MIME файла
 * @property int    $size     Размер файла
 * @property string $tempPath Путь ко временному расположению файла
 *
 * @package XEAF\Rack\API\Models
 */
class UploadedFile extends DataModel {

    /**
     * Имя файла
     * @var string
     */
    private string $_name = '';

    /**
     * MIME файла
     * @var string
     */
    private string $_mime = '';

    /**
     * Размер файла
     * @var int
     */
    private int $_size = 0;

    /**
     * Временное расположение файла
     * @var string
     */
    private string $_tempPath = '';

    /**
     * Возвразет имя файла
     *
     * @return string
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Задает имя файла
     *
     * @param string $name Имя файла
     *
     * @return void
     */
    public function setName(string $name): void {
        $this->_name = $name;
    }

    /**
     * Возвращает MIME файла
     *
     * @return string
     */
    public function getMime(): string {
        return $this->_mime;
    }

    /**
     * Задает MIME файла
     *
     * @param string $mime MIME файла
     *
     * @return void
     */
    public function setMime(string $mime): void {
        $this->_mime = $mime;
    }

    /**
     * Возвращает размер файла
     *
     * @return int
     */
    public function getSize(): int {
        return $this->_size;
    }

    /**
     * Задает размер файла
     *
     * @param int $size Размер файла
     *
     * @return void
     */
    public function setSize(int $size): void {
        $this->_size = $size;
    }

    /**
     * Возвращает путь ко временному расположению файла
     *
     * @return string
     */
    public function getTempPath(): string {
        return $this->_tempPath;
    }

    /**
     * Задает путь ко временному расположению файла
     *
     * @param string $tempPath Путь ко временному расположению файла
     *
     * @return void
     */
    public function setTempPath(string $tempPath): void {
        $this->_tempPath = $tempPath;
    }
}

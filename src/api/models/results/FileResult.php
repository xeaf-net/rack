<?php declare(strict_types = 1);

/**
 * FileResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Core\CachedResult;
use XEAF\Rack\API\Utils\FileMIME;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Реализует методы результата возвращающего файл
 *
 * @property string $filePath   Путь к файлу
 * @property string $fileName   Отображаемое имя файла
 * @property bool   $attachment Признак отправки файла как вложения
 * @property string $mimeType   Тип MIME
 * @property bool   $delete     Признак удаления после отправки
 *
 * @package XEAF\Rack\API\Models\Results
 */
class FileResult extends CachedResult {

    /**
     * Путь к файлу
     * @var string
     */
    protected $_filePath = '';

    /**
     * Имя файла
     * @var string|null
     */
    protected $_fileName = null;

    /**
     * Признак отправки файла как вложения
     * @var bool
     */
    protected $_attachment = true;

    /**
     * Тип MIME
     * @var string|null
     */
    protected $_mimeType = null;

    /**
     * Признак удаления после отправки
     * @var bool
     */
    protected $_delete = false;

    /**
     * Конструктор класса
     *
     * @param string $filePath   Путь к файлу
     * @param bool   $attachment Признак оправки как вложение
     * @param bool   $useCache   Признак использования кеша
     */
    public function __construct(string $filePath, bool $attachment = true, bool $useCache = false) {
        parent::__construct(HttpResponse::OK, $useCache);
        $this->_filePath   = $filePath;
        $this->_attachment = $attachment;
    }

    /**
     * Возвращает путь к файлу
     *
     * @return string
     */
    public function getFilePath(): string {
        return $this->_filePath;
    }

    /**
     * Задает путь к файлу
     *
     * @param string $filePath Путь к файлу
     *
     * @return void
     */
    public function setFilePath(string $filePath): void {
        $this->_filePath = $filePath;
    }

    /**
     * Возвращает отображаемое имя файла
     *
     * @return string
     */
    public function getFileName(): string {
        if (!$this->_fileName) {
            $fs              = FileSystem::getInstance();
            $this->_fileName = $fs->fileBaseName($this->_filePath);
        }
        return $this->_fileName;
    }

    /**
     * Задает отображаемое имя файла
     *
     * @param string $fileName Имя файла
     *
     * @return void
     */
    public function setFileName(string $fileName): void {
        $this->_fileName = $fileName;
    }

    /**
     * Возвращает тип MIME
     *
     * @return string
     */
    public function getMimeType(): string {
        if (!$this->_mimeType) {
            $fm              = FileMIME::getInsance();
            $fs              = FileSystem::getInstance();
            $fileType        = $fs->fileNameExt($this->_filePath);
            $this->_mimeType = $fm->getMIME($fileType);
        }
        return $this->_mimeType;
    }

    /**
     * Задает тип MIME
     *
     * @param string $value Имя файла
     *
     * @return void
     */
    public function setMimeType(string $value): void {
        $this->_mimeType = $value;
    }

    /**
     * Возвращает призанк отправки файла как вложения
     *
     * @return bool
     */
    public function getAttachment(): bool {
        return $this->_attachment;
    }

    /**
     * Задает признак отправки файла как вложения
     *
     * @param bool $value Признак отправки файла
     *
     * @return void
     */
    public function setAttachment(bool $value): void {
        $this->_attachment = $value;
    }

    /**
     * Возвращает признак удаления после отправки
     * @return bool
     */
    public function getDelete(): bool {
        return $this->_delete;
    }

    /**
     * Задает признак уаления после отправки
     *
     * @param bool $delete Признак удаления после отправки
     *
     * @return void
     */
    public function setDelete(bool $delete): void {
        $this->_delete = $delete;
    }

    /**
     * @inheritDoc
     */
    public function processResult(): void {
        $headers    = HttpResponse::getInstance();
        $fileSystem = FileSystem::getInstance();
        $headers->responseCode($this->getStatusCode());
        $headers->contentType($this->getMimeType());
        if ($this->getAttachment()) {
            $headers->fileAttachmentHeader($this->getFileName());
        } elseif ($this->getUseCache()) {
            $headers->fileCacheHeader();
        }
        $fileSystem->readFileChunks($this->getFilePath());
        if ($this->getDelete()) {
            $fileSystem->deleteFile($this->getFilePath());
        }
    }
}

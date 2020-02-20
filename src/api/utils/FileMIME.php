<?php /** @noinspection SpellCheckingInspection */

/**
 * FileMIME.php
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
use XEAF\Rack\API\Interfaces\IFileMIME;

/**
 * Реализует методы типизации файлов
 *
 * @package XEAF\Rack\API\Utils
 */
class FileMIME implements IFileMIME {

    /**
     * Тип MIME по умолчанию
     */
    public const DEFAULT_MIME_TYPE = 'application/octet-stream';

    /**
     * Известные форматы файлов изображений
     */
    private $_knownImages = [
        'bmp'  => 'image/bmp',
        'gif'  => 'image/gif',
        'ico'  => 'image/x-icon',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'raw'  => 'image/raw',
        'svg'  => 'image/svg+xml',
    ];

    /**
     * Известные формате аудиофайлов
     */
    private $_knownAudio = [
        'mp3' => 'audio/mpeg3',
    ];

    /**
     * Известные форматы видеофайлов
     */
    private $_knownVideo = [
        'avi' => 'video/x-msvideo',
        'mov' => 'video/quicktime',
        'mp4' => 'video/mp4',
    ];

    /**
     * Известные форматы файлов ресурсов
     */
    private $_knownResources = [
        'css'   => 'text/css',
        'eot'   => 'application/vnd.ms-fontobject',
        'js'    => 'application/x-javascript',
        'json'  => 'application/json',
        'lang'  => 'application/json',
        'map'   => 'application/json',
        'ttf'   => 'application/x-font-ttf',
        'woff'  => 'application/font-woff',
        'woff2' => 'application/font-woff',
    ];

    /**
     * Прочие известные форматы файлов
     */
    private $_knownOtherFiles = [
        '7z'   => 'application/x-7z-compressed',
        'ai'   => 'application/illustrator',
        'doc'  => 'application/msword',
        'docx' => 'application/vndopenxmlformats-officedocumentwordprocessingmldocument',
        'ppt'  => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'rar'  => 'application/x-rar',
        'txt'  => 'text/plain',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xml'  => 'text/xml',
        'zip'  => 'application/zip'
    ];

    /**
     * Список всех известных типов
     * @var array
     */
    private $_allKnownTypes = [];

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->_allKnownTypes = array_merge($this->_knownImages, $this->_knownAudio, $this->_knownVideo, $this->_knownResources, $this->_knownOtherFiles);
    }

    /**
     * @inheritDoc
     */
    public function isImage(string $fileType): bool {
        return isset($this->_knownImages[strtolower($fileType)]);
    }

    /**
     * @inheritDoc
     */
    public function isAudio(string $fileType): bool {
        return isset($this->_knownAudio[$fileType]);
    }

    /**
     * @inheritDoc
     */
    public function isVideo(string $fileType): bool {
        return isset($this->_knownVideo[$fileType]);
    }

    /**
     * @inheritDoc
     */
    public function isResource(string $fileType): bool {
        return isset($this->_knownResources[$fileType]);
    }

    /**
     * @inheritDoc
     */
    public function isFile(string $fileType): bool {
        return isset($this->_knownOtherFiles[$fileType]);
    }

    /**
     * @inheritDoc
     */
    public function getMIME(string $fileType): string {
        if (!$this->isSupported($fileType)) {
            $result = self::DEFAULT_MIME_TYPE;
        } else {
            $result = $this->_allKnownTypes[$fileType];
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isSupported(string $fileType): bool {
        return array_key_exists($fileType, $this->_allKnownTypes);
    }

    /**
     * @inheritDoc
     */
    public function registerImageFileType(string $fileType, string $mime): void {
        $this->unregisterFileType($fileType);
        $this->_knownImages[$fileType]   = $mime;
        $this->_allKnownTypes[$fileType] = $mime;
    }

    /**
     * @inheritDoc
     */
    public function registerAudioFileType(string $fileType, string $mime): void {
        $this->unregisterFileType($fileType);
        $this->_knownAudio[$fileType]    = $mime;
        $this->_allKnownTypes[$fileType] = $mime;
    }

    /**
     * @inheritDoc
     */
    public function registerVideoFileType(string $fileType, string $mime): void {
        $this->unregisterFileType($fileType);
        $this->_knownVideo[$fileType]    = $mime;
        $this->_allKnownTypes[$fileType] = $mime;
    }

    /**
     * @inheritDoc
     */
    public function registerResourceFileType(string $fileType, string $mime): void {
        $this->unregisterFileType($fileType);
        $this->_knownResources[$fileType] = $mime;
        $this->_allKnownTypes[$fileType]  = $mime;
    }

    /**
     * @inheritDoc
     */
    public function registerOtherFileType(string $fileType, string $mime): void {
        $this->unregisterFileType($fileType);
        $this->_knownOtherFiles[$fileType] = $mime;
        $this->_allKnownTypes[$fileType]   = $mime;
    }

    /**
     * @inheritDoc
     */
    public function unregisterFileType(string $fileType): void {
        unset($this->_knownImages[$fileType]);
        unset($this->_knownAudio[$fileType]);
        unset($this->_knownVideo[$fileType]);
        unset($this->_knownResources[$fileType]);
        unset($this->_knownOtherFiles[$fileType]);
        unset($this->_allKnownTypes[$fileType]);
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IFileMIME
     */
    public static function getInsance(): IFileMIME {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IFileMIME);
        return $result;
    }
}

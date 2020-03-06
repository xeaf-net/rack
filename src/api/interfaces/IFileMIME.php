<?php declare(strict_types = 1);

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
namespace XEAF\Rack\API\Interfaces;

/**
 * Объявляет методы типизации файлов
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IFileMIME extends IFactoryObject {

    /**
     * Возвращает признак файла изображения
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    public function isImage(string $fileType): bool;

    /**
     * Возвращает признак аудиофайла
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    public function isAudio(string $fileType): bool;

    /**
     * Возвращает признак видеофайла
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    public function isVideo(string $fileType): bool;

    /**
     * Возвращает признак файла ресурса
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    public function isResource(string $fileType): bool;

    /**
     * Возвращает признак файла прочего поддерживаемого типа
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    public function isFile(string $fileType): bool;

    /**
     * Возвращает MIME для заданного типа файла
     *
     * @param string $fileType Тип файла
     *
     * @return string
     */
    public function getMIME(string $fileType): string;

    /**
     * Возвращает признак поддержки типа файла
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    public function isSupported(string $fileType): bool;

    /**
     * Возвращает признак типа файла ресурса расширения
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     *
     * @since 1.0.2
     */
    public function isExtensionResource(string $fileType): bool;

    /**
     * Регистрирует тип файла изображения
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    public function registerImageFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует тип аудио файла
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    public function registerAudioFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует тип видео файла
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    public function registerVideoFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует тип файла ресурса
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    public function registerResourceFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует прочий поддерживаемый тип файла
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    public function registerOtherFileType(string $fileType, string $mime): void;

    /**
     * Отменяет регистрацию типа файла
     *
     * @param string $fileType Тип файла
     *
     * @return void
     */
    public function unregisterFileType(string $fileType): void;
}

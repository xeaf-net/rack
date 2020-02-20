<?php

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
    function isImage(string $fileType): bool;

    /**
     * Возвращает признак аудиофайла
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    function isAudio(string $fileType): bool;

    /**
     * Возвращает признак видеофайла
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    function isVideo(string $fileType): bool;

    /**
     * Возвращает признак файла ресурса
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    function isResource(string $fileType): bool;

    /**
     * Возвращает признак файла прочего поддерживаемого типа
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    function isFile(string $fileType): bool;

    /**
     * Возвращает MIME для заданного типа файла
     *
     * @param string $fileType Тип файла
     *
     * @return string
     */
    function getMIME(string $fileType): string;

    /**
     * Возвращает признак поддержки типа файла
     *
     * @param string $fileType Тип файла
     *
     * @return bool
     */
    function isSupported(string $fileType): bool;

    /**
     * Регистрирует тип файла изображения
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    function registerImageFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует тип аудио файла
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    function registerAudioFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует тип видео файла
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    function registerVideoFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует тип файла ресурса
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    function registerResourceFileType(string $fileType, string $mime): void;

    /**
     * Регистрирует прочий поддерживаемый тип файла
     *
     * @param string $fileType Тип файла
     * @param string $mime     MIME
     *
     * @return void
     */
    function registerOtherFileType(string $fileType, string $mime): void;

    /**
     * Отменяет регистрацию типа файла
     *
     * @param string $fileType Тип файла
     *
     * @return void
     */
    function unregisterFileType(string $fileType): void;
}

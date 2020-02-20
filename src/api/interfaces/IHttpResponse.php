<?php

/**
 * IHttpResponse.php
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
 * Описывает методы отправки заголовков
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IHttpResponse extends IFactoryObject {

    /**
     * Отправляет код состояния протокола HTTP
     *
     * @param int $statusCode Код статуса протокола HTTP
     *
     * @return void
     */
    function responseCode(int $statusCode): void;

    /**
     * Добавляет заголовок для типа отправляемого контента
     *
     * @param string $mimeType Тип MIME
     * @param string $charset  Нобор символов
     *
     * @return void
     */
    function contentType(string $mimeType, string $charset = ''): void;

    /**
     * Добавляет заголовок отправки данных в формате JSON
     *
     * @return void
     */
    function contentJSON(): void;

    /**
     * Добавляет заголовок переадресации
     *
     * @param string $url URL для переадресации
     *
     * @return void
     */
    function locationHeader(string $url): void;

    /**
     * Добавляет заголовок отправки файла как вложение
     *
     * @param string $fileName Имя файла
     *
     * @return void
     */
    function fileAttachmentHeader(string $fileName): void;

    /**
     * Добавляет заголовок кеширования отправляемого файла
     *
     * @return void
     */
    function fileCacheHeader(): void;
}

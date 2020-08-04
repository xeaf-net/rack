<?php declare(strict_types = 1);

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
     * @param int $statusCode Код состояния HTTP
     *
     * @return void
     */
    public function responseCode(int $statusCode): void;

    /**
     * Формирование заголовка Authenticate: Bearer
     *
     * @param int $statusCode Код состояния HTTP
     *
     * @return void
     */
    public function authenticateBearer(int $statusCode): void;

    /**
     * Добавляет заголовок для типа отправляемого контента
     *
     * @param string      $mimeType Тип MIME
     * @param string|null $charset  Нобор символов
     *
     * @return void
     */
    public function contentType(string $mimeType, ?string $charset = ''): void;

    /**
     * Добавляет заголовок отправки данных в формате JSON
     *
     * @return void
     */
    public function contentJSON(): void;

    /**
     * Добавляет заголовок переадресации
     *
     * @param string $url URL для переадресации
     *
     * @return void
     */
    public function locationHeader(string $url): void;

    /**
     * Добавляет заголовок отправки файла как вложение
     *
     * @param string $fileName Имя файла
     *
     * @return void
     */
    public function fileAttachmentHeader(string $fileName): void;

    /**
     * Добавляет заголовок кеширования отправляемого файла
     *
     * @return void
     */
    public function fileCacheHeader(): void;
}

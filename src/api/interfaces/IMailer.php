<?php declare(strict_types = 1);

/**
 * IMailer.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы отправки сообщений электронной почты
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IMailer extends IFactoryObject {

    /**
     * Сбрасывает настройки отправки электронной почты
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Добавляет нового получателя
     *
     * @param string $email Адрес электронной почты
     * @param string $name  Имя получателя
     *
     * @return void
     */
    public function addAddress(string $email, string $name = ''): void;

    /**
     * Добавляет новый адрес для ответа
     *
     * @param string $email Адрес электронной почты
     * @param string $name  Имя отправителя
     *
     * @return void
     */
    public function addReplayTo(string $email, string $name = ''): void;

    /**
     * Добавляет нового получателя копии
     *
     * @param string $email Адрес электронной почты
     * @param string $name  Имя получателя
     *
     * @return void
     */
    public function addCC(string $email, string $name): void;

    /**
     * Добавляет нового получателя скрытой копии
     *
     * @param string $email Адрес электронной почты
     * @param string $name  Имя получателя
     *
     * @return void
     */
    public function addBCC(string $email, string $name): void;

    /**
     * Добавляет новое вложение
     *
     * @param string $filePath Путь к файлу
     * @param string $fileName Имя файла
     *
     * @return void
     */
    public function addAttachment(string $filePath, string $fileName = ''): void;

    /**
     * Задает пирзнак отправки почты в формате HTML
     *
     * @param bool $isHTML Признак отправки в виде HTML
     *
     * @return void
     */
    public function setHtml(bool $isHTML): void;

    /**
     * Отправляет электронное пиьмо
     *
     * @param string $subject Тема письма
     * @param string $body    Тело сообщения
     * @param string $altBody Альтернативное тело (не HTML)
     *
     * @return void
     */
    public function send(string $subject, string $body, string $altBody = ''): void;

    /**
     * Возвращет результат последней операции
     *
     * @return string|null
     */
    public function getLastError(): ?string;

}

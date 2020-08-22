<?php declare(strict_types = 1);

/**
 * INotificator.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use XEAF\Rack\API\Core\DataObject;

/**
 * Реализует методы отправки нотификационных сообщений
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface INotificator extends IFactoryObject {

    /**
     * Отправляет нотификационное сообщение
     *
     * @param string                              $userId     Идентификатор пользователя
     * @param string                              $type       Тип сообщения
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function notify(string $userId, string $type, DataObject $dataObject = null): void;

    /**
     * Отправляет сообщение пользователю сессии
     *
     * @param string                              $type       Тип сообщеия
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function notifyMe(string $type, DataObject $dataObject = null): void;

    /**
     * Отправляет сообщение всем пользователям
     *
     * @param string                              $type       Тип сообщения
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function notifyAll(string $type, DataObject $dataObject = null): void;

    /**
     * Отправляет нотификационное сообщение группе пользователей
     *
     * @param array                               $users      Список идентификаторов пользователей
     * @param string                              $type       Тип сообщения
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных сообщения
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function notifyGroup(array $users, string $type, DataObject $dataObject = null): void;

    /**
     * Регистрирует сессию пользователя
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function registerUserSession(): void;

    /**
     * Отменяет регистрацию сессии пользователя
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function unregisterUserSession(): void;
}

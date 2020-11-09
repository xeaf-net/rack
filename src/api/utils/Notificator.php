<?php declare(strict_types = 1);

/**
 * Notificator.php
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
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Core\RestAPI;
use XEAF\Rack\API\Interfaces\INotificator;
use XEAF\Rack\API\Models\Config\NotificatorConfig;

/**
 * Реализует методы отправки нотификационных сообщений
 *
 * @package XEAF\Rack\API\Utils
 */
class Notificator extends RestAPI implements INotificator {

    /**
     * Cookie URL службы нотификаций
     */
    public const XNS_URL = 'xns-url';

    /**
     * Cookie сессии пользователя
     */
    public const XNS_SESSION_ID = 'xns-session-id';

    /**
     * Путь действия регистрации сессии
     */
    protected const LOGIN_PATH = 'login';

    /**
     * Путь действия отмены регистрации сессии
     */
    protected const LOGOUT_PATH = 'logout';

    /**
     * Путь действия нотификации
     */
    protected const NOTIFY_PATH = 'notify';

    /**
     * Поле идентификаторов пользователей
     */
    protected const USERS_FIELD = 'users';

    /**
     * Поле типа сообщения
     */
    protected const TYPE_FIELD = 'type';

    /**
     * Поле данных сообщения
     */
    protected const DATA_FIELD = 'data';

    /**
     * Параметры конфигурации
     * @var \XEAF\Rack\API\Models\Config\NotificatorConfig
     */
    private $_config;

    /**
     * Объект методов доступа к сессии
     * @var \XEAF\Rack\API\Interfaces\ISession
     */
    private $_session;

    /**
     * Конструктор класса
     */
    public function __construct() {
        parent::__construct();
        $this->_config  = NotificatorConfig::getInstance();
        $this->_session = Session::getInstance();
    }

    /**
     * @inheritDoc
     */
    public function notify(string $userId, string $type, DataObject $dataObject = null): void {
        self::notifyGroup([$userId], $type, $dataObject);
    }

    /**
     * @inheritDoc
     */
    public function notifyMe(string $type, DataObject $dataObject = null): void {
        $this->notify($this->_session->getUserId(), $type, $dataObject);
    }

    /**
     * @inheritDoc
     */
    public function notifyAll(string $type, DataObject $dataObject = null): void {
        $this->notify($this->_config->getKey(), $type, $dataObject);
    }

    /**
     * @inheritDoc
     */
    public function notifyGroup(array $users, string $type, DataObject $dataObject = null): void {
        if ($this->canUseService()) {
            $srz     = Serializer::getInstance();
            $url     = $this->_config->getUrl() . '/' . self::NOTIFY_PATH;
            $json    = $srz->jsonDataObjectEncode($dataObject);
            $message = [
                self::USERS_FIELD => $users,
                self::TYPE_FIELD  => $type,
                self::DATA_FIELD  => $json
            ];
            $this->post($url, ['sender' => $this->_config->getKey()], $message);
        }
    }

    /**
     * @inheritDoc
     */
    public function registerUserSession(): void {
        if ($this->canUseService()) {
            $url = $this->_config->getUrl() . '/' . self::LOGIN_PATH;
            $this->post($url, [
                'sender'  => $this->_config->getKey(),
                'session' => $this->_session->getId(),
                'user'    => $this->_session->getUserId()
            ]);
            self::setupNotificationCookie();
        }
    }

    /**
     * @inheritDoc
     */
    public function unregisterUserSession(): void {
        if ($this->canUseService()) {
            $url = $this->_config->getUrl() . '/' . self::LOGOUT_PATH;
            $this->post($url, [
                'sender'  => $this->_config->getKey(),
                'session' => $this->_session->getId()
            ]);
        }
        self::cleanupNotificationCookie();
    }

    /**
     * Возвращает признак возможности использования сервиса
     *
     * @return bool
     */
    protected function canUseService(): bool {
        $strings = Strings::getInstance();
        return $this->_config->getEnabled() && !$strings->isEmpty($this->_config->getKey());
    }

    /**
     * Устанавливает cookie для службы нотификаций
     *
     * @return void
     */
    protected function setupNotificationCookie(): void {
        if ($this->canUseService() && $this->_session->isNative()) {
            $cookie = Cookie::getInstance();
            $cookie->put(self::XNS_URL, $this->_config->getUrl());
            $cookie->put(self::XNS_SESSION_ID, $this->_session->getId());
        }
    }

    /**
     * Удаляет cookie для службы нотификаций
     *
     * @return void
     */
    protected function cleanupNotificationCookie(): void {
        if ($this->_session->isNative()) {
            $cookie = Cookie::getInstance();
            $cookie->delete(self::XNS_URL);
            $cookie->delete(self::XNS_SESSION_ID);
        }
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Utils\Notificator
     */
    public static function getInstance(): Notificator {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof Notificator);
        return $result;
    }
}

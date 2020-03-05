<?php declare(strict_types = 1);

/**
 * NativeSessionProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Sessions;

use XEAF\Rack\API\Utils\Crypto;
use XEAF\Rack\API\Utils\Session;

/**
 * Реализует методы провайдера нативной сессии
 *
 * @package XEAF\Rack\API\Utils\Sessions
 */
class NativeSessionProvider extends StorageSessionProvider {

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'native';

    /**
     * @inheritDoc
     */
    public function loadSessionVars(): void {
        $this->sessionStart();
        $this->resolveSessionId();
        foreach ($_SESSION as $key => $value) {
            $this->put($key, $value);
            unset($_SESSION[$key]);
        }
        $this->writeAndClose();
    }

    /**
     * @inheritDoc
     */
    public function saveSessionVars(): void {
        $this->sessionStart();
        $data = $this->toArray();
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
        $this->writeAndClose();
    }

    /**
     * @inheritDoc
     */
    public function isNative(): bool {
        return true;
    }

    /**
     * Получает значение переменной сессии
     *
     * @return void
     */
    protected function resolveSessionId(): void {
        $id = $_SESSION[Session::SESSION_ID] ?? null;
        if (!$id) {
            $id = Crypto::getInstance()->generateUUIDv4();
        }
        $this->setId($id);
    }

    /**
     * Открывает нативную сессию
     *
     * @return void
     */
    protected function sessionStart(): void {
        session_start();
    }

    /**
     * Закрывает нативную сессию
     *
     * @return void
     */
    protected function writeAndClose(): void {
        session_write_close();
    }
}

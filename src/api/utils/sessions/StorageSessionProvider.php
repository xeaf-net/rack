<?php declare(strict_types = 1);

/**
 * StorageSessionProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Sessions;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IStorage;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Utils\Crypto;
use XEAF\Rack\API\Utils\Exceptions\CryptoException;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\API\Utils\Session;
use XEAF\Rack\API\Utils\Storage;
use XEAF\Rack\API\Utils\Strings;

/**
 * Реализует методы использования хранилищ для сохранения переменных сессии
 *
 * @package XEAF\Rack\API\Utils\Sessions
 */
class StorageSessionProvider extends StaticSessionProvider {

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'storage';

    /**
     * Объект хранилища
     * @var \XEAF\Rack\API\Interfaces\IStorage|null
     */
    private ?IStorage $_storage = null;

    /**
     * Признак использования JWT
     * @var bool
     */
    private bool $_useJWT = false;

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function loadSessionVars(): void {
        $this->resolveSessionId();
        $name = $this->storageVarName();
        $data = $this->storage()->get($name, []);
        foreach ($data as $key => $value) {
            if ($key != Session::SESSION_ID) {
                $this->put($key, $value);
            }
        }
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function saveSessionVars(): void {
        $name = $this->storageVarName();
        $data = $this->toArray();
        unset($data[Session::SESSION_ID]);
        $this->storage()->put($name, $data);
    }

    /**
     * Возвращает объект хранилища
     *
     * @return \XEAF\Rack\API\Interfaces\IStorage
     */
    protected function storage(): IStorage {
        if ($this->_storage == null) {
            $config         = PortalConfig::getInstance();
            $data           = Strings::getInstance()->parseDSN($config->getSession());
            $name           = $data['name'] ?? Factory::DEFAULT_NAME;
            $this->_useJWT  = $data['jwt'] ?? false;
            $this->_storage = Storage::getInstance($name);
        }
        return $this->_storage;
    }

    /**
     * Устанавливает значение идентификатора сессии
     *
     * @return void
     */
    protected function resolveSessionId(): void {
        if (!$this->_useJWT) {
            $crypto    = Crypto::getInstance();
            $strings   = Strings::getInstance();
            $sessionId = $crypto->requestHeaderBearer();
            if (!$strings->isUUID($sessionId)) {
                $params    = Parameters::getInstance();
                $encodedId = $params->getString(strtolower(Session::SESSION_ID));
                if ($encodedId) {
                    $sessionId = $crypto->base64Decode($encodedId);
                }
            }
            if (!$sessionId || !$strings->isUUID($sessionId)) {
                $sessionId = $crypto->generateUUIDv4();
            }
            $this->setId($sessionId);
        } else {
            $this->resolveJWT();
        }
    }

    /**
     * Устанавливает значение идентификатора сессии из JWT
     *
     * @return void
     */
    protected function resolveJWT(): void {
        $crypto = Crypto::getInstance();
        try {
            $encodedJWT = $crypto->requestHeaderBearer();
            if ($encodedJWT) {
                $decodedJWT = $crypto->decodeJWT($encodedJWT);
                if ($crypto->validateJWT($decodedJWT)) {
                    $sessionId = $decodedJWT->getPayload()[Session::SESSION_ID] ?? null;
                    if ($sessionId) {
                        $this->setId($sessionId);
                    }
                }
            }
        } catch (CryptoException $exception) {
            Logger::getInstance()->exception($exception);
        }
    }

    /**
     * Возвращает имя переменной хранилища
     *
     * @return string
     */
    protected function storageVarName(): string {
        return Session::SESSION_VAR_PREFIX . $this->getId();
    }
}

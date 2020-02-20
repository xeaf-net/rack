<?php

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
     * @var \XEAF\Rack\API\Interfaces\IStorage
     */
    private $_storage = null;

    /**
     * @inheritDoc
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
            $config = PortalConfig::getInstance();
            $data   = Strings::getInstance()->parseDSN($config->getSession());
            $name   = $data['name'] ?? Factory::DEFAULT_NAME;
            $this->_storage = Storage::getInstance($name);
        }
        return $this->_storage;
    }

    /**
     * Получает значение переменной сессии
     *
     * @return void
     */
    protected function resolveSessionId(): void {
        $params = Parameters::getInstance();
        $id     = $params->get(strtolower(Session::SESSION_ID));
        if (!$id) {
            $id = $params->getHeader(Session::SESSION_ID);
        }
        if ($id) {
            $this->setId($id);
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
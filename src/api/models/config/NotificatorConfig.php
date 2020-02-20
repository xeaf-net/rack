<?php

/**
 * NotificatorConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Config;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\ConfigModel;
use XEAF\Rack\API\Interfaces\IFactoryObject;

/**
 * Содержит параметры конфигурации отправки сообщений
 *
 * @property-read string $url     URL сервера отправки сообщений
 * @property-read string $key     Ключ доступа к серверу отправки сообщений
 * @property-read bool   $enabled Признак разрешения использования
 *
 * @package XEAF\Rack\API\Models\Config
 */
class NotificatorConfig extends ConfigModel implements IFactoryObject {

    /**
     * Имя секции в файле конфигурации
     */
    public const SECTION_NAME = 'notificator';

    /**
     * URL сервера отправки сообщений по умолчанию
     */
    private const DEFAULT_URL = 'http://localhost:8181';

    /**
     * Ключ доступа к серверу по умолчанию
     */
    private const DEFAULT_KEY = '';

    /**
     * URL сервера отправки сообщений
     * @var string
     */
    private $_url = self::DEFAULT_URL;

    /**
     * Ключ доступа к серверу отправки сообщений
     * @var string
     */
    private $_key = self::DEFAULT_KEY;

    /**
     * Признак разрешения использования
     * @var bool
     */
    private $_enabled = false;

    /**
     * Конструктор класса
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct() {
        parent::__construct(self::SECTION_NAME);
    }

    /**
     * Возвращает URL сервера отправки сообщений
     *
     * @return string
     */
    public function getUrl(): string {
        return $this->_url;
    }

    /**
     * Возвращает ключ доступа к серверу отправки сообщений
     *
     * @return string
     */
    public function getKey(): string {
        return $this->_key;
    }

    /**
     * Возвращает признак разрешения использования
     *
     * @return bool
     */
    public function getEnabled(): bool {
        return $this->_enabled;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        $this->_url     = $data->{'url'} ?? self::DEFAULT_URL;
        $this->_key     = $data->{'key'} ?? self::DEFAULT_KEY;
        $this->_enabled = $data->{'enabled'} ?? false;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Models\Config\NotificatorConfig
     */
    public static function getInstance(): NotificatorConfig {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof NotificatorConfig);
        return $result;
    }
}

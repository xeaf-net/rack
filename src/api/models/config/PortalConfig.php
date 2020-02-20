<?php

/**
 * PortalConfig.php
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
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Parameters;

/**
 * Модель данных конфигурации портала
 *
 * @property-read string $url       URL портала
 * @property-read string $origin    URL источника запросов
 * @property-read string $session   Провайдер сессии
 * @property-read string $locale    Имя локали
 * @property-read string $tempPath  Директория временных файлов
 *
 * @package XEAF\Rack\API\Models\Config
 */
class PortalConfig extends ConfigModel implements IFactoryObject {

    /**
     * Имя секции в файле конфигурации
     */
    public const SECTION_NAME = 'portal';

    /**
     * URL портала по умолчанию
     */
    private const DEFAULT_URL = 'http://localhost';

    /**
     * URL источника запросов по умолчанию
     */
    private const DEFAULT_ORIGIN = Parameters::ORIGIN_ALL;

    /**
     * Имя локали по умолчанию
     */
    private const DEFAULT_LOCALE = Localization::DEFAULT_LOCALE;

    /**
     * Провайдер сессии по умолчанию
     */
    private const DEFAULT_SESSION = 'static://default';

    /**
     * Директория временных файлов по умолчанию
     */
    private const DEFAULT_TEMP_PATH = '/tmp';

    /**
     * URL портала
     * @var string
     */
    private $_url = self::DEFAULT_URL;

    /**
     * URL источника запросов
     * @var string
     */
    private $_origin = self::DEFAULT_ORIGIN;

    /**
     * Имя локали по умолчанию
     * @var string
     */
    private $_locale = self::DEFAULT_LOCALE;

    /**
     * Провайдер сессии
     * @var string
     */
    private $_session = self::DEFAULT_SESSION;

    /**
     * Директория временных файлов
     * @var string
     */
    private $_tempPath = self::DEFAULT_TEMP_PATH;

    /**
     * Конструктор класса
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct() {
        parent::__construct(self::SECTION_NAME);
    }

    /**
     * Возвращает URL портала
     *
     * @return string
     */
    public function getUrl(): string {
        return $this->_url;
    }

    /**
     * Возвращает URL источника запросов
     *
     * @return string
     */
    public function getOrigin(): string {
        return $this->_origin;
    }

    /**
     * Возвращает имя локали
     *
     * @return string
     */
    public function getLocale(): string {
        return $this->_locale;
    }

    /**
     * Возвращает провайдер сессии
     *
     * @return string
     */
    public function getSession(): string {
        return $this->_session;
    }

    /**
     * Возвращает директорию временных файлов
     *
     * @return string
     */
    public function getTempPath(): string {
        return $this->_tempPath;
    }

    /**
     * @inheritDoc
     */
    function parseConfigurationSection(object $data): void {
        $this->_url      = $data->{'url'} ?? self::DEFAULT_URL;
        $this->_origin   = $data->{'origin'} ?? self::DEFAULT_ORIGIN;
        $this->_locale   = $data->{'locale'} ?? self::DEFAULT_LOCALE;
        $this->_session  = $data->{'session'} ?? self::DEFAULT_SESSION;
        $this->_tempPath = $data->{'tempPath'} ?? self::DEFAULT_TEMP_PATH;
    }

    /**
     * Возвращет единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Models\Config\PortalConfig
     */
    public static function getInstance(): PortalConfig {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof PortalConfig);
        return $result;
    }
}

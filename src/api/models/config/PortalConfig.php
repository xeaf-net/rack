<?php declare(strict_types = 1);

/**
 * PortalConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
 * @property-read string $url         URL портала
 * @property-read string $host        Имя хоста портала
 * @property-read string $origin      URL источника запросов
 * @property-read string $bearer      Идентификатор типа авторизации
 * @property-read string $session     Провайдер сессии
 * @property-read string $locale      Имя локали
 * @property-read string $tempPath    Директория временных файлов
 * @property-read string $uploadsPath Директория загружаемых файлов
 * @property-read bool   $debugMode   Признак режима отладки
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
     * Идентфикатор типа авторизации
     */
    private const DEFAULT_BEARER = 'rackAPI';

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
     * Директория загружаемых файлов по умолчанию
     */
    private const DEFAULT_UPLOADS_PATH = '/tmp';

    /**
     * Признак режима отладки по умолчанию
     */
    private const DEFAULT_DEBUG_MODE = false;

    /**
     * URL портала
     * @var string
     */
    private string $_url = self::DEFAULT_URL;

    /**
     * URL источника запросов
     * @var string
     */
    private string $_origin = self::DEFAULT_ORIGIN;

    /**
     * Идентификатор тип авторизации
     * @var string
     */
    private string $_bearer = self::DEFAULT_BEARER;

    /**
     * Имя локали по умолчанию
     * @var string
     */
    private string $_locale = self::DEFAULT_LOCALE;

    /**
     * Провайдер сессии
     * @var string
     */
    private string $_session = self::DEFAULT_SESSION;

    /**
     * Директория временных файлов
     * @var string
     */
    private string $_tempPath = self::DEFAULT_TEMP_PATH;

    /**
     * Директория загружаемых файлов
     * @var string
     */
    private string $_uploadsPath = self::DEFAULT_UPLOADS_PATH;

    /**
     * Признак режима отладки
     * @var bool
     */
    private bool $_debugMode = self::DEFAULT_DEBUG_MODE;

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
     * Возвращает имя хоста портала
     *
     * @return string
     */
    public function getHost(): string {
        $url = $this->getUrl();
        return parse_url($url, PHP_URL_HOST);
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
     * Возвращает идентификатор типа авторизации
     *
     * @return string
     */
    public function getBearer(): string {
        return $this->_bearer;
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
     * Возвращает директорию загружаемых файлов
     *
     * @return string
     */
    public function getUploadsPath(): string {
        return $this->_uploadsPath;
    }

    /**
     * Возвращает признак режима отладки
     *
     * @return bool
     */
    public function getDebugMode(): bool {
        return $this->_debugMode;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        $this->_url         = $data->{'url'} ?? self::DEFAULT_URL;
        $this->_origin      = $data->{'origin'} ?? self::DEFAULT_ORIGIN;
        $this->_bearer      = $data->{'bearer'} ?? self::DEFAULT_BEARER;
        $this->_locale      = $data->{'locale'} ?? self::DEFAULT_LOCALE;
        $this->_session     = $data->{'session'} ?? self::DEFAULT_SESSION;
        $this->_tempPath    = $data->{'tempPath'} ?? self::DEFAULT_TEMP_PATH;
        $this->_uploadsPath = $data->{'uploadsPath'} ?? self::DEFAULT_UPLOADS_PATH;
        $this->_debugMode   = (bool)($data->{'debugMode'} ?? self::DEFAULT_DEBUG_MODE);
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

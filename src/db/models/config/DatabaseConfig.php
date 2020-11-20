<?php declare(strict_types = 1);

/**
 * DatabaseConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Models\Config;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\ConfigModel;
use XEAF\Rack\API\Interfaces\INamedObject;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Strings;

/**
 * Содержит параметры конфгурации подключения к базе данных
 *
 * @property-read string $provider Имя провайдера
 * @property-read string $host     Имя хоста
 * @property-read int    $port     Номер порта
 * @property-read string $dbName   Имя базы данных
 * @property-read string $user     Имя пользователя
 * @property-read string $password Пароль
 * @property-read string $charset  Набор символов
 *
 * @package XEAF\Rack\Db\Models\Config
 */
class DatabaseConfig extends ConfigModel implements INamedObject {

    use NamedObjectTrait;

    /**
     * Имя секции
     */
    public const SECTION_NAME = 'database';

    /**
     * Имя провайдера по умолчанию
     */
    protected const DEFAULT_PROVIDER = 'unknown';

    /**
     * Имя хоста по умолчанию
     */
    protected const DEFAULT_HOST = 'localhost';

    /**
     * Номер порта по умолчанию
     */
    protected const DEFAULT_PORT = 0;

    /**
     * Имя базы данных по умолчанию
     */
    protected const DEFAULT_DBNAME = 'unknown';

    /**
     * Имя пользователя по умолчанию
     */
    protected const DEFAULT_USER = '';

    /**
     * Пароль по умолчанию
     */
    protected const DEFAULT_PASSWORD = '';

    /**
     * Набор символов по умолчанию
     */
    protected const DEFAULT_CHARSET = '';

    /**
     * Имя провайдера
     * @var string
     */
    private string $_provider = self::DEFAULT_PROVIDER;

    /**
     * Имя хоста
     * @var string
     */
    private string $_host = self::DEFAULT_HOST;

    /**
     * Номер порта
     * @var int
     */
    private int $_port = self::DEFAULT_PORT;

    /**
     * Имя базы данных
     * @var string
     */
    private string $_dbname = self::DEFAULT_DBNAME;

    /**
     * Имя пользователя
     * @var string
     */
    private string $_user = '';

    /**
     * Пароль
     * @var string
     */
    private string $_password = '';

    /**
     * Набор символов
     * @var string
     */
    private string $_charset = '';

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        parent::__construct(self::SECTION_NAME, $name, true);
    }

    /**
     * Возвращает имя провайдера
     *
     * @return string
     */
    public function getProvider(): string {
        return $this->_provider;
    }

    /**
     * Возвращает имя хоста
     *
     * @return string
     */
    public function getHost(): string {
        return $this->_host;
    }

    /**
     * Возвращает номер порта
     *
     * @return int
     */
    public function getPort(): int {
        return $this->_port;
    }

    /**
     * Возвращает имя базы данных
     *
     * @return string
     */
    public function getDbName(): string {
        return $this->_dbname;
    }

    /**
     * Возвращает пользователя
     *
     * @return string
     */
    public function getUser(): string {
        return $this->_user;
    }

    /**
     * Возвращает пароль
     *
     * @return string
     */
    public function getPassword(): string {
        return $this->_password;
    }

    /**
     * Возвращает набор символов
     *
     * @return string
     */
    public function getCharset(): string {
        return $this->_charset;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        $strings         = Strings::getInstance();
        $this->_provider = $data->{'provider'} ?? self::DEFAULT_PROVIDER;
        $this->_host     = $data->{'host'} ?? self::DEFAULT_HOST;
        $this->_port     = (int) $strings->stringToInteger($data->{'port'} ?? null, self::DEFAULT_PORT);
        $this->_dbname   = $data->{'dbname'} ?? self::DEFAULT_DBNAME;
        $this->_user     = $data->{'user'} ?? self::DEFAULT_USER;
        $this->_password = $data->{'password'} ?? self::DEFAULT_PASSWORD;
        $this->_charset  = $data->{'charset'} ?? self::DEFAULT_CHARSET;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\Db\Models\Config\DatabaseConfig
     */
    public static function getInstance(string $name): DatabaseConfig {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof DatabaseConfig);
        return $result;
    }
}

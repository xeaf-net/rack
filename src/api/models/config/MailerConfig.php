<?php declare(strict_types = 1);

/**
 * MailerConfig.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Config;

use PHPMailer\PHPMailer\PHPMailer;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\ConfigModel;
use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\API\Utils\Strings;

/**
 * Описывает параметры конфигурации отправки электронной почты
 *
 * @property-read bool   $smtp       Признак использования SMTP
 * @property-read string $host       Имя хоста
 * @property-read int    $port       Номер порта
 * @property-read bool   $auth       Признак необходимости авторизации
 * @property-read string $secure     Способ защиты соединения
 * @property-read string $userName   Имя пользователя
 * @property-read string $password   Пароль
 * @property-read string $sendFrom   Адрес отправителя
 * @property-read string $senderName Имя отправителя
 *
 * @package  XEAF\Rack\API\Models\Config
 */
class MailerConfig extends ConfigModel implements IFactoryObject {

    /**
     * Имя секции файла конфигурации
     */
    public const SECTION_NAME = 'mailer';

    /**
     * Признак использования SMTP по умолчанию
     */
    private const DEFAULT_SMTP = false;

    /**
     * Имя хоста по умолчанию
     */
    private const DEFAULT_HOST = 'localhost';

    /**
     * Номер порта по умолчанию
     */
    private const DEFAULT_PORT = 25;

    /**
     * Признак необходимости авторизации по умолчанию
     */
    private const DEFAULT_AUTH = false;

    /**
     * Способ защиты соединения по умолчанию
     */
    private const DEFAULT_SECURE = PHPMailer::ENCRYPTION_STARTTLS;

    /**
     * Имя пользователя по умолчанию
     */
    private const DEFAULT_USERNAME = '';

    /**
     * Пароль по умолчанию
     */
    private const DEFAULT_PASSWORD = '';

    /**
     * Адрес отправителя по умолчанию
     */
    private const DEFAULT_SEND_FROM = '';

    /**
     * Имя отправителя по умолчанию
     */
    private const DEFAULT_SENDER_NAME = '';

    /**
     * Признак использования SMTP
     * @var bool
     */
    private $_smtp = self::DEFAULT_SMTP;

    /**
     * Имя хоста по умолчанию
     * @var string
     */
    private $_host = self::DEFAULT_HOST;

    /**
     * Номер порта по умолчанию
     * @var int
     */
    private $_port = self::DEFAULT_PORT;

    /**
     * Признак необходимости авторизации
     * @var bool
     */
    private $_auth = self::DEFAULT_AUTH;

    /**
     * Способ защиты соединения
     * @var string
     */
    private $_secure = self::DEFAULT_SECURE;

    /**
     * Имя пользователя
     * @var string
     */
    private $_userName = self::DEFAULT_USERNAME;

    /**
     * Пароль
     * @var string
     */
    private $_password = self::DEFAULT_PASSWORD;

    /**
     * Адрес отправителя
     * @var string
     */
    private $_sendFrom = self::DEFAULT_SEND_FROM;

    /**
     * Имя отправителя
     * @var string
     */
    private $_senderName = self::DEFAULT_SENDER_NAME;

    /**
     * Конструктор класса
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct() {
        parent::__construct(self::SECTION_NAME);
    }

    /**
     * Возвращает признак использования SMTP
     *
     * @return bool
     */
    public function getSmtp(): bool {
        return $this->_smtp;
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
     * Возвращает признак необходимости авторизации
     *
     * @return bool
     */
    public function getAuth(): bool {
        return $this->_auth;
    }

    /**
     * Возвращает способ защиты соединения
     *
     * @return string
     */
    public function getSecure(): string {
        return $this->_secure;
    }

    /**
     * Возвращает имя пользователя
     *
     * @return string
     */
    public function getUserName(): string {
        return $this->_userName;
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
     * Возвращает адрес отправителя
     *
     * @return string
     */
    public function getSendFrom(): string {
        return $this->_sendFrom;
    }

    /**
     * Возвращает имя отправителя
     *
     * @return string
     */
    public function getSenderName(): string {
        return $this->_senderName;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        $strings           = Strings::getInstance();
        $this->_smtp       = (bool)$data->{'smtp'} ?? self::DEFAULT_SMTP;
        $this->_host       = $data->{'host'} ?? self::DEFAULT_HOST;
        $this->_port       = $strings->stringToInteger($data->{'port'} ?? null, self::DEFAULT_PORT);
        $this->_auth       = (bool)$data->{'auth'} ?? self::DEFAULT_AUTH;
        $this->_secure     = $data->{'secure'} ?? self::DEFAULT_SECURE;
        $this->_userName   = $data->{'userName'} ?? self::DEFAULT_USERNAME;
        $this->_password   = $data->{'password'} ?? self::DEFAULT_PASSWORD;
        $this->_sendFrom   = $data->{'sendFrom'} ?? self::DEFAULT_SEND_FROM;
        $this->_senderName = $data->{'senderName'} ?? self::DEFAULT_SENDER_NAME;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Models\Config\MailerConfig
     */
    public static function getInstance(): MailerConfig {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof MailerConfig);
        return $result;
    }
}

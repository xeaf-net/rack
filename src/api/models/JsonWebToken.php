<?php declare(strict_types = 1);

/**
 * JsonWebToken.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Utils\Calendar;
use XEAF\Rack\API\Utils\Crypto;

/**
 * Модель данных JSON Web Token
 *
 * @property string $iss     Идентификатор выпускающей системы
 * @property string $sub     Дополнитеьная информация
 * @property array  $aud     Массив идентификаторов получателей
 * @property int    $exp     Время прекращения действия
 * @property int    $nbf     Время начала действия
 * @property int    $iat     Время выпуска
 * @property string $jti     Уникальный идентификатор
 * @property array  $payload Массив данных полезной нагрузки
 *
 * @package  XEAF\Rack\API\Models
 *
 * @since 1.0.4
 */
class JsonWebToken extends DataModel {

    /**
     * Идентификатор выпускающей системы
     * @var string
     */
    protected $_iss = '';

    /**
     * Дополнительная информация о выпускающей системе
     * @var string
     */
    protected $_sub = '';

    /**
     * Массив получателей ключа
     * @var array
     */
    protected $_aud = [];

    /**
     * Время прекращения срока действия
     * @var int
     */
    protected $_exp = 0;

    /**
     * Время начала строка действия
     * @var int
     */
    protected $_nbf = 0;

    /**
     * Время выпуска токена
     * @var int
     */
    protected $_iat = 0;

    /**
     * Уникальный идентификатор токена
     * @var string
     */
    protected $_jti = '';

    /**
     * Массив данных полезной нагрузки
     * @var array
     */
    protected $_payload = [];

    /**
     * Конструктор класса
     *
     * @param array $payload Массив данных полезной нагрузки
     */
    public function __construct(array $payload = []) {
        parent::__construct();
        $this->initialize();
        $this->_payload = $payload;
    }

    /**
     * Инициализирует поля JWT значениями по умолчанию
     *
     * @return void
     */
    protected function initialize(): void {
        $url        = PortalConfig::getInstance()->getUrl();
        $now        = Calendar::getInstance()->now();
        $this->_iss = $url;
        $this->_sub = $url;
        $this->_aud = [$url];
        $this->_exp = $now + Crypto::JWT_DEFAULT_LIFETIME;
        $this->_nbf = $now;
        $this->_iat = $now;
        $this->_jti = Crypto::getInstance()->generateUUIDv4();
    }

    /**
     * Возвращает идентификатор выпускающей системы
     *
     * @return string
     */
    public function getIss(): string {
        return $this->_iss;
    }

    /**
     * Задает идентификатор выпускающей системы
     *
     * @param string $iss Идентификатор выпускающей системы
     *
     * @return void
     */
    public function setIss(string $iss): void {
        $this->_iss = $iss;
    }

    /**
     * Возвращает дополнительную информацию о выпускающей системе
     *
     * @return string
     */
    public function getSub(): string {
        return $this->_sub;
    }

    /**
     * Задает дополнительную информацию о выпускающей системе
     *
     * @param string $sub Дополнительная информация
     *
     * @return void
     */
    public function setSub(string $sub): void {
        $this->_sub = $sub;
    }

    /**
     * Возвращает массив идентификаторов получателей
     *
     * @return array
     */
    public function getAud(): array {
        return $this->_aud;
    }

    /**
     * Задает массив идентификаторов получателей
     *
     * @param array $aud Массив идентификаторов получателей
     *
     * @return void
     */
    public function setAud(array $aud): void {
        $this->_aud = $aud;
    }

    /**
     * Возвращает время прекращения срока действия
     *
     * @return int
     */
    public function getExp(): int {
        return $this->_exp;
    }

    /**
     * Задает время прекращения срока действия
     *
     * @param int $exp Время прекращения срока действия
     *
     * @return void
     */
    public function setExp(int $exp): void {
        $this->_exp = $exp;
    }

    /**
     * Возвращает время начала срока действия
     *
     * @return int
     */
    public function getNbf(): int {
        return $this->_nbf;
    }

    /**
     * Задает время начала срока действия
     *
     * @param int $nbf Время начала срока действия
     *
     * @return void
     */
    public function setNbf(int $nbf): void {
        $this->_nbf = $nbf;
    }

    /**
     * Возвращает время выпуска токена
     *
     * @return int
     */
    public function getIat(): int {
        return $this->_iat;
    }

    /**
     * Задает время выпуска токена
     *
     * @param int $iat Время выпуска токена
     *
     * @return void
     */
    public function setIat(int $iat): void {
        $this->_iat = $iat;
    }

    /**
     * Возвращает уникальный идентификатор
     *
     * @return string
     */
    public function getJti(): string {
        return $this->_jti;
    }

    /**
     * Задает уникальный идентификатор
     *
     * @param string $jti Уникальный идентификатор
     *
     * @return void
     */
    public function setJti(string $jti): void {
        $this->_jti = $jti;
    }

    /**
     * Возвращает массив данных полезной нагрузки
     *
     * @return array
     */
    public function getPayload(): array {
        return $this->_payload;
    }

    /**
     * Задает массив данных полезной нагрузки
     *
     * @param array $payload Массив данных полезной нагрузки
     *
     * @return void
     */
    public function setPayload(array $payload): void {
        $this->_payload = $payload;
    }
}

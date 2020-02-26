<?php

/**
 * Crypto.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use Firebase\JWT\JWT;
use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\ICrypto;
use XEAF\Rack\API\Models\JsonWebToken;
use XEAF\Rack\Db\Utils\Exceptions\CryptoException;

/**
 * Реализует методы работы со случайными и шифрованными данными
 *
 * @package XEAF\Rack\API\Utils
 */
class Crypto implements ICrypto {

    /**
     * Идентификатор алгоритма хеширования
     */
    public const HASH_ALGO = 'sha256';

    /**
     * Алгоритм для подписи JWT по умолчнию
     */
    public const JWT_DEFAULT_ALGO = 'RS256';

    /**
     * Время жизни JWT по умолчанию
     */
    public const JWT_DEFAULT_LIFETIME = Calendar::SECONDS_PER_DAY;

    /**
     * Имя файла приватного ключа JWT
     */
    public const JWT_PRIVATE_FILE_NAME = 'jwt.private.key';

    /**
     * Имя файла публичного ключа JWT
     */
    public const JWT_PUBLIC_FILE_NAME = 'jwt.public.key';

    /**
     * Идентификатор алгоритма хеширования паролей
     */
    public const PASSWORD_ALGO = PASSWORD_DEFAULT;

    /**
     * Значение приватного ключа JWT
     * @var string
     */
    private $_jwtPrivateKey = '';

    /**
     * Значение публичного ключа JWT
     * @var string
     */
    private $_jwtPublicKey = '';

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function base64Encode(string $source): string {
        return base64_encode($source);
    }

    /**
     * @inheritDoc
     */
    public function base64Decode(string $base64): string {
        $result = base64_decode($base64);
        if ($result == false) {
            $result = '';
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function hash(string $data, $password = ''): string {
        return hash_hmac(self::HASH_ALGO, $data, $password);
    }

    /**
     * @inheritDoc
     */
    public function hashEquals(string $hash1, string $hash2): bool {
        return hash_equals($hash1, $hash2);
    }

    /**
     * @inheritDoc
     */
    public function randomBytes(int $length): string {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= chr(mt_rand(0, 255));
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function generateUUIDv4(): string {
        $data    = self::randomBytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Установка версии в 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Установка 6-7 битов в to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @inheritDoc
     */
    public function passwordHash(string $password): string {
        return password_hash($password, self::PASSWORD_ALGO);
    }

    /**
     * @inheritDoc
     */
    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * @inheritDoc
     */
    public function securityToken(): string {
        $uuid = Crypto::generateUUIDv4();
        return base64_encode($uuid);
    }

    /**
     * @inheritDoc
     */
    public function jwtPrivateKey(): string {
        if (!$this->_jwtPrivateKey) {
            $fileName             = __XEAF_RACK_CONFIG_DIR__ . '/' . self::JWT_PRIVATE_FILE_NAME;
            $this->_jwtPrivateKey = file_get_contents($fileName);
            if ($this->_jwtPrivateKey) {
                throw CryptoException::jwtPrivateKeyNotFound();
            }
        }
        return $this->_jwtPrivateKey;
    }

    /**
     * @inheritDoc
     */
    public function jwtPublicKey(): string {
        if (!$this->_jwtPublicKey) {
            $fileName             = __XEAF_RACK_CONFIG_DIR__ . '/' . self::JWT_PUBLIC_FILE_NAME;
            $this->_jwtPublicKey = file_get_contents($fileName);
            if ($this->_jwtPublicKey) {
                throw CryptoException::jwtPublicKeyNotFound();
            }
        }
        return $this->_jwtPublicKey;
    }

    /**
     * @inheritDoc
     */
    public function encodeJWT(JsonWebToken $jwt, string $privateKey = null, string $algo = self::JWT_DEFAULT_ALGO): ?string {
        $result = null;
        if ($jwt && $privateKey) {
            try {
                $data   = $jwt->toArray();
                $key    = ($privateKey) ? $privateKey : $this->jwtPrivateKey();
                $result = JWT::encode($data, $key, $algo);
            } catch (Throwable $exception) {
                $result = null;
                Logger::getInstance()->exception($exception);
                throw CryptoException::jwtEncryptionError($exception);
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function decodeJWT(?string $encodedJWT, string $publicKey = null, string $algo = self::JWT_DEFAULT_ALGO): ?JsonWebToken {
        $result = null;
        if ($encodedJWT && $publicKey) {
            try {
                $key    = ($publicKey) ? $publicKey : $this->jwtPublicKey();
                $data   = (array)JWT::decode($encodedJWT, $key, [$algo]);
                $result = new JsonWebToken($data);
            } catch (Throwable $exception) {
                $result = null;
                Logger::getInstance()->exception($exception);
                throw CryptoException::jwtDecryptionError($exception);
            }
        }
        return $result;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\ICrypto
     */
    public static function getInstance(): ICrypto {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ICrypto);
        return $result;
    }
}

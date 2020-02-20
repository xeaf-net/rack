<?php

/**
 * Crypto.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\ICrypto;

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
     * Идентификатор алгоритма хеширования паролей
     */
    public const PASSWORD_ALGO = PASSWORD_DEFAULT;

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    function base64Encode(string $source): string {
        return base64_encode($source);
    }

    /**
     * @inheritDoc
     */
    function base64Decode(string $base64): string {
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

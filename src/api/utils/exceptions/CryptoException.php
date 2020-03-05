<?php declare(strict_types = 1);

/**
 * CryptoException.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе методов шифрования данных
 *
 * @package XEAF\Rack\API\Utils\Exceptions
 *
 * @since 1.0.4
 */
class CryptoException extends Exception {

    /**
     * Не найден приватный ключ JWT
     */
    public const JWT_PRIVATE_KEY_NOT_FOUND = 'CPT001';

    /**
     * Не найден публичный ключ JWT
     */
    public const JWT_PUBLIC_KEY_NOT_FOUND = 'CPT002';

    /**
     * Ошибка шифрования JWT ключа
     */
    public const JWT_ENCRYPTION_ERROR = 'CPT003';

    /**
     * Ошика расшифровки JWT ключа
     */
    public const JWT_DECRYPTION_ERROR = 'CPT004';

    /**
     * Не найден приватный ключ JWT
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CryptoException
     */
    public static function jwtPrivateKeyNotFound(): self {
        return new self(self::JWT_PRIVATE_KEY_NOT_FOUND);
    }

    /**
     * Не найден публичный ключ JWT
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CryptoException
     */
    public static function jwtPublicKeyNotFound(): self {
        return new self(self::JWT_PUBLIC_KEY_NOT_FOUND);
    }

    /**
     * Ошибка шифрования JWT ключа
     *
     * @param \Throwable $reason Причина возникновения ошибки
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CryptoException
     */
    public static function jwtEncryptionError(Throwable $reason): self {
        return new self(self::JWT_ENCRYPTION_ERROR, [], $reason);
    }

    /**
     * Ошибка расшифровки JWT ключа
     *
     * @param \Throwable $reason Причина возникновения ошибки
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\CryptoException
     */
    public static function jwtDecryptionError(Throwable $reason): self {
        return new self(self::JWT_DECRYPTION_ERROR, [], $reason);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = '';
        switch ($code) {
            case self::JWT_PRIVATE_KEY_NOT_FOUND:
                $result = 'JWT private key not found.';
                break;
            case self::JWT_PUBLIC_KEY_NOT_FOUND:
                $result = 'JWT public key not found.';
                break;
            case self::JWT_ENCRYPTION_ERROR:
                $result = 'JWT encryption error.';
                break;
            case self::JWT_DECRYPTION_ERROR:
                $result = 'JWT decryption error.';
                break;
        }
        return $result;
    }
}

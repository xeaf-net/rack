<?php

/**
 * ICrypto.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use XEAF\Rack\API\Models\JsonWebToken;

/**
 * Описывает методы работы со случаными и шифрованными данными
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ICrypto extends IFactoryObject {

    /**
     * Преобразует строку символов в формат BASE64
     *
     * @param string $source Исходные данные
     *
     * @return string
     */
    function base64Encode(string $source): string;

    /**
     * Преобразует данные в формате BASE64 в строку символов
     *
     * @param string $base64 Данные в формате BASE64
     *
     * @return string
     */
    function base64Decode(string $base64): string;

    /**
     * Генерирует хеш на основе пароля и строковых данных
     *
     * @param string $data     Данные для хеша
     * @param string $password Пароль
     *
     * @return string
     */
    function hash(string $data, $password = ''): string;

    /**
     * Метод сравнение хешей
     *
     * @param string $hash1 Известный хеш
     * @param string $hash2 Сравниваемый хеш
     *
     * @return bool
     */
    function hashEquals(string $hash1, string $hash2): bool;

    /**
     * Возвращает случайно сгенерированную последовательность байтов
     *
     * @param int $length Длина строки
     *
     * @return string
     */
    function randomBytes(int $length): string;

    /**
     * Генерирует UUID версии 4
     *
     * @return string
     */
    function generateUUIDv4(): string;

    /**
     * Возвращает хеш пароля
     *
     * @param string $password Пароль
     *
     * @return string
     */
    function passwordHash(string $password): string;

    /**
     * Проверяет соответствие пароля хешу
     *
     * @param string $password Пароль
     * @param string $hash     Хеш пароля
     *
     * @return bool
     */
    function verifyPassword(string $password, string $hash): bool;

    /**
     * Генерирует значение токена безопасности
     *
     * @return string
     */
    function securityToken(): string;

    /**
     * Загружает данные приватного ключа JWT
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\CryptoException
     *
     * @since 1.0.4
     */
    function jwtPrivateKey(): string;

    /**
     * Загружает данные публичного ключа JWT
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\CryptoException
     *
     * @since 1.0.4
     */
    function jwtPublicKey(): string;

    /**
     * Кодирует и подписывает новый JWT
     *
     * ssh-keygen -t rsa -b 4096 -m PEM -f jwtRS256.key
     * # Don't add passphrase
     * openssl rsa -in jwtRS256.key -pubout -outform PEM -out jwtRS256.key.pub
     * cat jwtRS256.key
     * cat jwtRS256.key.pub
     *
     * @param \XEAF\Rack\API\Models\JsonWebToken $jwt        Объект JWT
     * @param string|null                        $privateKey Закрытый ключ
     * @param string                             $algo       Алгоритм
     *
     * @return string|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CryptoException
     *
     * @since 1.0.4
     */
    function encodeJWT(JsonWebToken $jwt, string $privateKey = null, string $algo = self::JWT_DEFAULT_ALGO): ?string;

    /**
     * Расшифровывает и проверят подпись JWT
     *
     * @param string      $encodedJWT Текст зашифрованного JWT
     * @param string|null $publicKey  Открытый ключ
     * @param string      $algo       Алгоритм
     *
     * @return \XEAF\Rack\API\Models\JsonWebToken|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CryptoException
     *
     * @since 1.0.4
     */
    function decodeJWT(string $encodedJWT, string $publicKey = null, string $algo = self::JWT_DEFAULT_ALGO): ?JsonWebToken;
}

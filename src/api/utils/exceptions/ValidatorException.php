<?php declare(strict_types = 1);

/**
 * ValidatorException.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Исключения проверки корректности значений
 *
 * @package  XEAF\Rack\API\Utils\Exceptions
 */
class ValidatorException extends FormException {

    /**
     * Значение не может быть пустым
     */
    private const EMPTY_VALUE = 'ValidatorException.EMPTY_VALUE';

    /**
     * Некорректное значение
     */
    private const INVALID_VALUE = 'ValidatorException.INVALID_VALUE';

    /**
     * Значение вне допустимого диапазона
     */
    private const INVALID_RANGE = 'ValidatorException.INVALID_RANGE';

    /**
     * Некорректный формат значения
     */
    private const INVALID_FORMAT = 'ValidatorException.INVALID_FORMAT';

    /**
     * Некорректная длина строки
     */
    private const INVALID_STRING_LENGTH = 'ValidatorException.INVALID_STRING_LENGTH';

    /**
     * Некорректный формат логического выражения
     */
    private const INVALID_BOOLEAN_FORMAT = 'ValidatorException.INVALID_BOOLEAN_FORMAT';

    /**
     * Некорректный формат целого числа
     */
    private const INVALID_INTEGER_FORMAT = 'ValidatorException.INVALID_INTEGER_FORMAT';

    /**
     * Некорректный формат числа
     */
    private const INVALID_NUMERIC_FORMAT = 'ValidatorException.INVALID_NUMERIC';

    /**
     * Некорретный адрес электронной почты
     */
    private const INVALID_EMAIL_FORMAT = 'ValidatorException.INVALID_EMAIL_FORMAT';

    /**
     * Некорретный адрес электронной почты
     */
    private const INVALID_PHONE_PHONE = 'ValidatorException.INVALID_PHONE_FORMAT';

    /**
     * Такое значение уже существует
     */
    private const VALUE_ALREADY_EXISTS = 'ValidatorException.VALUE_ALREADY_EXISTS';

    /**
     * Конструктор класса
     *
     * @param int         $status  Код состояния
     * @param string      $message Имя языковой переменной
     * @param array       $args    Аргументы текста сообщения
     * @param string|null $tag     Тег
     */
    protected function __construct(int $status, string $message, array $args = [], string $tag = null) {
        parent::__construct($status, $message, $args, $tag);
    }

    /**
     * Значение не может быть пустым
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function emptyValue(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::EMPTY_VALUE, [], $tag);
    }

    /**
     * Некорректное значение
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidValue(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_VALUE, [], $tag);
    }

    /**
     * Значение вне допустимого диапазона
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidRange(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_RANGE, [], $tag);
    }

    /**
     * Некорректный формат значения
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidFormat(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_FORMAT, [], $tag);
    }

    /**
     * Некорректная длина строки
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidStringLength(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_STRING_LENGTH, [], $tag);
    }

    /**
     * Некорректный формат логического выражения
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidBooleanFormat(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_BOOLEAN_FORMAT, [], $tag);
    }

    /**
     * Некорректный формат целого числа
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidIntegerFormat(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_INTEGER_FORMAT, [], $tag);
    }

    /**
     * Некорректный формат числа
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidNumericFormat(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_NUMERIC_FORMAT, [], $tag);
    }

    /**
     * Некорректный формат адреса электронной почты
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidEmailFormat(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_EMAIL_FORMAT, [], $tag);
    }

    /**
     * Некорректный формат номера телефона
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function invalidPhoneFormat(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::INVALID_PHONE_PHONE, [], $tag);
    }

    /**
     * Такое значение уже существует
     *
     * @param string|null $tag Тег
     *
     * @return static
     */
    public static function valueAlreadyExists(string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, self::VALUE_ALREADY_EXISTS, [], $tag);
    }
}

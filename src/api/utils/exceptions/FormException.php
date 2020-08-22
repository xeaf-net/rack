<?php declare(strict_types = 1);

/**
 * FormException.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use XEAF\Rack\API\Core\Exception;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Models\Results\FormResult;
use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Исключения проверки параметров форм ввода
 *
 * @package  XEAF\Rack\API\Utils\Exceptions
 */
class FormException extends Exception {

    /**
     * Код ошибки для исключения
     */
    private const ERROR_CODE = 'FORM';

    /**
     * Текст сообщения об ошибке
     */
    private const ERROR_MESSAGE = 'Form verification error';

    /**
     * Результат исполнения
     * @var \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    private $_result;

    /**
     * Конструктор класса
     *
     * @param int    $status  Код состояния
     * @param string $langVar Имя языковой переменной
     * @param array  $args    Аргументы текста сообщения
     * @param string $tag     Тег
     */
    public function __construct(int $status, string $langVar, array $args = [], string $tag = '') {
        $code = self::ERROR_CODE . $status;
        parent::__construct($code);
        $this->_result = new FormResult($status, $langVar, $args, $tag);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        return self::ERROR_MESSAGE . $code . '.';
    }

    /**
     * Возвращает результат исполнения действия
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    public function getResult(): ?IActionResult {
        return $this->_result;
    }

    /**
     * Создает исключение, возвращающее ошибку 400 - BAD REQUEST
     *
     * @param string $langVar Языковая формата сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function badRequest(string $langVar = '', array $args = [], string $tag = ''): self {
        return new self(HttpResponse::BAD_REQUEST, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 401 - UNAUTHORIZED
     *
     * @param string $langVar Языковая формата сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function unauthorized(string $langVar = '', array $args = [], string $tag = ''): self {
        return new self(HttpResponse::UNAUTHORIZED, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 403 - FORBIDDEN
     *
     * @param string $langVar Языковая формата сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function forbidden(string $langVar = '', array $args = [], string $tag = ''): self {
        return new self(HttpResponse::FORBIDDEN, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 404 - NOT FOUND
     *
     * @param string $langVar Языковая формата сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function notFound(string $langVar = '', array $args = [], string $tag = ''): self {
        return new self(HttpResponse::NOT_FOUND, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 409 - CONFLICT
     *
     * @param string $langVar Языковая формата сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function conflict(string $langVar = '', array $args = [], string $tag = ''): self {
        return new self(HttpResponse::CONFLICT, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 500 - INTERNAL SERVER ERROR
     *
     * @param string $langVar Языковая формата сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function internalServerError(string $langVar = '', array $args = [], string $tag = ''): self {
        return new self(HttpResponse::FATAL_ERROR, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 501 - NOT IMPLEMENTED
     *
     * @param string $langVar Языковая формата сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function notImplemented(string $langVar = '', array $args = [], string $tag = ''): self {
        return new self(HttpResponse::NOT_IMPLEMENTED, $langVar, $args, $tag);
    }
}

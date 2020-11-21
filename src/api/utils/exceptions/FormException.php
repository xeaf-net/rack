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
use XEAF\Rack\API\Utils\Localization;

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
     * Формат сообщения об ошибке
     */
    private const ERROR_FORMAT = 'Form verification error [%s].';

    /**
     * Ошибка запроса
     */
    private const BAD_REQUEST = 'FormException.BAD_REQUEST';

    /**
     * Пользователь не авторизован
     */
    private const UNAUTHORIZED = 'FormException.UNAUTHORIZED';

    /**
     * Доступ запрещен
     */
    private const FORBIDDEN = 'FormException.FORBIDDEN';

    /**
     * Данные не найдены
     */
    private const NOT_FOUND = 'FormException.NOT_FOUND';

    /**
     * Конфликт данных
     */
    private const CONFLICT = 'FormException.CONFLICT';

    /**
     * Внутренняя ошибка
     */
    private const INTERNAL_ERROR = 'FormException.INTERNAL_ERROR';

    /**
     * Функционал не реализован
     */
    private const NOT_IMPLEMENTED = 'FormException.NOT_IMPLEMENTED';

    /**
     * Результат исполнения
     * @var \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    private $_result;

    /**
     * Конструктор класса
     *
     * @param int         $status  Код состояния
     * @param string      $langVar Имя языковой переменной
     * @param array       $args    Аргументы текста сообщения
     * @param string|null $tag     Тег
     */
    public function __construct(int $status, string $langVar, array $args = [], string $tag = null) {
        $code = self::ERROR_CODE . $status;
        $this->registerLanguageClasses();
        $this->_result = new FormResult($status, $langVar, $args, $tag);
        parent::__construct($code);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $msg = $this->_result->getMessage();
        return ($msg) ? $msg : vsprintf(self::ERROR_FORMAT, [$code]);
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
     * Добавляет поддержку языковых переменных исключения
     *
     * @return void
     */
    private function registerLanguageClasses(): void {
        $l10n      = Localization::getInstance();
        $className = get_class($this);
        $l10n->registerLanguageClass(self::class);
        $l10n->registerLanguageClass($className);
    }

    /**
     * Создает исключение, возвращающее ошибку 400 - BAD REQUEST
     *
     * @param string      $langVar Языковая переменная формата сообщения
     * @param array       $args    Аргументы сообщения
     * @param string|null $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function badRequest(string $langVar = self::BAD_REQUEST, array $args = [], string $tag = null): self {
        return new self(HttpResponse::BAD_REQUEST, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 401 - UNAUTHORIZED
     *
     * @param string      $langVar Языковая переменная формата сообщения
     * @param array       $args    Аргументы сообщения
     * @param string|null $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function unauthorized(string $langVar = self::UNAUTHORIZED, array $args = [], string $tag = null): self {
        return new self(HttpResponse::UNAUTHORIZED, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 403 - FORBIDDEN
     *
     * @param string      $langVar Языковая переменная формата сообщения
     * @param array       $args    Аргументы сообщения
     * @param string|null $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function forbidden(string $langVar = self::FORBIDDEN, array $args = [], string $tag = null): self {
        return new self(HttpResponse::FORBIDDEN, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 404 - NOT FOUND
     *
     * @param string      $langVar Языковая переменная формата сообщения
     * @param array       $args    Аргументы сообщения
     * @param string|null $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function notFound(string $langVar = self::NOT_FOUND, array $args = [], string $tag = null): self {
        return new self(HttpResponse::NOT_FOUND, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 409 - CONFLICT
     *
     * @param string      $langVar Языковая переменная формата сообщения
     * @param array       $args    Аргументы сообщения
     * @param string|null $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function conflict(string $langVar = self::CONFLICT, array $args = [], string $tag = null): self {
        return new self(HttpResponse::CONFLICT, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 500 - INTERNAL SERVER ERROR
     *
     * @param string      $langVar Языковая переменная формата сообщения
     * @param array       $args    Аргументы сообщения
     * @param string|null $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function internalServerError(string $langVar = self::INTERNAL_ERROR, array $args = [], string $tag = null): self {
        return new self(HttpResponse::FATAL_ERROR, $langVar, $args, $tag);
    }

    /**
     * Создает исключение, возвращающее ошибку 501 - NOT IMPLEMENTED
     *
     * @param string      $langVar Языковая переменная формата сообщения
     * @param array       $args    Аргументы сообщения
     * @param string|null $tag     Тег
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public static function notImplemented(string $langVar = self::NOT_IMPLEMENTED, array $args = [], string $tag = null): self {
        return new self(HttpResponse::NOT_IMPLEMENTED, $langVar, $args, $tag);
    }
}

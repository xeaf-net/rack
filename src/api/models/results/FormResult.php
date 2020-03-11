<?php declare(strict_types = 1);

/**
 * FormResult.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Localization;

/**
 * Реализует методы результата возвращающего информацию о ошибке форм ввода
 *
 * @package  XEAF\Rack\API\Models\Results
 */
class FormResult extends ErrorResult {

    /**
     * Тег
     * @var string
     */
    private $_tag = '';

    /**
     * Конструктор класса
     *
     * @param int    $status  Код состояния HTTP
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     */
    public function __construct(int $status, string $langVar, array $args = [], string $tag = '') {
        $message = Localization::getInstance()->fmtLanguageVar($langVar, $args);
        parent::__construct($status, $message);
        $this->_tag = $tag;
    }

    /**
     * Возвращает тег
     *
     * @return string
     */
    public function getTag(): string {
        return $this->_tag;
    }

    /**
     * Задает значение тега
     *
     * @param string $tag Тег
     */
    public function setTag(string $tag): void {
        $this->_tag = $tag;
    }

    /**
     * Отправляет код 200 - OK
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function ok(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::OK, $langVar, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function badRequest(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::BAD_REQUEST, $langVar, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function unauthorized(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::UNAUTHORIZED, $langVar, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function forbidden(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::FORBIDDEN, $langVar, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notFound(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::NOT_FOUND, $langVar, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 409 - CONFLICT
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function conflict(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::CONFLICT, $langVar, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function internalServerError(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::FATAL_ERROR, $langVar, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @param string $langVar Языковая переменная или формат сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notImplemented(string $langVar = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::NOT_IMPLEMENTED, $langVar, $args, $tag);
    }
}

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
use XEAF\Rack\API\Utils\Serializer;
use XEAF\Rack\API\Utils\Strings;

/**
 * Реализует методы результата возвращающего информацию о ошибке форм ввода
 *
 * @property string $code Языковая переменная кода ошибки
 * @property string $tag  Дополнительная информация
 *
 * @package  XEAF\Rack\API\Models\Results
 */
class FormResult extends ErrorResult {

    /**
     * Код ошибки
     * @var string
     */
    private $_code;

    /**
     * Тег
     * @var string
     */
    private $_tag;

    /**
     * Конструктор класса
     *
     * @param int    $status Код состояния HTTP
     * @param string $code   Языковая переменная кода ошибки или формат сообщения
     * @param array  $args   Аргументы сообщения
     * @param string $tag    Тег
     */
    public function __construct(int $status, string $code, array $args = [], string $tag = '') {
        $message = Localization::getInstance()->fmtLanguageVar($code, $args);
        parent::__construct($status, $message);
        $this->_code = $code;
        $this->_tag  = $tag;
    }

    /**
     * Возвращает языковую перенную кода ошибки
     *
     * @return string
     */
    public function getCode(): string {
        return $this->_code;
    }

    /**
     * Задает языковую переменную кода ошибки
     *
     * @param string $code Языковая переменная кода ошибки
     *
     * @return void
     */
    public function setCode(string $code): void {
        $this->_code = $code;
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
     * @inheritDoc
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function processResult(): void {
        $strings = Strings::getInstance();
        $code    = $this->getCode();
        if ($strings->startsWith($code, 'error.')) {
            $code = substr($code, mb_strlen($code));
        }

        $result = [
            'error'   => $code,
            'message' => $this->getMessage(),
            'tag'     => $this->getTag(),
        ];

        $headers    = HttpResponse::getInstance();
        $headerCode = $this->getStatusCode();
        $headers->responseCode($headerCode);
        $headers->authenticateBearer($headerCode);
        $headers->contentJSON();

        if ($strings->isEmpty($result['message'])) {
            unset($result['message']);
        }
        if ($strings->isEmpty($result['tag'])) {
            unset($result['tag']);
        }

        print Serializer::getInstance()->jsonArrayEncode($result);
    }

    /**
     * Отправляет код 200 - OK
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function ok(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::OK, $code, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function badRequest(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::BAD_REQUEST, $code, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function unauthorized(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::UNAUTHORIZED, $code, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function forbidden(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::FORBIDDEN, $code, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notFound(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::NOT_FOUND, $code, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 409 - CONFLICT
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function conflict(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::CONFLICT, $code, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function internalServerError(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::FATAL_ERROR, $code, $args, $tag);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @param string $code Языковая переменная кода ошибки или формат сообщения
     * @param array  $args Аргументы сообщения
     * @param string $tag  Тег
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notImplemented(string $code = '', array $args = [], string $tag = ''): IActionResult {
        return new FormResult(HttpResponse::NOT_IMPLEMENTED, $code, $args, $tag);
    }
}

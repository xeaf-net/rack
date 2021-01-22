<?php declare(strict_types = 1);

/**
 * ErrorResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего информацию о ошибке
 *
 * @property string $message Текст сообщения об ошибке
 *
 * @package XEAF\Rack\API\Models\Results
 */
class ErrorResult extends StatusResult {

    /**
     * Текст сообщения об ошибке
     * @var string
     */
    protected string $_message;

    /**
     * Конструктор класса
     *
     * @param int    $status  Код состояния HTTP
     * @param string $message Сообщение об ошибке
     */
    public function __construct(int $status, string $message) {
        parent::__construct($status);
        $this->_message = $message;
    }

    /**
     * Возвращает сообщение об ошибке
     *
     * @return string
     */
    public function getMessage(): string {
        return $this->_message;
    }

    /**
     * Задает сообщение об ошибке
     *
     * @param string $message Текст сообщения об ошибке
     *
     * @return void
     */
    public function setMessage(string $message): void {
        $this->_message = $message;
    }

    /**
     * @inheritDoc
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function processResult(): void {
        $result = [
            'status'  => $this->getStatusCode(),
            'message' => $this->getMessage()
        ];

        $headers    = HttpResponse::getInstance();
        $headerCode = $this->getStatusCode();
        $headers->responseCode($headerCode);
        $headers->authenticateBearer($headerCode);

        $headers->contentJSON();

        if ($result['message'] == '') {
            unset($result['message']);
        }

        if (!$this->_headersOnly) {
            print Serializer::getInstance()->jsonArrayEncode($result);
        }
    }

    /**
     * Отправляет код 200 - OK
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function ok(): IActionResult {
        return new ErrorResult(HttpResponse::OK, '');
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @param string $message Текст сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function badRequest(string $message = ''): IActionResult {
        return new ErrorResult(HttpResponse::BAD_REQUEST, $message);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @param string $message Текст сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function unauthorized(string $message = ''): IActionResult {
        return new ErrorResult(HttpResponse::UNAUTHORIZED, $message);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @param string $message Текст сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function forbidden(string $message = ''): IActionResult {
        return new ErrorResult(HttpResponse::FORBIDDEN, $message);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @param string $message Текст сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notFound(string $message = ''): IActionResult {
        return new ErrorResult(HttpResponse::NOT_FOUND, $message);
    }

    /**
     * Создает объект, возвращающий ошибку 409 - CONFLICT
     *
     * @param string $message Текст сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function conflict(string $message = ''): IActionResult {
        return new ErrorResult(HttpResponse::CONFLICT, $message);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @param string $message Текст сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function internalServerError(string $message = ''): IActionResult {
        return new ErrorResult(HttpResponse::FATAL_ERROR, $message);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @param string $message Текст сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notImplemented(string $message = ''): IActionResult {
        return new ErrorResult(HttpResponse::NOT_IMPLEMENTED, $message);
    }
}

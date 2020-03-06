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

use XEAF\Rack\API\Core\ActionResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего информацию о ошибке
 *
 * @property string $message Текст сообщения об ошибке
 * @property string $tag     Тег
 *
 * @package XEAF\Rack\API\Models\Results
 */
class ErrorResult extends StatusResult {

    /**
     * Текст сообщения об ошибке
     * @var string
     */
    protected $_message = '';

    /**
     * Тег
     * @var string
     */
    protected $_tag = '';

    /**
     * Конструктор класса
     *
     * @param int    $status  Код состояния HTTP
     * @param string $message Сообщение об ошибке
     * @param string $tag     Тег
     */
    public function __construct(int $status, string $message = '', string $tag = '') {
        parent::__construct($status);
        $this->_message = $message;
        $this->_tag     = $tag;
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
     * Возвращает тег
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
        $result = [
            'status'  => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'tag'     => $this->getTag()
        ];

        $headers = HttpResponse::getInstance();
        $headers->responseCode($this->getHeaderStatusCode());
        $headers->contentJSON();

        if ($result['message'] == '') {
            $result['message'] = HttpResponse::MESSAGES[$this->getStatusCode()] ?? '';
        }
        if ($result['tag'] == '') {
            unset($result['tag']);
        }

        print Serializer::getInstance()->jsonArrayEncode($result);
    }

    /**
     * Возвращает код состояния HTTP для установки в заголовок
     *
     * @return int
     */
    protected function getHeaderStatusCode(): int {
        return $this->getStatusCode();
    }

    /**
     * Результат успешной операции
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function ok(): ActionResult {
        return new ErrorResult(HttpResponse::OK);
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function badRequest(): ActionResult {
        return new ErrorResult(HttpResponse::BAD_REQUEST);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function unauthorized(): ActionResult {
        return new ErrorResult(HttpResponse::UNAUTHORIZED);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function forbidden(): ActionResult {
        return new ErrorResult(HttpResponse::FORBIDDEN);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notFound(): ActionResult {
        return new ErrorResult(HttpResponse::NOT_FOUND);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function internalServerError(): ActionResult {
        return new ErrorResult(HttpResponse::FATAL_ERROR);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notImplemented(): ActionResult {
        return new ErrorResult(HttpResponse::NOT_IMPLEMENTED);
    }
}

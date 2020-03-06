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

use XEAF\Rack\API\Core\ActionResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Localization;

/**
 * Реализует методы результата возвращающего информацию о ошибке форм ввода
 *
 * @package  XEAF\Rack\API\Models\Results
 */
class FormResult extends ErrorResult {

    /**
     * Конструктор класса
     *
     * @param int    $status  Код состояния HTTP
     * @param string $langFmt Имя языковой переменной с форматом сообщения
     * @param array  $args    Аргументы сообщения
     * @param string $tag     Тег
     */
    public function __construct(int $status, string $langFmt = '', array $args = [], string $tag = '') {
        $message = ($langFmt) ? Localization::getInstance()->fmtLanguageVar($langFmt, $args) : '';
        parent::__construct($status, $message, $tag);
    }

    /**
     * Возвращает код состояния HTTP для установки в заголовок
     *
     * @return int
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function getHeaderStatusCode(): int {
        return HttpResponse::OK; // Всегда
    }

    /**
     * Сообщение об ошибке аргумента
     *
     * @param string $id      Идентификатор аргумента
     * @param string $langFmt Имя языковой переменной с форматом сообщения
     * @param array  $args    Аргументы сообщения
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function argument(string $id, string $langFmt, array $args = []): FormResult {
        return new FormResult(HttpResponse::BAD_REQUEST, $langFmt, $args, $id);
    }

    /**
     * Результат успешной операции
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function ok(): ActionResult {
        return new FormResult(HttpResponse::OK);
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function badRequest(): ActionResult {
        return new FormResult(HttpResponse::BAD_REQUEST);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function unauthorized(): ActionResult {
        return new FormResult(HttpResponse::UNAUTHORIZED);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function forbidden(): ActionResult {
        return new FormResult(HttpResponse::FORBIDDEN);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notFound(): ActionResult {
        return new FormResult(HttpResponse::NOT_FOUND);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function internalServerError(): ActionResult {
        return new FormResult(HttpResponse::FATAL_ERROR);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function notImplemented(): ActionResult {
        return new FormResult(HttpResponse::NOT_IMPLEMENTED);
    }
}

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
     * Возвращает код состояния HTTP для устаовки в заголовок
     *
     * @return int
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function getHeaderStatusCode(): int {
        return HttpResponse::OK; // Всегда
    }

    /**
     * Результат успешной операции
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function ok(): self {
        return new self(HttpResponse::OK);
    }

    /**
     * Результат ошибочных параметров запроса
     *
     * @param string $tag     Тег
     * @param string $langFmt Имя языковой переменной с форматом сообщения
     * @param array  $args    Аргументы сообщения
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function argument(string $tag, string $langFmt, array $args = []): self {
        return new self(HttpResponse::BAD_REQUEST, $langFmt, $args, $tag);
    }

    /**
     * Результат - 400 BAD REQUEST
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function badRequest(): self {
        return new self(HttpResponse::BAD_REQUEST);
    }

    /**
     * Результат - 401 UNAUTHORIZED
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function unauthorized(): self {
        return new self(HttpResponse::UNAUTHORIZED);
    }

    /**
     * Результат - 403 FORBIDDEN
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function forbidden(): self {
        return new self(HttpResponse::FORBIDDEN);
    }

    /**
     * Результат - 404 NOT FOUND
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function notFound(): self {
        return new self(HttpResponse::NOT_FOUND);
    }

    /**
     * Результат - 500 INTERNAL SERVER ERROR
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function internalServerError(): self {
        return new self(HttpResponse::FATAL_ERROR);
    }
}

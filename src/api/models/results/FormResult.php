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
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего информацию о ошибке форм ввода
 *
 * @package  XEAF\Rack\API\Models\Results
 */
class FormResult extends ErrorResult {

    /**
     * Конструктор класса
     *
     * @param string $langFmt Имя языковой переменной с форматом сообщения
     * @param array  $args    Аргументы сообщения
     * @param int    $status  Код ошибки
     */
    public function __construct(string $langFmt = '', array $args = [], int $status = HttpResponse::OK) {
        $message = ($langFmt) ? Localization::getInstance()->fmtLanguageVar($langFmt, $args) : '';
        parent::__construct($message, $status);
    }

    /**
     * @inheritDoc
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function processResult(): void {
        $headers    = HttpResponse::getInstance();
        $serializer = Serializer::getInstance();
        $headers->responseCode(HttpResponse::OK); // Всегда 200
        if ($this->getMessage() || !$this->getObjectErrors()->isEmpty()) {
            $headers->contentJSON();
            print $serializer->jsonDataObjectEncode($this);
        }
    }

    /**
     * Результат успешной операции
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     * @noinspection PhpMethodNamingConventionInspection
     */
    public static function ok(): self {
        return new FormResult();
    }

    /**
     * Результат ошибочных параметров запроса
     *
     * @param string $id      Идентификатор объекта
     * @param string $langFmt Имя языковой переменной с форматом сообщения
     * @param array  $args    Аргументы сообщения
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function argument(string $id, string $langFmt, array $args = []): self {
        $l10n    = Localization::getInstance();
        $message = $l10n->fmtLanguageVar($langFmt, $args);
        if ($id == '') {
            $result = self::badRequest($langFmt, $args);
        } else {
            $result = self::badRequest();
            $result->addObjectError($id, $message);
        }
        return $result;
    }

    /**
     * Результат - 400 BAD REQUEST
     *
     * @param string $langFmt Имя языковой переменной с форматом сообщения
     * @param array  $args    Аргументы сообщения
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function badRequest(string $langFmt = '', array $args = []): self {
        return new self($langFmt, $args, HttpResponse::BAD_REQUEST);
    }

    /**
     * Результат - 401 UNAUTHORIZED
     *
     * @return \XEAF\Rack\API\Models\Results\FormResult
     */
    public static function unauthorized(): self {
        return new self('', [], HttpResponse::UNAUTHORIZED);
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

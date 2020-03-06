<?php declare(strict_types = 1);

/**
 * StatusResult.php
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

/**
 * Реализует методы результата возвращающего код статуса
 *
 * @package XEAF\Rack\API\Models\Results
 */
class StatusResult extends ActionResult {

    /**
     * @inheritDoc
     */
    public function processResult(): void {
        $headers = HttpResponse::getInstance();
        $headers->responseCode($this->getStatusCode());
    }

    /**
     * Создает объект, возвращающий код завершения 200 - OK
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     */
    public static function ok(): ActionResult {
        return new self(HttpResponse::OK);
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     */
    public static function badRequest(): ActionResult {
        return new self(HttpResponse::BAD_REQUEST);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     */
    public static function unauthorized(): ActionResult {
        return new self(HttpResponse::UNAUTHORIZED);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     */
    public static function forbidden(): ActionResult {
        return new self(HttpResponse::FORBIDDEN);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     */
    public static function notFound(): ActionResult {
        return new self(HttpResponse::NOT_FOUND);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     */
    public static function internalServerError(): ActionResult {
        return new self(HttpResponse::FATAL_ERROR);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     */
    public static function notImplemented(): ActionResult {
        return new self(HttpResponse::NOT_IMPLEMENTED);
    }
}

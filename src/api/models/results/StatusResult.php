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
use XEAF\Rack\API\Interfaces\IActionResult;
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
        $code    = $this->getStatusCode();
        $headers->responseCode($code);
        $headers->authenticateBearer($code);
    }

    /**
     * Создает объект, возвращающий код завершения 200 - OK
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function ok(): IActionResult {
        return new StatusResult(HttpResponse::OK);
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function badRequest(): IActionResult {
        return new StatusResult(HttpResponse::BAD_REQUEST);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function unauthorized(): IActionResult {
        return new StatusResult(HttpResponse::UNAUTHORIZED);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function forbidden(): IActionResult {
        return new StatusResult(HttpResponse::FORBIDDEN);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function notFound(): IActionResult {
        return new StatusResult(HttpResponse::NOT_FOUND);
    }

    /**
     * Создает объект, возвращающий ошибку 409 - CONFLICT
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function conflict(): IActionResult {
        return new StatusResult(HttpResponse::CONFLICT);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function internalServerError(): IActionResult {
        return new StatusResult(HttpResponse::FATAL_ERROR);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function notImplemented(): IActionResult {
        return new StatusResult(HttpResponse::NOT_IMPLEMENTED);
    }
}

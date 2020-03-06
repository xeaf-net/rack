<?php declare(strict_types = 1);

/**
 * CommonErrorsTrait.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Traits;

use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Содердит статические методы генерации результатов общих ошибок
 *
 * @package  XEAF\Rack\API\Traits
 */
trait CommonErrorsTrait {

    /**
     * Создает объект результата исполнения действия
     *
     * @param int $statusCode
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    private static function createTraitResult(int $statusCode): IActionResult {
        $className = __CLASS__;
        $result    = new $className($statusCode);
        assert($result instanceof IActionResult);
        return $result;
    }

    /**
     * Создает объект, возвращающий код завершения 200 - OK
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function ok(): IActionResult {
        return self::createTraitResult(HttpResponse::OK);
    }

    /**
     * Создает объект, возвращающий ошибку 400 - BAD REQUEST
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function badRequest(): IActionResult {
        return self::createTraitResult(HttpResponse::BAD_REQUEST);
    }

    /**
     * Создает объект, возвращающий ошибку 401 - UNAUTHORIZED
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function unauthorized(): IActionResult {
        return self::createTraitResult(HttpResponse::UNAUTHORIZED);
    }

    /**
     * Создает объект, возвращающий ошибку 403 - FORBIDDEN
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function forbidden(): IActionResult {
        return self::createTraitResult(HttpResponse::FORBIDDEN);
    }

    /**
     * Создает объект, возвращающий ошибку 404 - NOT FOUND
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function notFound(): IActionResult {
        return self::createTraitResult(HttpResponse::NOT_FOUND);
    }

    /**
     * Создает объект, возвращающий ошибку 500 - INTERNAL SERVER ERROR
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function internalServerError(): IActionResult {
        return self::createTraitResult(HttpResponse::FATAL_ERROR);
    }

    /**
     * Создает объект, возвращающий ошибку 501 - NOT IMPLEMENTED
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function notImplemented(): IActionResult {
        return self::createTraitResult(HttpResponse::NOT_IMPLEMENTED);
    }
}

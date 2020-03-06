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
use XEAF\Rack\API\Traits\CommonErrorsTrait;
use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Реализует методы результата возвращающего информацию о ошибке форм ввода
 *
 * @package  XEAF\Rack\API\Models\Results
 */
class FormResult extends ErrorResult {

    use CommonErrorsTrait;

    /**
     * @inheritDoc
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
     * @param string $langFmt Имя языковой переменной или формат сообщения
     * @param array  $args    Аргументы сообщения
     *
     * @return \XEAF\Rack\API\Core\ActionResult
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public static function argument(string $id, string $langFmt, array $args = []): ActionResult {
        return new FormResult(HttpResponse::BAD_REQUEST, $langFmt, $args, $id);
    }
}

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

use XEAF\Rack\API\Traits\CommonErrorsTrait;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Localization;

/**
 * Реализует методы результата возвращающего информацию о ошибке форм ввода
 *
 * @package  XEAF\Rack\API\Models\Results
 */
class FormResult extends ErrorResult {

    use CommonErrorsTrait;

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
}

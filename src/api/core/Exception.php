<?php declare(strict_types = 1);

/**
 * Exception.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use Throwable;

/**
 * Реализует базовые методы для всех классов исключений
 *
 * @package XEAF\Rack\API\Core
 */
abstract class Exception extends \Exception {

    /**
     * Текст сообщения о неизвестной ошибке
     */
    protected const UNKNOWN_ERROR_MESSAGE = 'Unknown Internal Error.';

    /**
     * Конструктор класса
     *
     * @param string          $code     Код исключения
     * @param array           $args     Аргументы текста сообщения
     * @param \Throwable|null $previous Причина возникновения исключения
     */
    protected function __construct(string $code, array $args = [], Throwable $previous = null) {
        $format = $this->getFormat($code);
        if (!$format) {
            $message = self::UNKNOWN_ERROR_MESSAGE;
        } else {
            $message = vsprintf($format, $args);
        }
        parent::__construct($message, 0, $previous);
        $this->code = $code;
    }

    /**
     * Возвращает формат сообщения по поду исключения
     *
     * @param string $code Код исключения
     *
     * @return string|null
     */
    abstract protected function getFormat(string $code): ?string;
}

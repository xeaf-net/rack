<?php declare(strict_types = 1);

/**
 * ApplicationException.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use XEAF\Rack\API\Core\Exception;
use XEAF\Rack\API\Interfaces\IActionResult;

/**
 * Исключения с результатом исполнения действия
 *
 * @package  XEAF\Rack\API\Utils\Exceptions
 */
class ResultException extends Exception {

    /**
     * Код ошибки
     */
    private const ERROR_CODE = 'RES';

    /**
     * Результат исполнения приложения
     * @var \XEAF\Rack\API\Interfaces\IActionResult
     */
    private IActionResult $_result;

    /**
     * Конструктор класса
     *
     * @param IActionResult $result Результат исполнения приложения
     */
    public function __construct(IActionResult $result) {
        parent::__construct(self::ERROR_CODE);
        $this->_result = $result;
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        return null;
    }

    /**
     * Возвращает результат исполнения действия
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    public function getResult(): ?IActionResult {
        return $this->_result;
    }

}

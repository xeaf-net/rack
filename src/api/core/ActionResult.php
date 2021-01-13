<?php declare(strict_types = 1);

/**
 * ActionResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Parameters;

/**
 * Реализует базовые методы для классов результатов исполнения действий
 *
 * @property int  $statusCode  Код состояния HTTP
 * @property bool $headersOnly Признак отправки только заголовков
 *
 * @package XEAF\Rack\API\Core
 */
abstract class ActionResult extends DataModel implements IActionResult {

    /**
     * Код состояния HTTP
     * @var int
     */
    protected int $_statusCode = HttpResponse::OK;

    /**
     * Признак отправки только заголовков
     * @var bool
     */
    protected bool $_headersOnly;

    /**
     * Конструктор класса
     *
     * @param int $status Код состояния HTTP
     */
    public function __construct(int $status = HttpResponse::OK) {
        parent::__construct();
        $params             = Parameters::getInstance();
        $this->_statusCode  = $status;
        $this->_headersOnly = $params->getMethodName() == Parameters::HEAD_METHOD_NAME;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int {
        return $this->_statusCode;
    }

    /**
     * @inheritDoc
     */
    public function setStatusCode(int $statusCode): void {
        $this->_statusCode = $statusCode;
    }

}

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
use XEAF\Rack\API\Traits\CommonErrorsTrait;
use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Реализует методы результата возвращающего код статуса
 *
 * @package XEAF\Rack\API\Models\Results
 */
class StatusResult extends ActionResult {

    use CommonErrorsTrait;

    /**
     * @inheritDoc
     */
    public function processResult(): void {
        $headers = HttpResponse::getInstance();
        $code    = $this->getStatusCode();
        $headers->responseCode($code);
        $headers->authenticateBearer($code);
    }
}

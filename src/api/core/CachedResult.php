<?php declare(strict_types = 1);

/**
 * CachedResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Реализует базовые методы кешируемого результата действия
 *
 * @property bool $useCache Признак использования кеша
 *
 * @package XEAF\Rack\API\Core
 */
abstract class CachedResult extends ActionResult {

    /**
     * Идентификатор свойства результата
     */
    public const RESULT_PROPERTY = 'data';

    /**
     * Признак исопльззования кеша
     * @var bool
     */
    protected $_useCache = false;

    /**
     * Конструктор класса
     *
     * @param int  $status   Код статуса HTTP
     * @param bool $useCache Признак использования кеша
     */
    public function __construct(int $status = HttpResponse::OK, bool $useCache = false) {
        parent::__construct($status);
        $this->_useCache = $useCache;
    }

    /**
     * Возвращает признак использования кеша
     *
     * @return bool
     */
    public function getUseCache(): bool {
        return $this->_useCache;
    }

    /**
     * Задает признак использования кеша
     *
     * @param bool $useCache Признак использования кеша
     *
     * @return void
     */
    public function setUseCache(bool $useCache): void {
        $this->_useCache = $useCache;
    }
}

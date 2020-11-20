<?php declare(strict_types = 1);

/**
 * BoolResult.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Core\CachedResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Возвращает логическую константу
 *
 * @package XEAF\Rack\API\Models\Results
 */
class BoolResult extends CachedResult {

    /**
     * Возвращаемое значение
     * @var bool
     */
    protected bool $_value = false;

    /**
     * Конструктор класса
     *
     * @param bool $value    Логическое значение
     * @param bool $useCache Признак использования кеша
     * @param int  $status   Код статуса HTTP
     */
    public function __construct(bool $value, int $status = HttpResponse::OK, bool $useCache = false) {
        parent::__construct($status, $useCache);
        $this->_value = $value;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function processResult(): void {
        $headers    = HttpResponse::getInstance();
        $serializer = Serializer::getInstance();
        $headers->responseCode($this->getStatusCode());
        $headers->contentJSON();
        if ($this->getUseCache()) {
            $headers->fileCacheHeader();
        }
        print $serializer->jsonArrayEncode([
            self::RESULT_PROPERTY => $this->_value
        ]);
    }

    /**
     * Возвращает TRUE
     *
     * @return \XEAF\Rack\API\Models\Results\BoolResult
     */
    public static function ok(): self {
        return new self(true);
    }

    /**
     * Псевдоним для self::ok()
     *
     * @return static
     */
    public static function yes(): self {
        return self::ok();
    }

    /**
     * Возвращает FALSE
     *
     * @return static
     */
    public static function no(): self {
        return new self(false);
    }
}

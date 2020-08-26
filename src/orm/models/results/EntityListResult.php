<?php declare(strict_types = 1);

/**
 * EntityListResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Results;

use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Models\Results\ListResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\ORM\Traits\EntityPrepareTrait;

/**
 * Реализует методы результата возвращающего список объектов сущностей
 *
 * @package XEAF\Rack\ORM\Models\Results
 */
class EntityListResult extends ListResult {

    use EntityPrepareTrait;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection|null $list     Список объектов сущностей
     * @param array                                      $map      Карта возвращаемых свойств
     * @param array                                      $cleanups Идентификаторы упрощаемых свойств связей
     * @param bool                                       $useCache Признак использования кеширования
     * @param int                                        $status   Код состояния HTTP
     */
    public function __construct(?ICollection $list, array $map = [], array $cleanups= [], bool $useCache = false, int $status = HttpResponse::OK) {
        $realList = $this->prepareCollection($list, $map, $cleanups);
        parent::__construct($realList, $useCache, $status);
    }
}

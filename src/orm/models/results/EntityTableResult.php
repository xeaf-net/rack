<?php declare(strict_types = 1);

/**
 * EntityTableResult.php
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
use XEAF\Rack\API\Models\Results\TableResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\ORM\Traits\EntityPrepareTrait;

/**
 * Реализует методы результата возвращающего объектов сущностей для DataTable
 *
 * @package XEAF\Rack\ORM\Models\Results
 */
class EntityTableResult extends TableResult {

    use EntityPrepareTrait;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection|null $list            Список объектов сущностей
     * @param array                                      $map             Карта возвращаемых свойств
     * @param array                                      $cleanups        Идентификаторы упрощаемых посей связей
     * @param int                                        $recordsTotal    Общее количество записей
     * @param int                                        $recordsFiltered Количество отфильтрованных записей
     * @param bool                                       $useCache        Признак использования кеширования
     * @param int                                        $status          Код состояния HTTP
     */
    public function __construct(?ICollection $list, array $map = [], array $cleanups = [], int $recordsTotal = 0, int $recordsFiltered = 0, bool $useCache = false, int $status = HttpResponse::OK) {
        $realList = $this->prepareCollection($list, $map, $cleanups);
        parent::__construct($realList, $recordsTotal, $recordsFiltered, $useCache, $status);
    }
}

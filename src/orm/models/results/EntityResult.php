<?php

/**
 * EntityResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Results;

use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Models\Results\DataResult;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\ORM\Traits\EntityPrepareTrait;

/**
 * Реализует методы результата возвращающего объект сущности
 *
 * @property \XEAF\Rack\API\Core\DataObject $dataObject Объект данных
 *
 * @package XEAF\Rack\ORM\Models\Results
 */
class EntityResult extends DataResult {

    use EntityPrepareTrait;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект сущности
     * @param array                               $map        Карта возвращаемых свойств
     * @param bool                                $useCache   Признак исопльзования кеша
     * @param int                                 $status     Код состояния HTTP
     */
    public function __construct(?DataObject $dataObject, array $map = [], bool $useCache = false, int $status = HttpResponse::OK) {
        $realDataObject = null;
        if ($dataObject != null) {
            $realDataObject = $this->prepareDataObject($dataObject, $map);
        }
        parent::__construct($realDataObject, $useCache, $status);
    }
}

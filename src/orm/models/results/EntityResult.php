<?php declare(strict_types = 1);

/**
 * EntityResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
     * @param array                               $simplify   Идентификаторы упрощаемых посей связей
     * @param bool                                $useCache   Признак исопльзования кеша
     * @param int                                 $status     Код состояния HTTP
     */
    public function __construct(?DataObject $dataObject, array $map = [], array $simplify = [], bool $useCache = false, int $status = HttpResponse::OK) {
        $realDataObject = null;
        if ($dataObject != null) {
            $realDataObject = $this->prepareDataObject($dataObject, $map, $simplify);
        }
        parent::__construct($realDataObject, $useCache, $status);
    }
}

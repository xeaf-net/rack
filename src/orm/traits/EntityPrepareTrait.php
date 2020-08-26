<?php declare(strict_types = 1);

/**
 * EntityPrepareTrait.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Traits;

use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\ORM\Core\Entity;

/**
 * Содержит методы подготовки объектов сущностей
 *
 * @package XEAF\Rack\ORM\Traits
 */
trait EntityPrepareTrait {

    /**
     * Подготоваливает объект данных к отправке
     *
     * @param \XEAF\Rack\API\Core\DataObject $dataObject Объект данных
     * @param array                          $map        Карта возвращаемых свойств
     * @param array                          $cleanups   Массив упрощаемых свойств связей
     *
     * @return \XEAF\Rack\API\Core\DataObject
     */
    public function prepareDataObject(DataObject $dataObject, array $map, array $cleanups): DataObject {
        if ($dataObject instanceof Entity) {
            $result = new DataObject($dataObject->toArray($map, $cleanups));
        } else {
            $properties = [];
            foreach ($dataObject as $name => $entity) {
                assert($entity instanceof Entity);
                $subMap            = $map[$name] ?? [];
                $supClean          = $cleanups[$name] ?? [];
                $properties[$name] = $entity->toArray($subMap, $supClean);
            }
            $result = new DataObject($properties);
        }
        return $result;
    }

    /**
     * Подготавливает коллекцию объектов данных к отправке
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection|null $list     Коллекция объектоа
     * @param array                                      $map      Карта возвращаемых свойств
     * @param array                                      $cleanups Массив упрощаемых свойств связей
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function prepareCollection(?ICollection $list, array $map, array $cleanups): ICollection {
        $result = new Collection();
        if ($list != null) {
            foreach ($list as $dataObject) {
                assert($dataObject instanceof DataObject);
                $result->push($this->prepareDataObject($dataObject, $map, $cleanups));
            }
        }
        return $result;
    }
}

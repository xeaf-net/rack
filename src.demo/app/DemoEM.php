<?php declare(strict_types = 1);

/**
 * DemoEM.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Demo\App;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\Demo\Entities\ProjectEntity;
use XEAF\Rack\Demo\Entities\TaskEntity;
use XEAF\Rack\ORM\Core\EntityManager;

/**
 * Реализует методы менеджера сущностецй приложения
 *
 * @package XEAF\Rack\Demo\App
 */
class DemoEM extends EntityManager implements IFactoryObject {

    /**
     * @inheritDoc
     */
    public function declareEntities(): array {
        return [
            'projects' => ProjectEntity::class,
            'tasks'    => TaskEntity::class
        ];
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\Demo\App\DemoEM
     */
    public static function getInstance(): DemoEM {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof DemoEM);
        return $result;
    }
}

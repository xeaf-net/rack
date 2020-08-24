<?php

/**
 * IResolver.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\;
namespace XEAF\Rack\ORM\Interfaces;

use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\ORM\Core\EntityQuery;
use XEAF\Rack\ORM\Models\Parsers\WithModel;

/**
 * Описывает методы разрешения ссылок в запросах
 *
 * @package XEAF\Rack\ORM\Utils
 */
interface IResolver extends IFactoryObject {

    /**
     * Разрешает ссылку конструкции WITH
     *
     * @param \XEAF\Rack\ORM\Core\EntityQuery         $query     Объект запроса
     * @param \XEAF\Rack\ORM\Models\Parsers\WithModel $withModel Объект модели WITH
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function resolveWith(EntityQuery $query, WithModel $withModel): void;
}

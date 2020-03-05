<?php declare(strict_types = 1);

/**
 * IQueryParser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Interfaces;

use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\ORM\Models\QueryModel;

/**
 * Описывает методы парзера языка запросов XQL
 *
 * @package XEAF\Rack\ORM\Interfaces
 */
interface IQueryParser extends IFactoryObject {

    /**
     * Строит модель запроса по исходному коду XQL
     *
     * @param string $xql Исходный код XQL
     *
     * @return \XEAF\Rack\ORM\Models\QueryModel
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function buildQueryModel(string $xql): QueryModel;
}

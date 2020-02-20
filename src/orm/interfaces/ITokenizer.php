<?php

/**
 * ITokenizer.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Interfaces;

use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Interfaces\IFactoryObject;

/**
 * Описывает методы разбора текста XQL запроса на лексемы
 *
 * @package XEAF\Rack\ORM\Interfaces
 */
interface ITokenizer extends IFactoryObject {

    /**
     * Возвращает коллекцию моделей лексем
     *
     * @param string $xql Исходный текст XQL
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    function tokenize(string $xql): ICollection;
}

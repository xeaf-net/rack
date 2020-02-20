<?php

/**
 * IStorage.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы именованного хранилища Ключ-Значение
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IStorage extends IKeyStorage, INamedObject, IProviderFactory {

}

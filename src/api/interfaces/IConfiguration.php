<?php

/**
 * IConfiguration.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы разбора файла конфигурации
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IConfiguration extends IFactoryObject {

    /**
     * Возвращает объект параметров файла конфигурации
     *
     * @param string $section    Имя секции
     * @param string $subsection Имя подсекции
     * @param bool   $mustExists Признак обязательной секции
     *
     * @return object|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    function getParameters(string $section, string $subsection = '', bool $mustExists = true): ?object;
}

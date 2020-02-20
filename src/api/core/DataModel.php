<?php

/**
 * DataModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

/**
 * Реализует базовые методы моделей данных
 *
 * @package XEAF\Rack\API\Core
 */
abstract class DataModel extends DataObject {

    /**
     * @inheritDoc
     */
    public function __set(string $name, $value): void {
        $this->undefinedSetter($name, $value);
    }
}

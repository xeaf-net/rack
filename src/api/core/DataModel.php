<?php

/**
 * DataModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Utils\Exceptions\CoreException;

/**
 * Реализует базовые методы моделей данных
 *
 * @package XEAF\Rack\API\Core
 */
abstract class DataModel extends DataObject {

    /**
     * @inheritDoc
     */
    public function undefinedSetter(string $name, $value): void {
        throw CoreException::propertyIsNotWritable($this->getClassName(), $name);
    }
}

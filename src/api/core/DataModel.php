<?php declare(strict_types = 1);

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
     *
     * @since 1.0.3
     */
    public function __construct(array $data = []) {
        $values     = $data;
        $properties = $this->getProperties();
        foreach ($values as $name => $value) {
            if (!in_array($name, $properties)) {
                unset($values[$name]);
            }
        }
        parent::__construct($values);
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function undefinedSetter(string $name, $value): void {
        throw CoreException::propertyIsNotWritable($this->getClassName(), $name);
    }
}

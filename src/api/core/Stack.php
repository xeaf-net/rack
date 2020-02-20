<?php

/**
 * Stack.php
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
 * Реализует методы работы со стеком
 *
 * @package XEAF\Rack\API\Core
 */
class Stack extends Collection {

    /**
     * @inheritDoc
     */
    public function push(DataObject $item): void {
        if ($this->_duplicates || !$this->exists($item)) {
            array_unshift($this->_data, $item);
            $this->rewind();
        }
    }

    /**
     * @inheritDoc
     */
    public function unpush(): DataObject {
        return parent::pop();
    }
}

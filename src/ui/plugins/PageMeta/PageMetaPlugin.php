<?php

/**
 * PageMetaPlugin.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Plugins\PageMeta;

use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\UI\Core\Plugin;

/**
 * Контроллер плагина вывода тегов meta
 *
 * @package XEAF\Rack\UI\Plugins\PageMeta
 */
class PageMetaPlugin extends Plugin {

    /**
     * Идентификатор плагина
     */
    public const PLUGIN_NAME = 'tagPageMeta';

    /**
     * @inheritDoc
     */
    public function getDataObject(array $params = []): ?DataObject {
        return DataObject::fromArray([
            'pageMeta' => $this->_template->getPageMeta()
        ]);
    }
}

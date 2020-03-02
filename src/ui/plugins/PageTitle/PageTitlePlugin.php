<?php

/**
 * PageTitlePlugin.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Plugins\PageTitle;

use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\UI\Core\Plugin;

/**
 * Контроллер плагины вывода заголовка страницы
 *
 * @package  XEAF\Rack\UI\Plugins\PageTitle
 */
class PageTitlePlugin extends Plugin {

    /**
     * Идентификатор плагина
     */
    public const PLUGIN_NAME = 'tagPageTitle';

    /**
     * @inheritDoc
     */
    public function getDataObject(array $params = []): ?DataObject {
        return new DataObject([
            'pageTitle' => $this->_template->getPageTitle()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function html(DataObject $dataObject = null): ?string {
        return $dataObject->{'pageTitle'}??'';
    }
}

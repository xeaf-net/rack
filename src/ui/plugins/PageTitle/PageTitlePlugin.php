<?php declare(strict_types = 1);

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
    public const PLUGIN_NAME = 'pageTitle';

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function html(array $params = []): ?string {
        return $this->_template->getPageTitle();
    }
}

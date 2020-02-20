<?php

/**
 * Plugin.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Core;

use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\UI\Models\Results\HtmlResult;

/**
 * Реализует методы контроллеров плагинов
 *
 * @package  XEAF\Rack\UI\Core
 */
abstract class Plugin extends LayoutExtension {

    /**
     * Объект шаблона
     * @var \XEAF\Rack\UI\Core\Template
     */
    protected $_template = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\UI\Models\Results\HtmlResult $actionResult Результат исполнения действия
     * @param \XEAF\Rack\UI\Core\Template|null        $template     Объект шаблона
     */
    public function __construct(HtmlResult $actionResult, Template $template = null) {
        parent::__construct($actionResult);
        $this->_template = $template;
    }

    /**
     * Возвращает объект данных
     *
     * @param array $params Параметры вызова плагина
     *
     * @return \XEAF\Rack\API\Core\DataObject|null
     */
    public function getDataObject(array $params = []): ?DataObject {
        return null;
    }
}

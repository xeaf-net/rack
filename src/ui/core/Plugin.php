<?php declare(strict_types = 1);

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

use XEAF\Rack\UI\Models\Results\HtmlResult;

/**
 * Реализует методы контроллеров плагинов
 *
 * @package  XEAF\Rack\UI\Core
 */
abstract class Plugin extends Layout {

    /**
     * Объект шаблона
     * @var \XEAF\Rack\UI\Core\Template|null
     */
    protected ?Template $_template = null;

    /**
     * Параметры вызова плагина
     * @var array
     */
    protected array $_params = [];

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\UI\Models\Results\HtmlResult $actionResult Результат исполнения действия
     * @param \XEAF\Rack\UI\Core\Template|null        $template     Объект шаблона
     * @param array                                   $params       Параметры вызова плагина
     */
    public function __construct(HtmlResult $actionResult, ?Template $template, array $params) {
        parent::__construct($actionResult);
        $this->_template = $template;
        $this->_params   = $params;
    }

    /**
     * Возвращет HTML код плагина
     *
     * @return string|null
     */
    public function html(): ?string {
        return null; // Использовать .tpl файл
    }
}

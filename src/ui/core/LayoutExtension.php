<?php

/**
 * LayoutExtension.php
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
use XEAF\Rack\API\Core\Extension;
use XEAF\Rack\UI\Models\Results\HtmlResult;
use XEAF\Rack\UI\Utils\TemplateEngine;

/**
 * Реализует методы расширения шаблонизатора
 *
 * @property-read \XEAF\Rack\UI\Models\Results\HtmlResult $actionResult Результат исполнения действия
 * @property-read \XEAF\Rack\API\Core\DataObject|null     $dataObject   Объект данных
 * @property-read string                                  $layoutFile   Файл шаблона построения
 *
 * @package  XEAF\Rack\UI\Core
 */
abstract class LayoutExtension extends Extension {

    /**
     * Результат исполнения действия
     * @var \XEAF\Rack\UI\Models\Results\HtmlResult
     */
    private $_actionResult = null;

    /**
     * Шаблонизатор
     * @var \XEAF\Rack\UI\Interfaces\ITemplateEngine|null
     */
    private $_templateEngine = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\UI\Models\Results\HtmlResult $actionResult Результат исполнения действия
     */
    public function __construct(HtmlResult $actionResult) {
        parent::__construct();
        $this->_actionResult   = $actionResult;
        $this->_templateEngine = TemplateEngine::getInstance();
        $this->registerPlugins();
    }

    /**
     * Регистрирует используемые плагины
     *
     * @return void
     */
    private function registerPlugins(): void {
        $this->_templateEngine->registerPlugins($this->declarePlugins());
    }

    /**
     * Объявляет список используемых плагинов
     *
     * @return array
     */
    protected function declarePlugins(): array {
        return [];
    }

    /**
     * Возвращает результат исполнения действия
     *
     * @return \XEAF\Rack\UI\Models\Results\HtmlResult
     */
    public function getActionResult(): HtmlResult {
        return $this->_actionResult;
    }

    /**
     * Возвращает объект данных
     *
     * @return \XEAF\Rack\API\Core\DataObject|null
     */
    public function getDataObject(): ?DataObject {
        return null;
    }

    /**
     * Возвращает имя файла шаблона построения
     *
     * @return string
     */
    public function getLayoutFile(): string {
        return $this->_templateEngine->defaultLayoutFile($this->getClassName());
    }
}

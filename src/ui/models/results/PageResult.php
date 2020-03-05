<?php declare(strict_types = 1);

/**
 * PageResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Models\Results;

use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\UI\Core\Template;
use XEAF\Rack\UI\Templates\Portal\PortalTemplate;
use XEAF\Rack\UI\Utils\TemplateEngine;

/**
 * Реализует методы результата возвращающего текст страницы HTML
 *
 * @property string $pageTitle    Заголовок страницы
 * @property string $pageMeta     Метаданные страницы
 * @property string $templateName Имя шаблона
 *
 * @package  XEAF\Rack\UI\Models\Results
 */
class PageResult extends HtmlResult {

    /**
     * Заголовок страницы
     * @var string
     */
    protected $_pageTitle = '';

    /**
     * Метаданные страницы
     * @var array
     */
    protected $_pageMeta = [];

    /**
     * Имя шаблона
     * @var string|null
     */
    protected $_templateName = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject   Объект данных
     * @param string                              $pageTitle    Заголовок страницы
     * @param array                               $pageMeta     Метаданные страницы
     * @param string|null                         $templateName Идентификатор шаблона
     * @param string|null                         $layoutFile   Файл шаблона построения
     */
    public function __construct(DataObject $dataObject = null, string $pageTitle = '', array $pageMeta = [], string $templateName = null, string $layoutFile = null) {
        parent::__construct($dataObject, $layoutFile);
        $this->_pageTitle    = $pageTitle;
        $this->_pageMeta     = $pageMeta;
        $this->_templateName = $templateName;
    }

    /**
     * Возвращает заголовок страницы
     *
     * @return string
     */
    public function getPageTitle(): string {
        return $this->_pageTitle;
    }

    /**
     * Задает заголовок страницы
     *
     * @param string $pageTitle Заголовок страницы
     *
     * @return void
     */
    public function setPageTitle(string $pageTitle): void {
        $this->_pageTitle = $pageTitle;
    }

    /**
     * Возвращает метаданные страницы
     *
     * @return array
     */
    public function getPageMeta(): array {
        return $this->_pageMeta;
    }

    /**
     * Добавляет значение к метаданным страницы
     *
     * @param string $name  Имя матаданных
     * @param string $value Значение
     *
     * @return void
     */
    public function addPageMeta(string $name, string $value): void {
        $this->_pageMeta[$name] = $value;
    }

    /**
     * Задает метаданные страницы
     *
     * @param array $pageMeta Массив метаданных
     *
     * @return void
     */
    public function setPageMeta(array $pageMeta): void {
        $this->_pageMeta = $pageMeta;
    }

    /**
     * Возвращает шаблон страницы
     *
     * @return string|null
     */
    public function getTemplateName(): ?string {
        return $this->_templateName;
    }

    /**
     * Задает шаблон страницы
     *
     * @param string|null $templateName
     *
     * @return void
     */
    public function setTemplateName(?string $templateName): void {
        $this->_templateName = $templateName;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function processResult(): void {
        $this->adjustLayoutFile();
        $te          = TemplateEngine::getInstance();
        $template    = $this->createTemplate();
        $pageContent = $te->parseModule($this);
        print $te->parseTemplate($template, $pageContent);
    }

    /**
     * Создает объект шаблона
     *
     * @return \XEAF\Rack\UI\Core\Template
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    protected function createTemplate(): Template {
        $te       = TemplateEngine::getInstance();
        $template = $this->getTemplateName();
        if ($template == null) {
            $template = PortalTemplate::TEMPLATE_NAME;
        }
        $className = $te->getRegisteredTemplate($template);
        return new $className($this);
    }
}

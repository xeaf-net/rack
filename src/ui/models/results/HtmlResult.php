<?php

/**
 * HtmlResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Models\Results;

use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Core\ActionResult;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\UI\Utils\TemplateEngine;

/**
 * Реализует методы результата возвращающего текст фрагмента страницы HTML
 *
 * @property \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
 * @property string                              $layoutFile Имя файла разметки
 *
 * @package  XEAF\Rack\UI\Models\Results
 */
class HtmlResult extends ActionResult {

    /**
     * Объект данных
     * @var \XEAF\Rack\API\Core\DataObject|null
     */
    protected $_dataObject = null;

    /**
     * Имя файла разметки
     * @var string|null
     */
    protected $_layoutFile = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
     * @param string|null                         $layoutFile Имя файла разметки
     */
    public function __construct(DataObject $dataObject = null, string $layoutFile = null) {
        parent::__construct();
        $this->_dataObject = $dataObject;
        $this->_layoutFile = $layoutFile;
    }

    /**
     * Возвращает объект данных
     *
     * @return \XEAF\Rack\API\Core\DataObject|null
     */
    public function getDataObject(): ?DataObject {
        return $this->_dataObject;
    }

    /**
     * Задает объект данных
     *
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
     *
     * @return void
     */
    public function setDataObject(?DataObject $dataObject): void {
        $this->_dataObject = $dataObject;
    }

    /**
     * Возвращает имя файла разметки
     *
     * @return string|null
     */
    public function getLayoutFile(): ?string {
        return $this->_layoutFile;
    }

    /**
     * Задает имя файла разметки
     *
     * @param string|null $layoutFile Имя файла разметки
     *
     * @return void
     */
    public function setLayoutFile(?string $layoutFile): void {
        $this->_layoutFile = $layoutFile;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public function processResult(): void {
        $this->adjustLayoutFile();
        $te = TemplateEngine::getInstance();
        print $te->parseModule($this);
    }

    /**
     * Уточнение имени файла разметки
     *
     * @return void
     */
    protected function adjustLayoutFile(): void {
        if ($this->getLayoutFile() == null) {
            $router     = Router::getInstance();
            $parameters = Parameters::getInstance();
            $tplEngine  = TemplateEngine::getInstance();
            $path       = $parameters->getActionPath();
            $className  = $router->routeClassName($path);
            $this->setLayoutFile($tplEngine->defaultLayoutFile($className));
        }
    }
}

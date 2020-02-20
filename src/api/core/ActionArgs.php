<?php

/**
 * ActionArgs.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Interfaces\IActionArgs;

/**
 * Реализует методы контейнера параметров вызова приложения
 *
 * @property-read string      $methodName Имя метода
 * @property-read string|null $actionNode Имя узла маршрута
 * @property-read string|null $actionPath Путь действия
 * @property-read string|null $actionMode Режим вызова действия
 * @property-read string|null $objectId   Идентификатор объекта действия
 * @property-read string|null $objectPath Путь объекта действия
 * @property-read string|null $currentURL Полный URL вызова действия
 * @property-read string      $locale     Локаль
 *
 * @package XEAF\Rack\API\Core
 */
abstract class ActionArgs extends DataModel implements IActionArgs {

    /**
     * Имя метода
     * @var string
     */
    protected $_methodName = '';

    /**
     * Имя узла действия
     * @var string
     */
    protected $_actionNode = Router::ROOT_NODE;

    /**
     * Путь действия
     * @var string
     */
    protected $_actionPath = null;

    /**
     * Режим вызова действия
     * @var string|null
     */
    protected $_actionMode = null;

    /**
     * Идентификатор объекта действия
     * @var string|null
     */
    protected $_objectId = null;

    /**
     * Путь объекта действия
     * @var string|null
     */
    protected $_objectPath = null;

    /**
     * Полный URL вызова действия
     * @var string|null
     */
    protected $_currentURL = null;

    /**
     * Параметры вызова действия
     * @var array
     */
    protected $_parameters = [];

    /**
     * Параметры заголовков вызова
     * @var array
     */
    protected $_headers = [];

    /**
     * Локаль
     * @var string
     */
    protected $_locale = '';

    /**
     * @inheritDoc
     */
    public function getMethodName(): string {
        return $this->_methodName;
    }

    /**
     * @inheritDoc
     */
    public function getActionNode(): string {
        return $this->_actionNode;
    }

    /**
     * @inheritDoc
     */
    public function getActionPath(): ?string {
        return $this->_actionPath;
    }

    /**
     * @inheritDoc
     */
    public function getActionMode(): ?string {
        return $this->_actionMode;
    }

    /**
     * @inheritDoc
     */
    public function getObjectId(): ?string {
        return $this->_objectId;
    }

    /**
     * @inheritDoc
     */
    public function getObjectPath(): ?string {
        return $this->_objectPath;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentURL(): ?string {
        return $this->_currentURL;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name, $defaultValue = null) {
        return $this->_parameters[$name] ?? $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name, $defaultValue = null) {
        return $this->_headers[$name] ?? $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array {
        return $this->_headers;
    }

    /**
     * Возвращает локаль
     *
     * @return string
     */
    public function getLocale(): string {
        return $this->_locale;
    }
}

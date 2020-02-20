<?php

/**
 * Application.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\App;

use Throwable;
use XEAF\Rack\API\Core\ActionArgs;
use XEAF\Rack\API\Core\Extension;
use XEAF\Rack\API\Interfaces\IActionArgs;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Interfaces\IConfiguration;
use XEAF\Rack\API\Interfaces\IModule;
use XEAF\Rack\API\Interfaces\INodeModule;
use XEAF\Rack\API\Models\Results\StatusResult;
use XEAF\Rack\API\Modules\Home\HomeModule;
use XEAF\Rack\API\Modules\Tools\ResourceModule;
use XEAF\Rack\API\Modules\Tools\SessionModule;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Reflection;
use XEAF\Rack\API\Utils\Session;

/**
 * Реализует методы приложения проекта
 *
 * @package XEAF\Rack\API\App
 */
class Application extends Extension {

    /**
     * Объект методов работы с маршрутами
     * @var \XEAF\Rack\API\Interfaces\IRouter|null
     */
    private $_router = null;

    /**
     * Инициализирует значения свойств объекта класса
     *
     * @param \XEAF\Rack\API\Interfaces\IConfiguration $configuration Параметры конфигурации
     * @param \XEAF\Rack\API\Interfaces\IActionArgs    $actionArgs    Параметры вызова
     */
    public function __construct(IConfiguration $configuration = null, IActionArgs $actionArgs = null) {
        parent::__construct();
        if ($configuration != null) {
            Factory::setFactoryObject(Configuration::class, $configuration);
        }
        if ($actionArgs != null) {
            Factory::setFactoryObject(ActionArgs::class, $actionArgs);
        }
        $this->_router = Router::getInstance();
    }

    /**
     * Объявляет используемые модули
     *
     * @return array
     */
    protected function declareModules(): array {
        return [
            Router::ROOT_NODE             => HomeModule::class,
            SessionModule::MODULE_PATH    => SessionModule::class,
            ResourceModule::PUBLIC_PATH   => ResourceModule::class,
            ResourceModule::VENDOR_PATH   => ResourceModule::class,
            ResourceModule::NODE_MODULES  => ResourceModule::class,
            ResourceModule::RESOURCE_PATH => ResourceModule::class
        ];
    }

    /**
     * Объявляет модули проверки узлов маршрутов
     *
     * @return array
     */
    protected function declareNodeModules(): array {
        return [];
    }

    /**
     * Определяет набор расширений приложения
     *
     * @return void
     */
    protected function defineExtensions(): void {
        $this->_router->registerRoutes($this->declareModules());
        $this->_router->registerRoutesNodes($this->declareNodeModules());
    }

    /**
     * Метод обработки события начала обработки действия
     *
     * @return void
     */
    protected function beforeExecute(): void {
        $this->defineExtensions();
    }

    /**
     * Метод обработки события завершения обработки действия
     *
     * @return void
     */
    protected function afterExecute(): void {
        $session = Session::getInstance();
        $session->saveSessionVars();
    }

    /**
     * Создает объект исполняемого модуля
     *
     * @return \XEAF\Rack\API\Interfaces\IModule|null
     */
    protected function createModule(): ?IModule {
        $className = $this->_router->moduleClassName();
        if ($className) {
            return $this->createModuleObject($className);
        }
        return null;
    }

    /**
     * Создает объект модуля обработки узла
     *
     * @return \XEAF\Rack\API\Interfaces\INodeModule|null
     */
    protected function createNodeModule(): ?INodeModule {
        $result    = null;
        $className = $this->_router->moduleNodeClassName();
        if ($className) {
            $result = $this->createModuleObject($className);
            assert($result instanceof INodeModule);
        }
        return $result;
    }

    /**
     * Создает объект модуля
     *
     * @param string $className Имя класса объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IModule
     */
    protected function createModuleObject(string $className): IModule {
        $result = null;
        if ($className) {
            $reflection = Reflection::getInstance();
            $result     = $reflection->createInjectable($className);
            if ($result != null) {
                assert($result instanceof IModule);
            }
        }
        return $result;
    }

    /**
     * Метод исполнения действия приложения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    protected function execute(): ?IActionResult {
        try {
            $result = null;
            $node   = $this->createNodeModule();
            if ($node != null) {
                $result = $node->checkNode();
            }
            if ($result == null) {
                $module = $this->createModule();
                if ($module) {
                    $result = $module->execute();
                } else {
                    $result = StatusResult::notFound();
                }
            }
        } catch (Throwable $exception) {
            $this->defaultLogger()->exception($exception);
            $result = StatusResult::internalServerError();
        }
        return $result;
    }

    /**
     * Точка входа
     *
     * @return void
     */
    public function run(): void {
        try {
            $this->beforeExecute();
            $result = $this->execute();
            $this->afterExecute();
            if ($result) {
                $result->processResult();
            }
        } catch (Throwable $reason) {
            $errorMsg = HttpResponse::MESSAGES[HttpResponse::FATAL_ERROR];
            $this->defaultLogger()->error($errorMsg, $reason);
            Logger::fatalError($errorMsg, $reason);
        }
    }
}

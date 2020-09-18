<?php declare(strict_types = 1);

/**
 * Application.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Models\Results\StatusResult;
use XEAF\Rack\API\Modules\Home\HomeModule;
use XEAF\Rack\API\Modules\Tools\ResourceModule;
use XEAF\Rack\API\Modules\Tools\SessionModule;
use XEAF\Rack\API\Utils\Assets;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Localization;
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
     * Идентификатор API по умолчанию
     */
    public const DEFAULT_API_ID = 'rackAPI';

    /**
     * Объект методов работы с маршрутами
     * @var \XEAF\Rack\API\Interfaces\IRouter
     */
    private $_router;

    /**
     * Объект методов работы с рефлексией классов
     * @var \XEAF\Rack\API\Interfaces\IReflection
     */
    private $_reflection;

    /**
     * Инициализирует значения свойств объекта класса
     *
     * @param \XEAF\Rack\API\Interfaces\IConfiguration|null $configuration Параметры конфигурации
     * @param \XEAF\Rack\API\Interfaces\IActionArgs|null    $actionArgs    Параметры вызова
     */
    public function __construct(IConfiguration $configuration = null, IActionArgs $actionArgs = null) {
        parent::__construct();
        if ($configuration != null) {
            Factory::setFactoryObject(Configuration::class, $configuration);
        }
        if ($actionArgs != null) {
            Factory::setFactoryObject(ActionArgs::class, $actionArgs);
        }
        $this->_router     = Router::getInstance();
        $this->_reflection = Reflection::getInstance();
        $this->initialization();
    }

    /**
     * Инициализация текущей сесси приложения
     *
     * @return void
     */
    protected function initialization(): void {
        $this->defineExtensions();
        $locale = Session::getInstance()->getLocale();
        if (!$locale) {
            $locale = PortalConfig::getInstance()->getLocale();
        }
        Localization::getInstance()->setDefaultLocale($locale);
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
            ResourceModule::TEMPLATE_PATH => ResourceModule::class
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
     * Регистрирует папку публичных ресурсов
     *
     * @return void
     */
    protected function registerPublicFolder(): void {
        try {
            $fs           = FileSystem::getInstance();
            $classFile    = $this->getClassFileName();
            $publicFolder = $fs->fileDir($classFile) . '/..' . ResourceModule::PUBLIC_PATH;
            if ($fs->folderExists($publicFolder)) {
                Assets::getInstance()->registerPublicFolder($publicFolder);
            }
        } catch (Throwable $exception) {
            $this->defaultLogger()->exception($exception);
        }
    }

    /**
     * Метод обработки события начала обработки действия
     *
     * @return void
     */
    protected function beforeExecute(): void {
        $this->registerPublicFolder();
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
     * Удаляет ранее созданные временные файлы
     *
     * @return void
     */
    protected function clearTempFiles(): void {
        $fileSystem = FileSystem::getInstance();
        $fileSystem->deleteTempFiles();
    }

    /**
     * Создает объект исполняемого модуля
     *
     * @return \XEAF\Rack\API\Interfaces\IModule|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function createModule(): ?IModule {
        $result    = null;
        $className = $this->_router->moduleClassName();
        if ($className) {
            $result = $this->_reflection->createInjectable($className);
            assert($result instanceof IModule);
        }
        return $result;
    }

    /**
     * Создает объект модуля обработки узла
     *
     * @return \XEAF\Rack\API\Interfaces\INodeModule|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function createNodeModule(): ?INodeModule {
        $result    = null;
        $className = $this->_router->moduleNodeClassName();
        if ($className) {
            $result = $this->_reflection->createInjectable($className);
            assert($result instanceof INodeModule);
            $result->registerNodeModules();
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
            $result = $this->processException($exception);
        }
        return $result;
    }

    /**
     * Возвращает результат исполнения действия при ошибке
     *
     * @param \Throwable $exception Объект исключения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    protected function processException(Throwable $exception): IActionResult {
        $this->defaultLogger()->exception($exception);
        return StatusResult::internalServerError();
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
            $this->clearTempFiles();
        } catch (Throwable $reason) {
            $errorMsg = HttpResponse::MESSAGES[HttpResponse::FATAL_ERROR];
            $this->defaultLogger()->error($errorMsg, $reason);
            Logger::fatalError($errorMsg, $reason);
        }
    }
}

<?php

/**
 * ResourceModule.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Modules\Tools;

use Throwable;
use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Core\Module;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Models\Results\DataResult;
use XEAF\Rack\API\Models\Results\FileResult;
use XEAF\Rack\API\Models\Results\StatusResult;
use XEAF\Rack\API\Utils\Assets;
use XEAF\Rack\API\Utils\Exceptions\SerializerException;
use XEAF\Rack\API\Utils\FileMIME;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\Reflection;
use XEAF\Rack\API\Utils\Serializer;
use XEAF\Rack\UI\Utils\TemplateEngine;

/**
 * Обрабатывает запросы к файлам ресурсов
 *
 * @package XEAF\Rack\API\Modules\Tools
 */
class ResourceModule extends Module {

    /**
     * Путь к модулю для обработки ресурсов из папки public
     */
    public const PUBLIC_PATH = '/public';

    /**
     * Путь к модулю для обработки ресурсов из папки node_modules
     */
    public const NODE_MODULES = '/node_modules';

    /**
     * Путь к модулю для обработки ресурсов из папки vendor
     */
    public const VENDOR_PATH = '/vendor';

    /**
     * Путь к модулю для обработки ресурсов исполняемых элементов
     */
    public const RESOURCE_PATH = '/resource';

    /**
     * Тип файла Таблица стилей
     */
    public const CSS_FILE_TYPE = 'css';

    /**
     * Тип файла JavaScript
     */
    public const JS_FILE_TYPE = 'js';

    /**
     * Карта типов ресурсов
     */
    public const RESOURCE_TYPE_MAP = [
        self::CSS_FILE_TYPE => ['scss', 'css', 'min.css'],
        self::JS_FILE_TYPE  => ['ts', 'js', 'js.min']
    ];

    /**
     * Имя модуля домашней страницы
     */
    public const HOME_MODULE_NAME = 'home';

    /**
     * Объект методов доступа к файловой системе
     * @var \XEAF\Rack\API\Interfaces\IFileSystem
     */
    private $_fs = null;

    /**
     * Конструктор класса
     */
    public function __construct() {
        parent::__construct();
        $this->_fs = FileSystem::getInstance();
    }

    /**
     * @inheritDoc
     */
    public function execute(): ?IActionResult {
        $actionName = $this->getActionArgs()->getActionPath();
        try {
            switch ($actionName) {
                case self::PUBLIC_PATH:
                    $result = $this->processGetPublic();
                    break;
                case self::NODE_MODULES:
                    $result = $this->processGetNodeModules();
                    break;
                case self::VENDOR_PATH:
                    $result = $this->processGetVendor();
                    break;
                case self::RESOURCE_PATH:
                    $result = $this->processGetResource();
                    break;
                default:
                    $result = null;
                    break;
            }
        } catch (Throwable $exception) {
            $this->defaultLogger()->exception($exception);
            $result = null;
        }
        if ($result == null) {
            $result = StatusResult::notFound();
        }
        return $result;
    }

    /**
     * Обрабатывает обращение к ресурсам из папки public
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    protected function processGetPublic(): ?IActionResult {
        $result    = null;
        $assets    = Assets::getInstance();
        $fileName  = $this->resourcePath('');
        $fileType  = $this->_fs->fileNameExt($fileName);
        $filePaths = [];

        if ($fileType == self::CSS_FILE_TYPE || $fileType == self::JS_FILE_TYPE) {
            $distDir = $assets->getDistPublicFolder($fileType);
            $filePaths[] = $distDir . $this->_fs->minimizedFilePath($fileName);
            $filePaths[] = $distDir . $fileName;
        }
        foreach ($assets->getPublicFolders() as $publicFolder) {
            $filePaths[] = $publicFolder . '/' . $fileName;
        }

        foreach ($filePaths as $filePath) {
            if ($this->_fs->fileExists($filePath)) {
                $result = $this->sendResourceFile($filePath);
                break;
            }
        }

        return $result;
    }

    /**
     * Обрабатывает обращение к ресурсам из папки node_modules
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    protected function processGetNodeModules(): ?IActionResult {
        $result   = null;
        $rootPath = Assets::getInstance()->getNodeModulesPath();
        $fileName = $this->resourcePath($rootPath);
        if ($this->_fs->fileExists($fileName)) {
            $result = $this->sendResourceFile($fileName);
        }
        return $result;
    }

    /**
     * Обрабатывает обращение к ресурсам из папки vendor
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    protected function processGetVendor(): ?IActionResult {
        $result   = null;
        $fileName = $this->resourcePath(__XEAF_RACK_VENDOR_DIR__);
        if ($this->_fs->fileExists($fileName)) {
            $result = $this->sendResourceFile($fileName);
        }
        return $result;
    }

    /**
     * Обрабатывает обращение к ресурсам исполняемых элементов
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    protected function processGetResource(): ?IActionResult {
        $result     = null;
        $actionMode = $this->getActionArgs()->getActionMode();
        $objectPath = $this->getActionArgs()->getObjectPath();
        if ($actionMode == 'template') {
            $result = $this->processTemplateResource($objectPath);
        } else {
            $objectPath = rtrim("/$actionMode/$objectPath", '/');
            $result     = $this->processModuleResource($objectPath);
        }
        return $result;
    }

    /**
     * Обрабатывает запрос к ресурсам модуля
     *
     * @param string|null $objectPath Путь
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function processModuleResource(?string $objectPath): IActionResult {
        $ref        = Reflection::getInstance();
        $router     = Router::getInstance();
        $fileName   = null;
        $fileType   = $this->_fs->fileNameExt($objectPath);
        $modulePath = $this->extractModulePath($objectPath);
        $moduleMode = '';

        $arr = explode('.', $modulePath);
        if (count($arr) == 1) {
            $moduleMode = '';
        } else if ($fileType == self::CSS_FILE_TYPE || $fileType == self::JS_FILE_TYPE) {
            $modulePath = $arr[0];
            $moduleMode = $arr[1];
        }

        $objectWork  = substr($objectPath, strlen($modulePath));
        $moduleClass = $router->routeClassName($modulePath);

        if ($moduleClass) {
            $moduleFile = $ref->classFileName($moduleClass);
            if ($objectWork == ('.' . self::CSS_FILE_TYPE)) {
                $fileName = $this->_fs->changeFileNameExt($moduleFile, self::CSS_FILE_TYPE);
            } else if ($objectWork == ('.' . self::JS_FILE_TYPE)) {
                $fileName = $this->_fs->changeFileNameExt($moduleFile, self::JS_FILE_TYPE);
            } else if ($moduleMode != '' && $fileType == self::CSS_FILE_TYPE) {
                $fileName = $this->_fs->trimFileNameExt($moduleFile) . '-' . ucfirst($moduleMode) . '.' . self::CSS_FILE_TYPE;
            } else if ($moduleMode != '' && $fileType == self::JS_FILE_TYPE) {
                $fileName = $this->_fs->trimFileNameExt($moduleFile) . '-' . ucfirst($moduleMode) . '.' . self::JS_FILE_TYPE;
            } else if ($modulePath == Router::ROOT_NODE && $objectWork == self::HOME_MODULE_NAME . '.' . self::CSS_FILE_TYPE) {
                $fileName = $this->_fs->changeFileNameExt($moduleFile, self::CSS_FILE_TYPE);
            } else if ($modulePath == Router::ROOT_NODE && $objectWork == self::HOME_MODULE_NAME . '.' . self::JS_FILE_TYPE) {
                $fileName = $this->_fs->changeFileNameExt($moduleFile, self::JS_FILE_TYPE);
            } else {
                $moduleDir = $this->_fs->fileDir($moduleFile);
                $fileName  = $moduleDir . $objectWork;
            }
        }

        if ($fileName) {
            $fileName = $this->checkMinimizedFile($fileName);
            return $this->sendResourceFile($fileName);
        }
        return null;
    }

    /**
     * Обрабатывает запрос к ресурсам шаблона
     *
     * @param string|null $objectPath Путь
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    protected function processTemplateResource(?string $objectPath): IActionResult {
        $te  = TemplateEngine::getInstance();
        $ref = Reflection::getInstance();

        $arr          = explode('/', $objectPath);
        $template     = $this->_fs->fileName($arr[0]);
        $className    = $te->getRegisteredTemplate($template);
        $templateFile = $ref->classFileName($className);
        $fileType     = $this->_fs->fileNameExt($objectPath);

        if (count($arr) == 1 && ($fileType == self::CSS_FILE_TYPE || $fileType == self::JS_FILE_TYPE)) {
            $fileName = $this->_fs->changeFileNameExt($templateFile, $fileType);
        } else {
            unset($arr[0]);
            $fileDir  = $this->_fs->fileDir($templateFile);
            $fileName = $fileDir . '/' . implode('/', $arr);
        }
        if ($fileType == self::CSS_FILE_TYPE || $fileType == self::JS_FILE_TYPE) {
            $fileName = $this->checkMinimizedFile($fileName);
        }

        return $this->sendResourceFile($fileName);
    }

    /**
     * Возвращает путь к файлу ресурса
     *
     * @param string $rootDir Имя корневой папки
     *
     * @return string|null
     */
    protected function resourcePath(string $rootDir): ?string {
        $result     = null;
        $actionMode = $this->getActionArgs()->getActionMode();
        $objectPath = $this->getActionArgs()->getObjectPath();
        if ($actionMode) {
            $result = $rootDir . '/' . $actionMode;
            if ($objectPath) {
                $result .= '/' . $objectPath;
            }
        }
        return $result;
    }

    /**
     * Извлекает из полного пути путь модуля
     *
     * @param string $objectPath Полный путь
     *
     * @return string
     */
    protected function extractModulePath(string $objectPath): string {
        $trimPath = ltrim($objectPath, '/');
        if ($trimPath == self::HOME_MODULE_NAME . '.' . self::CSS_FILE_TYPE) {
            $result = Router::ROOT_NODE;
        } else if ($trimPath == self::HOME_MODULE_NAME . '.' . self::JS_FILE_TYPE) {
            $result = Router::ROOT_NODE;
        } else {
            $objectName = '/' . ltrim($this->_fs->trimFileNameExt($trimPath), './');
            $moduleNode = Router::getInstance()->extractPathNode($objectName);
            $modulePath = substr($objectName, strlen($moduleNode));
            $moduleName = explode('/', ltrim($modulePath, '/'))[0];
            $result     = rtrim($moduleNode, '/') . '/' . $moduleName;
        }
        return $result;
    }

    /**
     * Возвращает имя минимизированной версии файла, если она существует
     *
     * @param string $filePath Путь к файлу
     *
     * @return string
     */
    protected function checkMinimizedFile(string $filePath): string {
        $minFileName = $this->_fs->minimizedFilePath($filePath);
        if ($this->_fs->fileExists($minFileName)) {
            return $minFileName;
        }
        return $filePath;
    }

    /**
     * Возвращает результат отправки файла ресурса
     *
     * @param string $fileName Имя файла ресурса
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    protected function sendResourceFile(string $fileName): IActionResult {
        $result   = null;
        $fileType = $this->_fs->fileNameExt($fileName);
        $mimeType = FileMIME::getInsance()->getMIME($fileType);
        if ($mimeType != FileMIME::DEFAULT_MIME_TYPE) {
            if ($fileType != 'lang') {
                if ($this->_fs->fileExists($fileName)) {
                    $result = new FileResult($fileName, false, true);
                }
            } else {
                try {
                    $data   = Serializer::getInstance()->jsonDecodeFileArray($fileName, true);
                    $result = DataResult::dataArray($data);
                } catch (SerializerException $exception) {
                    $this->defaultLogger()->exception($exception);
                }
            }
        }
        if (!$result) {
            $result = StatusResult::notFound();
        }
        return $result;
    }
}

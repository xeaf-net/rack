<?php declare(strict_types = 1);

/**
 * ResourceModule.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Modules\Tools;

use Throwable;
use XEAF\Rack\API\Core\Module;
use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Interfaces\IFileSystem;
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
     * Путь к модулю для обработки компилированных ресурсов
     */
    public const DIST_PATH = '/dist';

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
     * Путь к модулю для обработки ресурсов шаблонов
     */
    public const TEMPLATE_PATH = '/template';

    /**
     * Тип файла Таблица стилей
     */
    public const CSS_FILE_TYPE = 'css';

    /**
     * Тип файла JavaScript
     */
    public const JS_FILE_TYPE = 'js';

    /**
     * Тип файла Внешний языковой ресурс
     */
    public const EXTERNAL_LANG_TYPE = 'lang';

    /**
     * Объект методов доступа к файловой системе
     * @var \XEAF\Rack\API\Interfaces\IFileSystem
     */
    private IFileSystem $_fs;

    /**
     * Конструктор класса
     */
    public function __construct() {
        parent::__construct();
        $this->_fs = FileSystem::getInstance();
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function execute(): ?IActionResult {
        $actionName = $this->args()->getActionPath();
        try {
            switch ($actionName) {
                case self::DIST_PATH:
                    $result = $this->processGetDist();
                    break;
                case self::PUBLIC_PATH:
                    $result = $this->processGetPublic();
                    break;
                case self::NODE_MODULES:
                    $result = $this->processGetNodeModules();
                    break;
                case self::VENDOR_PATH:
                    $result = $this->processGetVendor();
                    break;
                case self::TEMPLATE_PATH:
                    $result = $this->processGetTemplate();
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
     * Обрабатывает обращение к ресурсам из папки dist
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    protected function processGetDist(): ?IActionResult {
        $result   = null;
        $rootPath = Assets::getInstance()->getDistRootFolder();
        $fileName = $this->resourcePath($rootPath);
        if ($this->_fs->fileExists($fileName)) {
            $result = $this->sendResourceFile($fileName);
        }
        return $result;
    }

    /**
     * Обрабатывает обращение к ресурсам из папки public
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     */
    protected function processGetPublic(): ?IActionResult {
        $result   = null;
        $assets   = Assets::getInstance();
        $fileName = $this->resourcePath('');
        foreach ($assets->getPublicFolders() as $publicFolder) {
            $checked = $this->checkMinimizedFile($publicFolder . '/' . $fileName);
            if ($this->_fs->fileExists($checked)) {
                $result = $this->sendResourceFile($checked);
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
        $fileName = $this->resourcePath(__RACK_VENDOR_DIR__);
        if ($this->_fs->fileExists($fileName)) {
            $result = $this->sendResourceFile($fileName);
        }
        return $result;
    }

    /**
     * Обрабатывает запрос к ресурсам шаблона
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    protected function processGetTemplate(): IActionResult {
        $te  = TemplateEngine::getInstance();
        $ref = Reflection::getInstance();

        $path          = $this->resourcePath('');
        $parts         = explode('/', ltrim($this->resourcePath(''), '/'));
        $fileName      = null;
        $templateName  = $this->_fs->fileName($parts[0]);
        $templateClass = $te->getRegisteredTemplate($templateName);
        $templateFile  = $ref->classFileName($templateClass);

        switch ($path) {
            case '/' . $templateName . '.' . self::CSS_FILE_TYPE:
                $fileName = $this->_fs->changeFileNameExt($templateFile, self::CSS_FILE_TYPE);
                break;
            case '/' . $templateName . '.' . self::JS_FILE_TYPE:
                $fileName = $this->_fs->changeFileNameExt($templateFile, self::JS_FILE_TYPE);
                break;
            default:
                unset($parts[0]);
                $templateDir = $this->_fs->fileDir($templateFile);
                $fileName    = $templateDir . '/' . implode('/', $parts);
                break;
        }

        $fileName = $this->checkMinimizedFile($fileName);
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
        $actionMode = $this->args()->getActionMode();
        $objectPath = $this->args()->getObjectPath();
        if ($actionMode) {
            $result = $rootDir . '/' . $actionMode;
            if ($objectPath) {
                $result .= '/' . $objectPath;
            }
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
        $fileMIME = FileMIME::getInsance();
        $fileType = $this->_fs->fileNameExt($fileName);
        if ($fileMIME->isExtensionResource($fileType)) {
            if ($fileType != self::EXTERNAL_LANG_TYPE) {
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

<?php

/**
 * Module.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Interfaces\IModule;
use XEAF\Rack\API\Models\Results\DataResult;
use XEAF\Rack\API\Models\Results\FileResult;
use XEAF\Rack\API\Models\Results\StatusResult;
use XEAF\Rack\API\Modules\Tools\ResourceModule;
use XEAF\Rack\API\Utils\Assets;
use XEAF\Rack\API\Utils\FileMIME;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\API\Utils\Reflection;

/**
 * Реализует базовые методы модуля проекта
 *
 * @package XEAF\Rack\API\Core
 */
class Module extends Extension implements IModule {

    /**
     * Префикс метода исполнения действия модуля
     */
    private const ACTION_METHOD_PREFIX = 'process';

    /**
     * Префикс метода исполнения действия модуля в режима API
     */
    private const ACTION_METHOD_API_SUFFIX = 'API';

    /**
     * @inheritDoc
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function execute(): ?IActionResult {
        $result     = null;
        $methodName = $this->getActionArgs()->getMethodName();
        $actionMode = $this->getActionArgs()->getActionMode();
        if ($methodName == Parameters::GET_METHOD_NAME && $actionMode == Assets::MODULE_L10N) {
            $result = $this->sendLocaleData();
        } else if ($methodName == Parameters::GET_METHOD_NAME && $actionMode == Assets::MODULE_CSS) {
            $result = $this->sendModuleResource(ResourceModule::CSS_FILE_TYPE);
        } else if ($methodName == Parameters::GET_METHOD_NAME && $actionMode == Assets::MODULE_JS) {
            $result = $this->sendModuleResource(ResourceModule::JS_FILE_TYPE);
        } else {
            $method = $this->actionModeMethod($actionMode, $methodName);
            if ($method) {
                $this->beforeExecute();
                $reflection = Reflection::getInstance();
                $result     = $reflection->returnInjectable($this, $method);
                if ($result != null) {
                    assert($result instanceof IActionResult);
                }
                $this->afterExecute();
            } else if ($methodName == Parameters::GET_METHOD_NAME) {
                $result = $this->sendModuleResource($actionMode);
            }
        }
        if ($result == null) {
            $result = StatusResult::notFound();
        }
        return $result;
    }

    /**
     * Выполняется перед исполнением метода обработки действия
     *
     * @return void
     */
    protected function beforeExecute(): void {
    }

    /**
     * Выполняется перед исполнением метода обработки действия
     *
     * @return void
     */
    protected function afterExecute(): void {
    }

    /**
     * Возвращает идентификатор метода для исполнения режима действия
     *
     * @param string|null $actionMode Идентификатор режима действия
     * @param string      $methodName Идентификатор метода
     *
     * @return string|null
     */
    protected function actionModeMethod(?string $actionMode, string $methodName = Parameters::GET_METHOD_NAME): ?string {
        $mode   = ($actionMode) ? $actionMode : '';
        $result = self::ACTION_METHOD_PREFIX . ucfirst(strtolower($methodName)) . ucfirst($mode);
        if ($this->isApiMode()) {
            $apiMethod = $result . self::ACTION_METHOD_API_SUFFIX;
            if (method_exists($this, $apiMethod)) {
                return $apiMethod;
            }
        }
        return method_exists($this, $result) ? $result : null;
    }

    /**
     * Возвращает данные локализации
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    protected function sendLocaleData(): IActionResult {
        $l10n   = Localization::getInstance();
        $locale = $this->getActionArgs()->get('locale');
        if (!$locale) {
            $locale = $l10n->getDefaultLocale()->getName();
        }
        $l10nData = $l10n->getLocale($locale)->toArray();
        $landVars = $l10n->getLanguageVars($locale)->toArray();
        return DataResult::dataArray([
            Localization::L10N => $l10nData,
            Localization::LANG => $landVars
        ]);
    }

    /**
     * Отправляет файл ресурса
     *
     * @param string|null $type Тип ресурса
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function sendModuleResource(?string $type): ?IActionResult {
        $result = null;
        if ($type != null) {
            $fileName   = null;
            $fs         = FileSystem::getInstance();
            $moduleFile = $this->getClassFileName();
            switch ($type) {
                case ResourceModule::CSS_FILE_TYPE:
                case ResourceModule::JS_FILE_TYPE:
                    $fileName = $fs->changeFileNameExt($moduleFile, $type);
                    break;
                default:
                    $mime     = FileMIME::getInsance();
                    $path     = $this->getActionArgs()->getObjectPath();
                    $dir      = $fs->fileDir($moduleFile);
                    $fileName = $dir . '/' . $type;
                    if ($path) {
                        $fileName .= '/' . $path;
                    }
                    $fileType = $fs->fileNameExt($fileName);
                    if (!$mime->isExtensionResource($fileType)) {
                        $fileName = null;
                    }
                    break;
            }
            if ($fileName != null) {
                $minFileName = $fs->minimizedFilePath($fileName);
                if ($fs->fileExists($minFileName)) {
                    $fileName = $minFileName;
                }
            }
            if ($fs->fileExists($fileName)) {
                $result = new FileResult($fileName, false, true);
            }
        }
        return $result;
    }
}

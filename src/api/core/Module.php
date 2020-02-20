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
use XEAF\Rack\API\Models\Results\StatusResult;
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
     */
    public function execute(): ?IActionResult {
        $actionMode = $this->getActionArgs()->getActionMode();
        if (!$actionMode && $this->getActionArgs()->get(Localization::L10N, false)) {
            $result = $this->sendLocaleData();
        } else {
            $methodName = $this->getActionArgs()->getMethodName();
            $method     = $this->actionModeMethod($actionMode, $methodName);
            if ($method) {
                $this->beforeExecute();
                $reflection = Reflection::getInstance();
                $result     = $reflection->returnInjectable($this, $method);
                if ($result != null) {
                    assert($result instanceof IActionResult);
                }
                $this->afterExecute();
            } else {
                $result = StatusResult::notFound();
            }
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
        $locale   = $this->getActionArgs()->getLocale();
        $l10n     = Localization::getInstance();
        $l10nData = $l10n->getLocale($locale)->toArray();
        $landVars = $l10n->getLanguageVars($locale)->toArray();
        return DataResult::dataArray([
                Localization::L10N => $l10nData,
                Localization::LANG => $landVars
            ]);
    }
}

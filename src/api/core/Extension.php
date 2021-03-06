<?php declare(strict_types = 1);

/**
 * Extension.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\IActionArgs;
use XEAF\Rack\API\Interfaces\ILogger;
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Parameters;

/**
 * Реализует базовые методы для всех расширений проекта
 *
 * @property-read \XEAF\Rack\API\Interfaces\IActionArgs $actionArgs   Параметры вызова приложения
 *
 * @package XEAF\Rack\API\Core
 */
class Extension extends StdObject {

    /**
     * Объект методов доступа к параметрам
     * @var \XEAF\Rack\API\Interfaces\IActionArgs|null
     */
    private ?IActionArgs $_args = null;

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->loadLanguageFiles();
    }

    /**
     * Загружает файлы языковых переменных
     *
     * @return void
     */
    protected function loadLanguageFiles(): void {
        $l10n      = Localization::getInstance();
        $className = $this->getClassName();
        while ($className != __CLASS__) {
            $l10n->registerLanguageClass($className);
            $className = get_parent_class($className);
        }
    }

    /**
     * Возвращает объект параметров вызова приложения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionArgs
     */
    protected function args(): IActionArgs {
        if (!$this->_args) {
            $this->_args = Parameters::getInstance();
        }
        return $this->_args;
    }

    /**
     * Возвращает значение языковой переменной
     *
     * @param string      $name   Имя переменной
     * @param string|null $locale Локаль
     *
     * @return string
     */
    protected function lang(string $name, string $locale = null): string {
        $l10n = Localization::getInstance();
        return $l10n->getLanguageVar($name, $locale);
    }

    /**
     * Форматирует значения с использованием языковой переменной
     *
     * @param string      $name   Имя переменной
     * @param array       $args   Аргументы
     * @param string|null $locale Локаль
     *
     * @return string
     */
    protected function langFmt(string $name, array $args = [], string $locale = null): string {
        $l10n = Localization::getInstance();
        return $l10n->fmtLanguageVar($name, $args, $locale);
    }

    /**
     * Возращащает объект методов журанлирования по умолчанию
     *
     * @return \XEAF\Rack\API\Interfaces\ILogger
     */
    protected function defaultLogger(): ILogger {
        return Logger::getInstance();
    }
}

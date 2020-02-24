<?php

/**
 * SmartyTemplateEngine.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Utils\Engines;

use Smarty;
use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\Formatter;
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\API\Utils\Reflection;
use XEAF\Rack\API\Utils\Strings;
use XEAF\Rack\UI\Core\Plugin;
use XEAF\Rack\UI\Core\Template;
use XEAF\Rack\UI\Interfaces\ITemplateEngineProvider;
use XEAF\Rack\UI\Models\Config\TemplatesConfig;
use XEAF\Rack\UI\Models\Results\HtmlResult;
use XEAF\Rack\UI\Utils\Exceptions\TemplateException;
use XEAF\Rack\UI\Utils\TemplateEngine;

/**
 * Реализует методы действий провайдера шаблонизатора
 *
 * @package  XEAF\Rack\UI\Utils\Engines
 */
class SmartyTemplateEngine implements ITemplateEngineProvider {

    use NamedObjectTrait;

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'smarty';

    /**
     * Расширение имени файла шаблона
     */
    protected const FILE_NAME_EXT = 'tpl';

    /**
     * Имя переменной URL портала
     */
    protected const VAR_PORTAL_URL = 'portalURL';

    /**
     * Имя переменной идентификатора действия
     */
    protected const VAR_ACTION_NAME = 'actionName';

    /**
     * Имя переменной идентификатора режима вызва действия
     */
    protected const VAR_ACTION_MODE = 'actionMode';

    /**
     * Имя переменной признака режима отладки
     */
    protected const VAR_DEBUG_MODE = 'debugMode';

    /**
     * Имя переменной модели данных действия
     */
    protected const VAR_ACTION_MODEL = 'actionModel';

    /**
     * Имя переменной модули данные плагина
     */
    protected const VAR_PLUGIN_MODEL = 'pluginModel';

    /**
     * Имя переменной модели данных шаблона
     */
    protected const VAR_TEMPLATE_MODEL = 'templateModel';

    /**
     * Имя переменной заголовка страницы
     */
    protected const VAR_PAGE_TITLE = 'pageTitle';

    /**
     * Имя переменной метаданных
     */
    protected const VAR_PAGE_META = 'pageMeta';

    /**
     * Имя переменной локали
     */
    protected const VAR_LOCALE = 'pageLocale';

    /**
     * Объект шаблонизатора Smarty
     * @var \Smarty
     */
    private $_smarty = null;

    /**
     * Набор зарегистрированных плагинов
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_plugins = null;

    /**
     * Набор зарегистрированных шаблонов
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_templates = null;

    /**
     * Результат исполнения текущего действия
     * @var \XEAF\Rack\UI\Models\Results\HtmlResult|null
     */
    private static $_currentActionResult = null;

    /**
     * Текущимй исполняемый объект шаблона
     * @var \XEAF\Rack\UI\Core\Template|null
     */
    private static $_currentTemplate = null;

    /**
     * Текйщий выводимый контент страницы
     * @var string|null
     */
    private static $_currentPageContent = null;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name      = $name;
        $this->_smarty    = self::createSmarty();
        $this->_plugins   = new KeyValue();
        $this->_templates = new KeyValue();
    }

    /**
     * Создает и инициализирует объект Smarty
     *
     * @return \Smarty
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    protected static function createSmarty(): Smarty {
        $result = new Smarty();
        try {
            $config = TemplatesConfig::getInstance();
            $result->setCacheDir($config->getCacheDir());
            $result->setCompileDir($config->getCompileDir());
            $result->caching       = $config->getEnableCaching();
            $result->force_compile = $config->getForceCompile();
            self::initSmartyPlugins($result);
            self::initSmartyModifiers($result);
            self::initSmartyVariables($result);
        } catch (Throwable $exception) {
            throw TemplateException::internalEngineError(self::PROVIDER_NAME, $exception);
        }
        return $result;
    }

    /**
     * Инициализирует плагины Smarty
     *
     * @param \Smarty $smarty Объект шаблонизатора Smarty
     *
     * @return void
     * @throws \SmartyException
     */
    protected static function initSmartyPlugins(Smarty $smarty): void {
        $smarty->registerPlugin("function", "content", self::class . "::printPageContent");
        $smarty->registerPlugin("function", "plugin", self::class . "::printPluginContent");
    }

    /**
     * Инициализирует модификаторы Smarty
     *
     * @param \Smarty $smarty Объект шаблонизатора Smarty
     *
     * @return void
     * @throws \SmartyException
     */
    protected static function initSmartyModifiers(Smarty $smarty): void {
        $smarty->registerPlugin("modifier", "lang", self::class . "::printLangModifier");
        $smarty->registerPlugin("modifier", "int", self::class . "::printIntModifier");
        $smarty->registerPlugin("modifier", "number", self::class . "::printNumberModifier");
        $smarty->registerPlugin("modifier", "date", self::class . "::printDateModifier");
        $smarty->registerPlugin("modifier", "time", self::class . "::printTimeModifier");
        $smarty->registerPlugin("modifier", "dt", self::class . "::printDateTimeModifier");
    }

    /**
     * Инициализирует переменные Smarty
     *
     * @param \Smarty $smarty Объект шаблонизатора Smarty
     *
     * @return void
     */
    protected static function initSmartyVariables(Smarty $smarty): void {
        $params = Parameters::getInstance();
        $config = PortalConfig::getInstance();
        $l10n   = Localization::getInstance();
        $smarty->assign(self::VAR_PORTAL_URL, $config->getUrl());
        $smarty->assign(self::VAR_ACTION_NAME, $params->getActionPath());
        $smarty->assign(self::VAR_ACTION_MODE, $params->getActionMode());
        $smarty->assign(self::VAR_LOCALE, $l10n->getLocale($params->getLocale()));
        $smarty->assign(self::VAR_DEBUG_MODE, __XEAF_RACK_DEBUG_MODE__);
    }

    /**
     * Обрабатывает вызов модификатора языковой переменной
     *
     * @param mixed|null $name   Идентификатор переменной
     * @param mixed|null $locale Имя локали
     *
     * @return string
     */
    public static function printLangModifier($name = null, $locale = null) {
        $l10n = Localization::getInstance();
        return $l10n->getLanguageVar($name, $locale);
    }

    /**
     * Обрабатывает вызов модификатора форматирования целых чисел
     *
     * @param mixed|null $text   Форматируемый текст
     * @param mixed|null $locale Имя локали
     *
     * @return string
     */
    public static function printIntModifier($text = null, $locale = null) {
        $str = Strings::getInstance();
        $fmt = Formatter::getInstance();
        if ($str->isInteger($text)) {
            return $fmt->formatInteger($text, $locale);
        }
        return $text;
    }

    /**
     * Обрабатывает вызов модификатора форматирования чисел
     *
     * @param mixed|null $text   Форматируемый текст
     * @param int        $dec    Количество десятичных цифр
     * @param mixed|null $locale Имя локали
     *
     * @return string
     */
    public static function printNumberModifier($text = null, $dec = 0, $locale = null) {
        $fmt = Formatter::getInstance();
        if (is_numeric($text) && is_numeric($dec)) {
            return $fmt->formatNumeric($text, $dec, $locale);
        }
        return $text;
    }

    /**
     * Обрабатывает вызов модификатора форматирования даты
     *
     * @param mixed|null $text   Форматируемый текст
     * @param mixed|null $locale Имя локали
     *
     * @return string
     */
    public static function printDateModifier($text = null, $locale = null) {
        $str = Strings::getInstance();
        $fmt = Formatter::getInstance();
        if ($str->isInteger($text)) {
            return $fmt->formatDate($text, $locale);
        }
        return $text;
    }

    /**
     * Обрабатывает вызов модификатора форматирования времени
     *
     * @param mixed|null $text   Форматируемый текст
     * @param mixed|null $locale Имя локали
     *
     * @return string
     */
    public static function printTimeModifier($text = null, $locale = null) {
        $str = Strings::getInstance();
        $fmt = Formatter::getInstance();
        if ($str->isInteger($text)) {
            return $fmt->formatTime($text, $locale);
        }
        return $text;
    }

    /**
     * Обрабатывает вызов модификатора форматирования времени
     *
     * @param mixed|null $text   Форматируемый текст
     * @param mixed|null $locale Имя локали
     *
     * @return string
     */
    public static function printDateTimeModifier($text = null, $locale = null) {
        $str = Strings::getInstance();
        $fmt = Formatter::getInstance();
        if ($str->isInteger($text)) {
            return $fmt->formatDateTime($text, $locale);
        }
        return $text;
    }

    /**
     * Возвращает содержимое текущей страницы
     *
     * @param mixed|null $params Параметры вызова плагина
     * @param mixed|null $smarty Объект шаблонизатора Smarty
     *
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public static function printPageContent($params, $smarty) {
        return self::$_currentPageContent;
    }

    /**
     * Обрабатыввает вызов плагина
     *
     * @param mixed|null $params Параметры вызова плагина
     * @param mixed|null $smarty Объект шаблонизатора Smarty
     *
     * @return string
     * @throws \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     * @noinspection PhpUnusedParameterInspection
     */
    public static function printPluginContent($params, $smarty) {
        $result     = '';
        $pluginName = $params['name'] ?? null;
        if ($pluginName) {
            $te        = TemplateEngine::getInstance();
            $className = $te->getRegisteredPlugin($pluginName);
            $plugin    = new $className(self::$_currentActionResult, self::$_currentTemplate);
            assert($plugin instanceof Plugin);
            $newSmarty  = self::createSmarty();
            $layoutFile = $plugin->getLayoutFile();
            try {
                $pluginData = $plugin->getDataObject($params);
                $result     = $plugin->generateHTML($pluginData);
                if (!$result) {
                    $newSmarty->assign(self::VAR_PLUGIN_MODEL, $pluginData);
                    $newSmarty->assign(self::VAR_ACTION_MODEL, self::$_currentActionResult->getDataObject());
                    if (self::$_currentTemplate) {
                        $newSmarty->assign(self::VAR_TEMPLATE_MODEL, self::$_currentTemplate->getDataObject());
                    }
                    $result = $newSmarty->fetch($plugin->getLayoutFile());
                }
            } catch (Throwable $exception) {
                throw TemplateException::templateProcessingError($layoutFile, $exception);
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function defaultLayoutFile(string $className): string {
        $result     = null;
        $strings    = Strings::getInstance();
        $fileSystem = FileSystem::getInstance();
        $reflection = Reflection::getInstance();
        $parameters = Parameters::getInstance();
        $actionMode = $parameters->getActionMode();
        $baseName   = $reflection->classFileName($className);
        if ($actionMode) {
            $prefix   = $fileSystem->trimFileNameExt($baseName);
            $fileName = $prefix . '-' . $strings->kebabToCamel($actionMode) . '.' . self::FILE_NAME_EXT;
            if ($fileSystem->fileExists($fileName)) {
                $result = $fileName;
            }
        }
        if (!$result) {
            $result = $fileSystem->changeFileNameExt($baseName, self::FILE_NAME_EXT);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getRegisteredPlugin(string $name): string {
        $result = $this->_plugins->get($name);
        if ($result == null) {
            throw TemplateException::unregisteredPlugin($name);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function registerPlugin(string $name, string $className): void {
        $this->_plugins->put($name, $className);
    }

    /**
     * @inheritDoc
     */
    public function registerPlugins(array $plugins): void {
        foreach ($plugins as $name => $className) {
            $this->registerPlugin($name, $className);
        }
    }

    /**
     * @inheritDoc
     */
    public function unregisterPlugin(string $name): void {
        $this->_plugins->delete($name);
    }

    /**
     * @inheritDoc
     */
    public function getRegisteredTemplate(string $name): string {
        $result = $this->_templates->get($name);
        if ($result == null) {
            throw TemplateException::unregisteredTemplate($name);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function registerTemplate(string $name, string $className): void {
        $this->_templates->put($name, $className);
    }

    /**
     * @inheritDoc
     */
    public function registerTemplates(array $templates): void {
        foreach ($templates as $name => $className) {
            $this->registerTemplate($name, $className);
        }
    }

    /**
     * @inheritDoc
     */
    public function unregisterTemplate(string $name): void {
        $this->_templates->delete($name);
    }

    /**
     * @inheritDoc
     */
    public function parseModule(HtmlResult $actionResult): string {
        $layoutFile = $actionResult->getLayoutFile();
        try {
            self::$_currentActionResult = $actionResult;
            $this->_smarty->assign(self::VAR_ACTION_MODEL, self::$_currentActionResult->getDataObject());
            return $this->_smarty->fetch($layoutFile);
        } catch (Throwable $exception) {
            throw TemplateException::templateProcessingError($layoutFile, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function parseTemplate(Template $template, string &$pageContent): string {
        $layoutFile = $template->getLayoutFile();
        try {
            self::$_currentTemplate    = $template;
            self::$_currentPageContent = $pageContent;
            $this->_smarty->assign(self::VAR_ACTION_MODEL, self::$_currentActionResult->getDataObject());
            $this->_smarty->assign(self::VAR_TEMPLATE_MODEL, self::$_currentTemplate->getDataObject());
            $this->_smarty->assign(self::VAR_PAGE_TITLE, self::$_currentTemplate->getPageTitle());
            $this->_smarty->assign(self::VAR_PAGE_META, self::$_currentTemplate->getPageMeta());
            return $this->_smarty->fetch($layoutFile);
        } catch (Throwable $exception) {
            throw TemplateException::templateProcessingError($layoutFile, $exception);
        }
    }
}

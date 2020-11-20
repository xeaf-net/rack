<?php declare(strict_types = 1);

/**
 * TemplateEngine.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Traits\ProviderFactoryTrait;
use XEAF\Rack\UI\Core\Template;
use XEAF\Rack\UI\Interfaces\ITemplateEngine;
use XEAF\Rack\UI\Interfaces\ITemplateEngineProvider;
use XEAF\Rack\UI\Models\Config\TemplatesConfig;
use XEAF\Rack\UI\Models\Results\HtmlResult;
use XEAF\Rack\UI\Plugins\FavIcon\FavIconPlugin;
use XEAF\Rack\UI\Plugins\PageMeta\PageMetaPlugin;
use XEAF\Rack\UI\Plugins\PageTitle\PageTitlePlugin;
use XEAF\Rack\UI\Plugins\ResourceLink\ResourceLinkPlugin;
use XEAF\Rack\UI\Templates\Portal\PortalTemplate;

/**
 * Реализует методы шаблонизатора
 *
 * @package  XEAF\Rack\UI\Utils
 */
class TemplateEngine implements ITemplateEngine {

    use ProviderFactoryTrait;

    /**
     * Объект провайдера шаблонизатора
     * @var \XEAF\Rack\UI\Interfaces\ITemplateEngineProvider
     */
    private $_engine;

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public function __construct() {
        $this->_engine = $this->createProvider();
        $this->registerDefaultPlugins();
        $this->registerDefaultTemplate();
    }

    /**
     * Регистрирует плагины по умолчанию
     *
     * @return void
     */
    protected function registerDefaultPlugins(): void {
        $this->registerPlugins([
            FavIconPlugin::PLUGIN_NAME      => FavIconPlugin::class,
            PageMetaPlugin::PLUGIN_NAME     => PageMetaPlugin::class,
            PageTitlePlugin::PLUGIN_NAME    => PageTitlePlugin::class,
            ResourceLinkPlugin::PLUGIN_NAME => ResourceLinkPlugin::class
        ]);
    }

    /**
     * Регистрирует шаблон по умолчанию
     *
     * @return void
     */
    protected function registerDefaultTemplate(): void {
        $this->registerTemplate(PortalTemplate::TEMPLATE_NAME, PortalTemplate::class);
    }

    /**
     * @inheritDoc
     */
    public function defaultLayoutFile(string $className): string {
        return $this->_engine->defaultLayoutFile($className);
    }

    /**
     * @inheritDoc
     */
    public function getRegisteredPlugin(string $name): string {
        return $this->_engine->getRegisteredPlugin($name);
    }

    /**
     * @inheritDoc
     */
    public function registerPlugin(string $name, string $className): void {
        $this->_engine->registerPlugin($name, $className);
    }

    /**
     * @inheritDoc
     */
    public function registerPlugins(array $plugins): void {
        $this->_engine->registerPlugins($plugins);
    }

    /**
     * @inheritDoc
     */
    public function unregisterPlugin(string $name): void {
        $this->_engine->unregisterPlugin($name);
    }

    /**
     * @inheritDoc
     */
    public function getRegisteredTemplate(string $name): string {
        return $this->_engine->getRegisteredTemplate($name);
    }

    /**
     * @inheritDoc
     */
    public function registerTemplate(string $name, string $className): void {
        $this->_engine->registerTemplate($name, $className);
    }

    /**
     * @inheritDoc
     */
    public function registerTemplates(array $templates): void {
        $this->_engine->registerTemplates($templates);
    }

    /**
     * @inheritDoc
     */
    public function unregisterTemplate(string $name): void {
        $this->_engine->unregisterPlugin($name);
    }

    /**
     * @inheritDoc
     */
    public function parse(string $layoutFile, DataObject $dataObject = null, string $locale = null): string {
        return $this->_engine->parse($layoutFile, $dataObject, $locale);
    }

    /**
     * @inheritDoc
     */
    public function parseModule(HtmlResult $actionResult, string $locale = null): string {
        return $this->_engine->parseModule($actionResult, $locale);
    }

    /**
     * @inheritDoc
     */
    public function parseTemplate(Template $template, string $pageContent, string $locale = null): string {
        return $this->_engine->parseTemplate($template, $pageContent, $locale);
    }

    /**
     * Создает объект провайдера шаблонизатора
     *
     * @return \XEAF\Rack\UI\Interfaces\ITemplateEngineProvider
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    protected function createProvider(): ITemplateEngineProvider {
        $config    = TemplatesConfig::getInstance();
        $className = self::getProviderClass($config->getEngine());
        return new $className();
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\UI\Interfaces\ITemplateEngine
     */
    public static function getInstance(): ITemplateEngine {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ITemplateEngine);
        return $result;
    }
}

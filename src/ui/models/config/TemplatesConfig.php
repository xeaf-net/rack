<?php declare(strict_types = 1);

/**
 * TemplatesConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Models\Config;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\ConfigModel;
use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\UI\Utils\Engines\SmartyTemplateEngine;

/**
 * Содержит параметры конфигурации шаблонизатора
 *
 * @property-read string $engine        Идентификатор парзера шаблонов
 * @property-read bool   $forceCompile  Признак принудительной компиляции
 * @property-read bool   $enableCaching Признак использования кеширования
 * @property-read string $cacheDir      Директория файлов кеша
 * @property-read string $compileDir    Директория файлов откомпилированных шаблонов
 *
 * @package  XEAF\Rack\UI\Models\Config
 */
class TemplatesConfig extends ConfigModel implements IFactoryObject {

    /**
     * Имя секции в файле конфигурации
     */
    public const SECTION_NAME = 'templates';

    /**
     * Идентификатор парзера шаблонов по умолчанию
     */
    protected const DEFAULT_ENGINE = SmartyTemplateEngine::PROVIDER_NAME;

    /**
     * Признак принудительной компиляции по умолчанию
     */
    protected const DEFAULT_FORCE_COMPILE = false;

    /**
     * Признак использования кеширования по умолчанию
     */
    protected const DEFAULT_ENABLE_CACHING = true;

    /**
     * Имя парзера шаблонов
     * @var string
     */
    private $_engine = self::DEFAULT_ENGINE;

    /**
     * Признак принудительной компиляции
     * @var bool
     */
    private $_forceCompile = self::DEFAULT_FORCE_COMPILE;

    /**
     * Признак использования кеширования
     * @var bool
     */
    private $_enableCaching = self::DEFAULT_ENABLE_CACHING;

    /**
     * Директория файлов кеша
     * @var string
     */
    private $_cacheDir = '';

    /**
     * Директория файлов компиляции
     * @var string
     */
    private $_compileDir = '';

    /**
     * Конструктор класса
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct() {
        parent::__construct(self::SECTION_NAME);
    }

    /**
     * Возвращает имя парзера шаблонов
     *
     * @return string
     */
    public function getEngine(): string {
        return $this->_engine;
    }

    /**
     * Возвращает признак принудительной компиляции
     *
     * @return bool
     */
    public function getForceCompile(): bool {
        if (__RACK_DEBUG_MODE__){
            return true;
        }
        return $this->_forceCompile;
    }

    /**
     * Возвращает признак использования кеширования
     *
     * @return bool
     */
    public function getEnableCaching(): bool {
        return $this->_enableCaching;
    }

    /**
     * Возвращает директорию файлов кеша
     *
     * @return string
     */
    public function getCacheDir(): string {
        return $this->_cacheDir;
    }

    /**
     * Возвращает директорию файлов откомпилированных шаблонов
     *
     * @return string
     */
    public function getCompileDir(): string {
        return $this->_compileDir;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        $portalConfig         = PortalConfig::getInstance();
        $this->_engine        = $data->{'engine'} ?? self::DEFAULT_ENGINE;
        $this->_forceCompile  = $data->{'forceCompile'} ?? self::DEFAULT_FORCE_COMPILE;
        $this->_enableCaching = $data->{'enableCaching'} ?? self::DEFAULT_ENABLE_CACHING;
        $this->_cacheDir      = $data->{'cacheDir'} ?? null;
        $this->_compileDir    = $data->{'compileDir'} ?? null;
        if (!$this->_cacheDir) {
            $this->_cacheDir = $portalConfig->getTempPath();
        }
        if (!$this->_compileDir) {
            $this->_compileDir = $portalConfig->getTempPath();
        }
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\UI\Models\Config\TemplatesConfig
     */
    public static function getInstance(): TemplatesConfig {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof TemplatesConfig);
        return $result;
    }
}

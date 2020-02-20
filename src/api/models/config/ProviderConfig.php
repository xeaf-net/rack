<?php

/**
 * ProviderConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Config;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\ConfigModel;
use XEAF\Rack\API\Interfaces\INamedObject;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Exceptions\ConfigurationException;
use XEAF\Rack\API\Utils\Strings;

/**
 * Содежит базовые методы обработки параметров конфигурации провайдеров
 *
 * @property-read string $provider Имя провайдера
 *
 * @package XEAF\Rack\API\Models\Config
 */
class ProviderConfig extends ConfigModel implements INamedObject {

    use NamedObjectTrait;

    /**
     * Идентификатор провайдера
     * @var string|null
     */
    private $_provider = null;

    /**
     * Конструктор класса
     *
     * @param string $section Имя секции
     * @param string $name    Имя объекта
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct(string $section = Factory::DEFAULT_NAME, string $name = Factory::DEFAULT_NAME) {
        parent::__construct($section, $name, true);
    }

    /**
     * Возвращает имя провайдера
     *
     * @return string
     */
    public function getProvider(): string {
        return $this->_provider;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function parseConfigurationSection(object $data): void {
        $this->_provider = $data->{Strings::DSN_PREFIX} ?? null;
        if (!$this->_provider) {
            throw ConfigurationException::parameterNotFound($this->_section, Strings::DSN_PREFIX, $this->_subsection);
        }
    }

    /**
     * Возвращает единичный экземпляр класса
     *
     * @param string $section Имя секции
     * @param string $name    Имя объекта
     *
     * @return \XEAF\Rack\API\Models\Config\ProviderConfig
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public static function getInstance(string $section, string $name): self {
        $factoryId = $section . ':' . self::class;
        if (!Factory::factoryNamedObjectExists($factoryId, $name)) {
            $config = new ProviderConfig($section, $name);
            Factory::setFactoryNamedObject($factoryId, $name, $config);
        }
        $result = Factory::getFactoryNamedObject($factoryId, $name);
        assert($result instanceof ProviderConfig);
        return $result;
    }
}

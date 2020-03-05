<?php declare(strict_types = 1);

/**
 * ConfigModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\App\Configuration;

/**
 * Реализует базовые методы контейнера параметров конфигурации
 *
 * @property-read string $section    Имя секции
 * @property-read string $subsection Имя подсекции
 *
 * @package XEAF\Rack\API\Core
 */
abstract class ConfigModel extends DataModel {

    /**
     * Имя секции
     * @var string
     */
    protected $_section = '';

    /**
     * Имя подсекциии
     * @var string
     */
    protected $_subsection = '';

    /**
     * Конструктор класса
     *
     * @param string $section    Имя секции файла конфигурации
     * @param string $subsection Имя подсекции
     * @param bool   $mustExists Признак обязательного существования секции
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct(string $section, string $subsection = '', bool $mustExists = false) {
        parent::__construct();
        $this->_section    = $section;
        $this->_subsection = $subsection;

        $config = Configuration::getInstance();
        $data   = $config->getParameters($section, $subsection, $mustExists);
        if ($data != null) {
            $this->parseConfigurationSection($data);
        }
    }

    /**
     * Возвращает имя секции
     *
     * @return string
     */
    public function getSection(): string {
        return $this->_section;
    }

    /**
     * Возвращает имя подсекции
     *
     * @return string
     */
    public function getSubsection(): string {
        return $this->_subsection;
    }

    /**
     * Разбирает данные секции файла конфигурации
     *
     * @param object $data Данные секции файла конфигурации
     *
     * @return void
     */
    abstract public function parseConfigurationSection(object $data): void;
}

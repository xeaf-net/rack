<?php declare(strict_types = 1);

/**
 * LoggerConfig.php
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
use XEAF\Rack\API\Utils\Logger;

/**
 * Содержит общие параметры конфигурации для журналов операций
 *
 * @property-read int $level Уровень записей журнала
 *
 * @package XEAF\Rack\API\Models\Config
 */
abstract class LoggerConfig extends ConfigModel implements INamedObject {

    use NamedObjectTrait;

    /**
     * Идентификатор секции
     */
    public const SECTION_NAME = 'logger';

    /**
     * Уровень записей журнала
     * @var int
     */
    private $_level = Logger::ERROR;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        parent::__construct(self::SECTION_NAME, $name);
    }

    /**
     * Возвращает уровень записей журнала
     *
     * @return int
     */
    public function getLevel(): int {
        return $this->_level;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        $level        = $data->{'level'} ?? null;
        $this->_level = Logger::LEVEL_NAMES[$level] ?? Logger::ERROR;
    }
}

<?php declare(strict_types = 1);

/**
 * StorageConfig.php
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
use XEAF\Rack\API\Utils\Calendar;
use XEAF\Rack\API\Utils\Strings;

/**
 * Содержит параметры конфигурации хранилища Ключ-Значение
 *
 * @property-read int $ttl Максимальное время жизни в секундах
 *
 * @package XEAF\Rack\API\Models\Config
 */
abstract class StorageConfig extends ConfigModel implements INamedObject {

    use NamedObjectTrait;

    /**
     * Идентификатор секции
     */
    public const SECTION_NAME = 'storage';

    /**
     * Максимальное время жизни в секундах по умолчанию
     */
    private const DEFAULT_TTL = Calendar::SECONDS_PER_DAY;

    /**
     * Максимальное время жизни в секундах
     * @var int
     */
    private $_ttl = self::DEFAULT_TTL;

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
     * Возвращает максимальное время жизни в секундах
     * @return int
     */
    public function getTtl(): int {
        return $this->_ttl;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        $strings    = Strings::getInstance();
        $this->_ttl = $strings->stringToInteger($data->{'ttl'} ?? null, self::DEFAULT_TTL);
    }
}

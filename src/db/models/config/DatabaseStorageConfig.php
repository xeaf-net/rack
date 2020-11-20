<?php declare(strict_types = 1);

/**
 * DatabaseStorageConfig.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Models\Config;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Models\Config\StorageConfig;

/**
 * Содержит парамеры конфигурации провайдера хранилища в базе данных
 *
 * @property-read string $connection Имя подключения к базе данных
 * @property-read bool   $load       Признак загрузки всех значений
 *
 * @package XEAF\Rack\Db\Models\Config
 */
class DatabaseStorageConfig extends StorageConfig {

    /**
     * Имя подключения к базе данных по умолчанию
     */
    protected const DEFAULT_CONNECTION = Factory::DEFAULT_NAME;

    /**
     * Имя подключения к базе данных
     * @var string
     */
    private string $_connection = self::DEFAULT_CONNECTION;

    /**
     * Возвращает имя подкючения к базе данных
     *
     * @return string
     */
    public function getConnection(): string {
        return $this->_connection;
    }

    /**
     * @inheritDoc
     */
    public function parseConfigurationSection(object $data): void {
        parent::parseConfigurationSection($data);
        $this->_connection = $data->{'connection'} ?? self::DEFAULT_CONNECTION;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\Db\Models\Config\DatabaseStorageConfig
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): DatabaseStorageConfig {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof DatabaseStorageConfig);
        return $result;
    }
}

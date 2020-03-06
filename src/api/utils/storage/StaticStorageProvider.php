<?php declare(strict_types = 1);

/**
 * StaticStorageProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Storage;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyStorage;
use XEAF\Rack\API\Interfaces\IStorageProvider;
use XEAF\Rack\API\Traits\NamedObjectTrait;

/**
 * Провайдер статического хранилища Ключ-Значение
 *
 * @package XEAF\Rack\API\Utils\Storage
 */
class StaticStorageProvider extends KeyStorage implements IStorageProvider {

    use NamedObjectTrait;

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'static';

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        parent::__construct($name);
    }
}

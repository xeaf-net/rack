<?php declare(strict_types = 1);

/**
 * Assets.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IAssets;

/**
 * Реализует методы регистратора ресурсов
 *
 * @package  XEAF\Rack\API\Utils
 */
class Assets implements IAssets {

    /**
     * Ссылка на режим отправки информации о локализации
     */
    public const MODULE_L10N = 'l10n';

    /**
     * Ссылка на режим отправки CSS модуля
     */
    public const MODULE_CSS = 'module.css';

    /**
     * Ссылка на режим отправки JS модуля
     */
    public const MODULE_JS = 'module.js';

    /**
     * Список папок публичных ресурсов
     * @var array
     */
    private $_publicFolders = [];

    /**
     * @inheritDoc
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function getPublicFolders(): array {
        return $this->_publicFolders;
    }

    /**
     * @inheritDoc
     */
    public function registerPublicFolder(string $folderPath): void {
        if (!in_array($folderPath, $this->_publicFolders)) {
            array_unshift($this->_publicFolders, $folderPath);
        }
    }

    /**
     * @inheritDoc
     */
    public function getDistRootFolder(): string {
        return __RACK_VENDOR_DIR__ . '/../dist';
    }

    /**
     * @inheritDoc
     */
    public function getDistPublicFolder(string $fileType): string {
        return $this->getDistRootFolder() . '/public';
    }

    /**
     * @inheritDoc
     */
    public function getNodeModulesPath(): string {
        return __RACK_VENDOR_DIR__ . '/../node_modules';
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IAssets
     */
    public static function getInstance(): IAssets {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IAssets);
        return $result;
    }
}

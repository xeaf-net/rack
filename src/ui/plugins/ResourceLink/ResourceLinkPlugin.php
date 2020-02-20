<?php

/**
 * ResourceLinkPlugin.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Plugins\ResourceLink;

use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Modules\Tools\ResourceModule;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\Reflection;
use XEAF\Rack\UI\Core\Plugin;

/**
 * Контроллер плагины вывода ссылок на ресурсы модуля
 *
 * @package  XEAF\Rack\UI\Plugins\ResourceLink
 */
class ResourceLinkPlugin extends Plugin {

    /**
     * Идентификатор плагина
     */
    public const PLUGIN_NAME = 'tagResourceLink';

    /**
     * Список загружаемых ссылок
     * @var array
     */
    protected $_links = [];

    /**
     * Возвращает объект данных плагина
     *
     * @param array $params Параметры вызова плагина
     *
     * @return \XEAF\Rack\API\Core\DataObject|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function getDataObject(array $params = []): ?DataObject {
        $type = $params['type'] ?? null;
        if ($type == 'css' || $type == 'js') {
            $this->checkActionLink($type);
        }
        return DataObject::fromArray([
            'type'  => $type,
            'links' => $this->_links
        ]);
    }

    /**
     * Добавляет ссылку на ресурс модуля
     *
     * @param string $type Тип ссылки
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function checkActionLink(string $type) {
        $reflection    = Reflection::getInstance();
        $actionPath    = $this->getActionArgs()->getActionPath();
        $actionMode    = $this->getActionArgs()->getActionMode();
        $classFileName = $reflection->moduleClassFileName();
        if (!$actionPath) {
            $actionPath = Router::ROOT_NODE;
        }
        $this->checkActionFileLink($classFileName, $actionPath, '', $type);
        if ($actionMode) {
            $layoutFileName = $this->getActionResult()->getLayoutFile();
            $this->checkActionFileLink($layoutFileName, $actionPath, $actionMode, $type);
        }
    }

    /**
     * Добавляет ссылку на ресурс режима исполнения действия модуля
     *
     * @param string $fileName   Базовое имя проверяемого файла
     * @param string $actionPath Путь к модулю
     * @param string $actionMode Режим исполнения действия
     * @param string $type       Тип ссылки
     *
     * @return void
     */
    protected function checkActionFileLink(string $fileName, string $actionPath, string $actionMode, string $type) {
        $extMap     = ResourceModule::RESOURCE_TYPE_MAP[$type];
        $fileSystem = FileSystem::getInstance();
        foreach ($extMap as $ext) {
            $checkedName = $fileSystem->changeFileNameExt($fileName, $ext);
            if ($fileSystem->fileExists($checkedName)) {
                $config = PortalConfig::getInstance();
                $link   = $prefix = $config->getUrl() . '/resource' . $actionPath;
                if ($actionPath == Router::ROOT_NODE) {
                    $link .= ResourceModule::HOME_MODULE_NAME;
                }
                if ($actionMode) {
                    $link .= '.' . $actionMode;
                }
                $link .= '.' . $type;
                // $link = rtrim($prefix . $actionMode, '/') . '.' . $type;
                if (!in_array($link, $this->_links)) {
                    $this->_links[] = $link;
                }
            }
        }
    }

}

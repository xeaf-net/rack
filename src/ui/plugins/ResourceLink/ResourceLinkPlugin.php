<?php declare(strict_types = 1);

/**
 * ResourceLinkPlugin.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Plugins\ResourceLink;

use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Modules\Tools\ResourceModule;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\API\Utils\Reflection;
use XEAF\Rack\UI\Core\Plugin;
use XEAF\Rack\UI\Core\Template;
use XEAF\Rack\UI\Models\Results\HtmlResult;

/**
 * Контроллер плагины вывода ссылок на ресурсы модуля
 *
 * @package  XEAF\Rack\UI\Plugins\ResourceLink
 */
class ResourceLinkPlugin extends Plugin {

    /**
     * Идентификатор плагина
     */
    public const PLUGIN_NAME = 'resourceLink';

    /**
     * Объект модуле работы с файловой системой
     * @var \XEAF\Rack\API\Utils\FileSystem
     */
    private $_fs;

    /**
     * Объект методов работы с рефлексией
     * @var \XEAF\Rack\API\Utils\Reflection
     */
    private $_ref;

    /**
     * Параметры вызова приложения
     * @var \XEAF\Rack\API\Utils\Parameters
     */
    private $_args;

    /**
     * @inheritDoc
     */
    public function __construct(HtmlResult $actionResult, Template $template, array $params) {
        parent::__construct($actionResult, $template, $params);
        $this->_fs   = FileSystem::getInstance();
        $this->_ref  = Reflection::getInstance();
        $this->_args = Parameters::getInstance();
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    public function getDataObject(): ?DataObject {
        $link = '';
        $type = $this->_params['type'] ?? null;
        switch ($type) {
            case ResourceModule::CSS_FILE_TYPE:
            case ResourceModule::JS_FILE_TYPE:
                $link = $this->resourceLink((string)$type);
                break;
            default:
                break;
        }
        return DataObject::fromArray([
            'type' => $type,
            'link' => $link
        ]);
    }

    /**
     * Возвращает ссылку на ресурс
     *
     * @param string $type Тип ресурса
     *
     * @return string|null
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function resourceLink(string $type): ?string {
        $result   = null;
        $fileName = $this->resourceFileName($type);
        if ($this->_fs->fileExists($fileName)) {
            $path = $this->_args->getActionPath();
            if (!$path) {
                $path = Router::HOME_NODE;
            }
            $result = "$path/module.$type";
        }
        return $result;
    }

    /**
     * Возвращает имя файла предполагаемого ресурса
     *
     * @param string $type Тип ресурса
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\CoreException
     */
    protected function resourceFileName(string $type): string {
        $fileName = $this->_ref->moduleClassFileName();
        return $this->_fs->changeFileNameExt($fileName, $type);
    }
}

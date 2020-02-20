<?php

/**
 * Template.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Core;

use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Utils\Parameters;
use XEAF\Rack\UI\Models\Results\PageResult;

/**
 * Реализует методы контроллеров шаблонов
 *
 * @property-read \XEAF\Rack\UI\Models\Results\PageResult $actionResult Результат исполнения действия
 * @property-read string                                  $pageTitle    Заголовок страницы
 * @property-read array                                   $pageMeta     Метаданные страницы
 *
 * @package  XEAF\Rack\UI\Core
 */
abstract class Template extends LayoutExtension {

    /**
     * Имя переменной определяющей BASE HREF
     */
    protected const PORTAL_META_URL = 'x-portal-url';

    /**
     * Имя переменной определяющей локаль
     */
    protected const PORTAL_META_LOCALE = 'x-portal-locale';

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\UI\Models\Results\PageResult $actionResult
     */
    public function __construct(PageResult $actionResult) {
        parent::__construct($actionResult);
    }

    /**
     * Возвращает заголовок страницы
     *
     * @return string
     */
    public function getPageTitle(): string {
        $actionResult = $this->getActionResult();
        assert($actionResult instanceof PageResult);
        $result = $actionResult->getPageTitle();
        return !$result ? $this->getDefaultPageTitle() : $this->getDefaultPageTitle() . ' | ' . $result;
    }

    /**
     * Возвращает заголовок страницы по умолчанию
     *
     * @return string
     */
    public function getDefaultPageTitle(): string {
        $config = PortalConfig::getInstance();
        return $config->getUrl();
    }

    /**
     * Возвращает метаданные страницы
     *
     * @return array
     */
    public function getPageMeta(): array {
        $result       = $this->getDefaultPageMeta();
        $actionResult = $this->getActionResult();
        assert($actionResult instanceof PageResult);
        $actionMeta = $actionResult->getPageMeta();
        foreach ($actionMeta as $name => $value) {
            $result[$name] = $value;
        }
        return $result;
    }

    /**
     * Возвращает метаданные страницы по умолчанию
     *
     * @return array
     */
    public function getDefaultPageMeta(): array {
        $config = PortalConfig::getInstance();
        $args   = Parameters::getInstance();
        return [
            self::PORTAL_META_URL    => $config->getUrl(),
            self::PORTAL_META_LOCALE => $args->getLocale()
        ];
    }

}

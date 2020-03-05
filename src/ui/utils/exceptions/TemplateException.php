<?php declare(strict_types = 1);

/**
 * TemplateException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе с шаблонизаторами
 *
 * @package  XEAF\Rack\UI\Utils\Exceptions
 */
class TemplateException extends Exception {

    /**
     * Внутренняя ошибка шаблонизатора
     */
    public const INTERNAL_ENGINE_ERROR = 'TPL001';

    /**
     * Незарегисторованный плагин
     */
    public const UNREGISTERED_PLUGIN = 'TPL002';

    /**
     * Незарегистрированный шаблон
     */
    public const UNREGISTERED_TEMPLATE = 'TPL003';

    /**
     * Ошибка обработки плагина
     */
    public const PLUGIN_PROCESSING_ERROR = 'TPL004';

    /**
     * Ошибка обработки шаблона
     */
    public const TEMPLATE_PROCESSING_ERROR = 'TPL005';

    /**
     * Внутренняя ошибка шаблонизатора
     *
     * @param string     $engineName Имя провайдера шаблонизатора
     * @param \Throwable $reason     Причина возникновения ошибки
     *
     * @return \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public static function internalEngineError(string $engineName, Throwable $reason): self {
        return new self(self::INTERNAL_ENGINE_ERROR, [$engineName], $reason);
    }

    /**
     * Незарегистрированный плагин
     *
     * @param string $pluginName Имя плагина
     *
     * @return static
     */
    public static function unregisteredPlugin(string $pluginName): self {
        return new self(self::UNREGISTERED_PLUGIN, [$pluginName]);
    }

    /**
     * Незарегистрированный шаблон
     *
     * @param string $templateName Имя шаблона
     *
     * @return static
     */
    public static function unregisteredTemplate(string $templateName): self {
        return new self(self::UNREGISTERED_TEMPLATE, [$templateName]);
    }

    /**
     * Ошибка обработки плагина
     *
     * @param string     $pluginName Имя плагина
     * @param \Throwable $reason     Причина возникновения ошибки
     *
     * @return \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public static function pluginProcessingError(string $pluginName, Throwable $reason): self {
        return new self(self::PLUGIN_PROCESSING_ERROR, [$pluginName], $reason);
    }

    /**
     * Ошибка обработки шаблона
     *
     * @param string     $fileName Имя файла шаблона
     * @param \Throwable $reason   Причина возникновения ошибки
     *
     * @return \XEAF\Rack\UI\Utils\Exceptions\TemplateException
     */
    public static function templateProcessingError(string $fileName, Throwable $reason): self {
        return new self(self::TEMPLATE_PROCESSING_ERROR, [$fileName], $reason);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = '';
        switch ($code) {
            case self::INTERNAL_ENGINE_ERROR:
                $result = 'Internal error in template engine [%s].';
                break;
            case self::PLUGIN_PROCESSING_ERROR:
                $result = 'Error while processing plugin [%s].';
                break;
            case self::TEMPLATE_PROCESSING_ERROR:
                $result = 'Error while processing template file [%s].';
                break;
            case self::UNREGISTERED_PLUGIN:
                $result = 'Unregistered plugin [%s].';
                break;
            case self::UNREGISTERED_TEMPLATE:
                $result = 'Unregistered template [%s].';
                break;
        }
        return $result;
    }
}

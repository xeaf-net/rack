<?php

/**
 * ITemplateEngine.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\UI\Interfaces;

use XEAF\Rack\API\Interfaces\IProviderFactory;

/**
 * Описывает методы шаблонизатора
 *
 * @package XEAF\Rack\UI\Interfaces
 */
interface ITemplateEngine extends ITemplateEngineActions, IProviderFactory {

}

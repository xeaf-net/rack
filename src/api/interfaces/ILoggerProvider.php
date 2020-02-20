<?php

/**
 * ILoggerProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы провайдера журнала операций
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ILoggerProvider extends INamedObject, IProvider {

    /**
     * Возвращает уровень записей из файла конфигурации
     *
     * @return int
     */
    function getConfigLevel(): int;

    /**
     * Создает запись в журнале операций
     *
     * @param int        $level   Уровень записи
     * @param string     $message Текст сообщения
     * @param mixed|null $data    Дополнительные данные
     *
     * @return void
     */
    function writeLog(int $level, string $message, $data = null): void;
}

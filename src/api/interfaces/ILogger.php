<?php declare(strict_types = 1);

/**
 * ILogger.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use Throwable;

/**
 * Описывает методы журналирования
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ILogger extends INamedObject, IProviderFactory {

    /**
     * Возвращает уровень записей журнала
     *
     * @return int
     */
    public function getLevel(): int;

    /**
     * Задает уровень записей хурнала
     *
     * @param int $level Уровень записей
     *
     * @return void
     */
    public function setLevel(int $level): void;

    /**
     * Записывает в журанал отладочное сообщение
     *
     * @param string     $message Текст сообщения
     * @param mixed|null $data    Дополнительная информация
     *
     * @return void
     */
    public function debug(string $message, $data = null): void;

    /**
     * Записывает в журанал информационное сообщение
     *
     * @param string     $message Текст сообщения
     * @param mixed|null $data    Дополнительная информация
     *
     * @return void
     */
    public function info(string $message, $data = null): void;

    /**
     * Записывает в журанал сообщение о предупреждении
     *
     * @param string     $message Текст сообщения
     * @param mixed|null $data    Дополнительная информация
     *
     * @return void
     */
    public function warning(string $message, $data): void;

    /**
     * Записывает в журанал сообщение об ошибке
     *
     * @param string     $message Текст сообщения
     * @param mixed|null $data    Дополнительная информация
     *
     * @return void
     */
    public function error(string $message, $data = null): void;

    /**
     * Записывает в журнал сообщение об исключении
     *
     * @param \Throwable $exception Объект исключения
     *
     * @return void
     */
    public function exception(Throwable $exception): void;

    /**
     * Выводит отладочную информацию об исключении
     *
     * @param \Throwable $exception Объект исключения
     *
     * @return void
     */
    public function debugException(Throwable $exception): void;
}

<?php declare(strict_types = 1);

/**
 * IActionResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает базовые методы для классов результатов исполнения действий
 *
 * @property int $statusCode Код состояния HTTP
 *
 * @package XEAF\Rack\API\Core
 */
interface IActionResult {

    /**
     * Возвращает код состояния HTTP
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Задает код состояния HTTP
     *
     * @param int $statusCode Код состояния HTTP
     *
     * @return void
     */
    public function setStatusCode(int $statusCode): void;

    /**
     * Обрабатывает результат исполнения действия
     *
     * @return void
     */
    public function processResult(): void;
}

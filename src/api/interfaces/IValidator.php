<?php declare(strict_types = 1);

/**
 * IValidator.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Описывает методы проверки параметров
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface IValidator extends IFactoryObject {

    /**
     * Проверяет наличие значения
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkNotEmpty($data, string $tag = null): void;

    /**
     * Проверяет длину строки
     *
     * @param mixed       $data      Данные для проврки
     * @param int         $minLength Минимальная длина
     * @param int         $maxLength Максмиальная длина
     * @param string|null $tag       Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkLength($data, int $minLength = 0, int $maxLength = 0, string $tag = null): void;

    /**
     * Проверяет уникальный идентификатор
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkUUID($data, string $tag = null): void;

    /**
     * Проверяет адрес электронной почты
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkEmail($data, string $tag = null): void;

}

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
     * Проверка соответствия формату целого числа
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkIsInteger($data, string $tag = null): void;

    /**
     * Проверка соответствия формату числового значения
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkIsNumber($data, string $tag = null): void;

    /**
     * Проверка корректности формата значения
     *
     * @param mixed       $data    Данные для проверки
     * @param string      $pattern Регулярное выражение
     * @param string|null $tag     Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkFormat($data, string $pattern, string $tag = null): void;

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
     * Проверяет наличие доступного значения
     *
     * @param mixed       $data   Данные для проверки
     * @param array       $values Список доступных значений
     * @param string|null $tag    Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkEnum($data, array $values, string $tag = null): void;

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

    /**
     * Проверяет пустой или корректный адрес электронной почты
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkNullOrEmail($data, string $tag = null): void;

    /**
     * Проверяет формат номера телефона
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkPhone($data, string $tag = null): void;

    /**
     * Проверяет пустой или корректный номер телефона
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkNullOrPhone($data, string $tag = null): void;

    /**
     * Проверяет идентичность данных
     *
     * @param bool        $exp Логическое выражение
     * @param string|null $tag Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkExists(bool $exp, string $tag = null): void;

}

<?php declare(strict_types = 1);

/**
 * Validator.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\API\Utils\Exceptions\FormException;

/**
 * Реализует методы проверки параметров
 *
 * @package  XEAF\Rack\API\Utils
 */
class Validator implements IFactoryObject {

    /**
     * Значение не модет быть пустым
     */
    private const EMPTY_VALUE = 'validator.EMPTY_VALUE';

    /**
     * Некорреткное значение
     */
    private const INVALID_VALUE = 'validator.INVALID_VALUE';

    /**
     * Некорреткный формат значения
     */
    private const INVALID_FORMAT = 'validator.INVALID_FORMAT';

    /**
     * Некорретный адрес электронной почты
     */
    private const INVALID_EMAIL = 'validator.INVALID_EMAIL';

    /**
     * Объект методов работы со строками
     * @var \XEAF\Rack\API\Utils\Strings
     */
    private $_strings;

    /**
     * Конструктор класса
     */
    public function __construct() {
        $l10n = Localization::getInstance();
        $l10n->registerLanguageClass(self::class);
        $this->_strings = Strings::getInstance();
    }

    /**
     * Проверяет наличие значения
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkNotEmpty($data, string $tag = null): void {
        $test = (string)$data;
        if (!$test) {
            throw FormException::badRequest(self::EMPTY_VALUE, [], $tag);
        }
    }

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
    public function checkLength($data, int $minLength = 0, int $maxLength = 0, string $tag = null): void {
        $this->checkNotEmpty($data);
        $value = (string)$data;
        if ($minLength > 0 && mb_strlen($value) < $minLength) {
            throw FormException::badRequest(self::INVALID_FORMAT, [], $tag);
        }
        if ($maxLength > 0 && mb_strlen($value) > $maxLength) {
            throw FormException::badRequest(self::INVALID_FORMAT, [], $tag);
        }
    }

    /**
     * Проверяет уникальный идентификатор
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkUUID($data, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data);
        if (!$this->_strings->isUUID($test)) {
            throw FormException::badRequest(self::INVALID_VALUE, [], $tag);
        }
    }

    /**
     * Проверяет адрес электронной почты
     *
     * @param mixed       $data Данные для проверки
     * @param string|null $tag  Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkEmail($data, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data);
        if (!$this->_strings->isEmail($test)) {
            throw FormException::badRequest(self::INVALID_EMAIL, [], $tag);
        }
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Utils\Validator
     */
    public static function getInstance(): Validator {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof Validator);
        return $result;
    }
}

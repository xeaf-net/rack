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
use XEAF\Rack\API\Interfaces\IValidator;
use XEAF\Rack\API\Utils\Exceptions\FormException;

/**
 * Реализует методы проверки параметров
 *
 * @package  XEAF\Rack\API\Utils
 */
class Validator implements IValidator {

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
     * @inheritDoc
     */
    public function checkNotEmpty($data, string $tag = null): void {
        $test = (string)$data;
        if (!$test) {
            throw FormException::badRequest(self::EMPTY_VALUE, [], $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkLength($data, int $minLength = 0, int $maxLength = 0, string $tag = null): void {
        $this->checkNotEmpty($data, $tag);
        $value = (string)$data;
        if ($minLength > 0 && mb_strlen($value) < $minLength) {
            throw FormException::badRequest(self::INVALID_FORMAT, [], $tag);
        }
        if ($maxLength > 0 && mb_strlen($value) > $maxLength) {
            throw FormException::badRequest(self::INVALID_FORMAT, [], $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkUUID($data, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data, $tag);
        if (!$this->_strings->isUUID($test)) {
            throw FormException::badRequest(self::INVALID_VALUE, [], $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkEmail($data, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data, $tag);
        if (!$this->_strings->isEmail($test)) {
            throw FormException::badRequest(self::INVALID_EMAIL, [], $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkEnum($data, array $values, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data, $tag);
        if (!in_array($test, $values)) {
            throw FormException::badRequest(self::INVALID_VALUE, [], $tag);
        }
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\IValidator
     */
    public static function getInstance(): IValidator {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IValidator);
        return $result;
    }
}

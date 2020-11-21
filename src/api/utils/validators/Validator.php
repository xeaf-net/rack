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
namespace XEAF\Rack\API\Utils\Validators;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IValidator;
use XEAF\Rack\API\Utils\Exceptions\FormException;
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Strings;

/**
 * Реализует методы проверки параметров
 *
 * @package  XEAF\Rack\API\Utils\Validators
 */
class Validator implements IValidator {

    /**
     * Значение не модет быть пустым
     */
    private const EMPTY_VALUE = 'Validator.EMPTY_VALUE';

    /**
     * Некорреткное значение
     */
    private const INVALID_VALUE = 'Validator.INVALID_VALUE';

    /**
     * Некорректный формат целого числа
     */
    private const INVALID_INTEGER = 'Validator.INVALID_INTEGER';

    /**
     * Некорректный формат числа
     */
    private const INVALID_NUMERIC = 'Validator.INVALID_NUMERIC';

    /**
     * Некорреткный формат значения
     */
    private const INVALID_FORMAT = 'Validator.INVALID_FORMAT';

    /**
     * Некорретный адрес электронной почты
     */
    private const INVALID_EMAIL = 'Validator.INVALID_EMAIL';

    /**
     * Некорретный адрес электронной почты
     */
    private const INVALID_PHONE = 'Validator.INVALID_PHONE';

    /**
     * Такое значение уже существует
     */
    private const VALUE_EXISTS = 'Validator.VALUE_EXISTS';

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
    public function checkIsInteger($data, string $tag = null): void {
        if (!$this->_strings->isInteger((string)$data)) {
            throw FormException::badRequest(self::INVALID_INTEGER, [], $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkIsNumber($data, string $tag = null): void {
        if (!$this->_strings->isFloat((string)$data)) {
            throw FormException::badRequest(self::INVALID_NUMERIC, [], $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkFormat($data, string $pattern, string $tag = null): void {
        $test = (string)$data;
        if (!preg_match($pattern, $test)) {
            throw FormException::badRequest(self::INVALID_EMAIL, [], $tag);
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
    public function checkNullOrEmail($data, string $tag = null): void {
        if ($this->_strings->isEmpty($data)) {
            $this->checkEmail($data, $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPhone($data, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data, $tag);
        if (!$this->_strings->isPhoneNumber($test)) {
            throw FormException::badRequest(self::INVALID_PHONE, [], $tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkNullOrPhone($data, string $tag = null): void {
        if (!$this->_strings->isEmail($data)) {
            $this->checkPhone($data, $tag);
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
     * Проверяет идентичность данных
     *
     * @param bool        $exp Логическое выражение
     * @param string|null $tag Тег
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\FormException
     */
    public function checkExists(bool $exp, string $tag = null): void {
        if ($exp) {
            throw FormException::badRequest(self::VALUE_EXISTS, [], $tag);
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

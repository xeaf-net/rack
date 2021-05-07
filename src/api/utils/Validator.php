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
use XEAF\Rack\API\Utils\Exceptions\ValidatorException;

/**
 * Реализует методы проверки параметров
 *
 * @package  XEAF\Rack\API\Utils
 */
class Validator implements IValidator {

    /**
     * Объект методов работы со строками
     * @var \XEAF\Rack\API\Utils\Strings
     */
    private $_strings;

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->_strings = Strings::getInstance();
    }

    /**
     * @inheritDoc
     */
    public function checkNotEmpty($data, string $tag = null): void {
        $test = (string)$data;
        if ($this->_strings->isEmpty($test)) {
            throw ValidatorException::emptyValue($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkLength($data, int $minLength = 0, int $maxLength = 0, string $tag = null): void {
        $this->checkNotEmpty($data, $tag);
        $value = (string)$data;
        if ($minLength > 0 && mb_strlen($value) < $minLength) {
            throw ValidatorException::invalidStringLength($tag);
        }
        if ($maxLength > 0 && mb_strlen($value) > $maxLength) {
            throw ValidatorException::invalidStringLength($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkIsBoolean($data, string $tag = null): void {
        if (!$this->_strings->isBoolean((string)$data)) {
            throw ValidatorException::invalidBooleanFormat($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkIsInteger($data, string $tag = null): void {
        if (!$this->_strings->isInteger((string)$data)) {
            throw ValidatorException::invalidIntegerFormat($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkIntegerRange(int $data, int $min = null, int $max = null, string $tag = null): void {
        if ($min !== null && $data < $min) {
            throw ValidatorException::invalidRange($tag);
        }
        if ($max !== null && $data > $max) {
            throw ValidatorException::invalidRange($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkIsNumeric($data, string $tag = null): void {
        if (!$this->_strings->isNumeric((string)$data)) {
            throw ValidatorException::invalidNumericFormat($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkNumericRange(float $data, float $min = null, float $max = null, string $tag = null): void {
        if ($min !== null && $data < $min) {
            throw ValidatorException::invalidRange($tag);
        }
        if ($max !== null && $data > $max) {
            throw ValidatorException::invalidRange($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkFormat($data, string $pattern, string $tag = null): void {
        $test = (string)$data;
        if (!preg_match($pattern, $test)) {
            throw ValidatorException::invalidFormat($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkUUID($data, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data, $tag);
        if (!$this->_strings->isUUID($test)) {
            throw ValidatorException::invalidValue($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkEmail($data, string $tag = null): void {
        $test = (string)$data;
        $this->checkNotEmpty($data, $tag);
        if (!$this->_strings->isEmail($test)) {
            throw ValidatorException::invalidEmailFormat($tag);
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
            throw ValidatorException::invalidPhoneFormat($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkNullOrPhone($data, string $tag = null): void {
        if (!$this->_strings->isEmpty($data)) {
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
            throw ValidatorException::invalidValue($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkExists(bool $exp, string $tag = null): void {
        if ($exp) {
            throw ValidatorException::valueAlreadyExists($tag);
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

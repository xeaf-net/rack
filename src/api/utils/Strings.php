<?php declare(strict_types = 1);

/**
 * Strings.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IStrings;

/**
 * Реализует методы работы со строковыми данными
 *
 * @package XEAF\Rack\API\Utils
 */
class Strings implements IStrings {

    /**
     * Пустая строка
     */
    public const EMPTY = '';

    /**
     * Имя переменной префикса DSN
     */
    public const DSN_PREFIX = 'provider';

    /**
     * Шаблон уникального идентификатора
     */
    private const UUID_PATTERN = '/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/i';

    /**
     * Конструктор класса
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(?string $buf): bool {
        return $buf === null || $buf === self::EMPTY;
    }

    /**
     * @inheritDoc
     */
    public function emptyToNull(?string $buf): ?string {
        return $this->isEmpty($buf) ? null : $buf;
    }

    /**
     * @inheritDoc
     */
    public function emptyToEmpty(?string $buf): string {
        return $this->isEmpty($buf) ? self::EMPTY : $buf;
    }

    /**
     * @inheritDoc
     */
    public function stringToInteger(?string $buf, int $onError = null): ?int {
        return $this->isInteger($buf) ? intval($buf) : $onError;
    }

    /**
     * @inheritDoc
     */
    public function stringToNumeric(?string $buf, float $onError = null): ?float {
        return $this->isNumeric($buf) ? floatval($buf) : $onError;
    }

    /**
     * @inheritDoc
     */
    public function stringToDateTime(?string $buf, int $onError = null): ?int {
        return $this->isDateTime($buf) ? strtotime($buf) : $onError;
    }

    /**
     * @inheritDoc
     */
    public function isInteger(?string $buf): bool {
        return filter_var($buf, FILTER_VALIDATE_INT) === 0 || (filter_var($buf, FILTER_VALIDATE_INT) !== false);
    }

    /**
     * @inheritDoc
     */
    public function isNumeric(?string $buf): bool {
        return $this->isInteger($buf) || (filter_var($buf, FILTER_VALIDATE_FLOAT) !== false);
    }

    /**
     * @inheritDoc
     */
    public function isDateTime(?string $buf): bool {
        return !$this->isEmpty($buf) && strtotime($buf) !== false;
    }

    /**
     * @inheritDoc
     */
    public function isUUID(?string $buf): bool {
        $result = false;
        if ($buf != null) {
            $preg   = preg_match(self::UUID_PATTERN, $buf);
            $result = $preg !== false && $preg > 0;
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isEmail(?string $buf): bool {
        return filter_var($buf, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @inheritDoc
     */
    public function isPhoneNumber(?string $buf): bool {
        $len = strlen(preg_replace('/\D/', '', $buf));
        return $len == 11 || $len == 10;
    }

    /**
     * @inheritDoc
     */
    public function isObjectId(?string $buf): bool {
        return $this->isUUID($buf) || $this->isInteger($buf);
    }

    /**
     * @inheritDoc
     */
    public function startsWith(string $haystack, string $needle, bool $ignoreCase = false): bool {
        $length = mb_strlen($needle);
        return $ignoreCase ? (mb_substr(mb_strtoupper($haystack), 0, $length) === mb_strtoupper($needle))
            : (mb_substr($haystack, 0, $length) === $needle);
    }

    /**
     * @inheritDoc
     */
    public function endsWith(string $haystack, string $needle, bool $ignoreCase = false): bool {
        $length = mb_strlen($needle);
        if ($length == 0) {
            return true;
        }
        return $ignoreCase ? (mb_substr(mb_strtoupper($haystack), -$length) === mb_strtoupper($needle))
            : (mb_substr($haystack, -$length) === $needle);
    }

    /**
     * @inheritDoc
     */
    public function upperCaseFirst(string $buf): string {
        return self::isEmpty($buf) ? self::EMPTY
            : mb_strtoupper(mb_substr($buf, 0, 1)) . mb_strtolower(mb_substr($buf, 1));
    }

    /**
     * @inheritDoc
     */
    public function parseDSN(string $data): array {
        $result = [];
        if (!$this->isEmpty($data)) {
            $colonPos = strpos($data, ':');
            if ($colonPos !== false) {
                $provider = mb_substr($data, 0, $colonPos);
                $params   = $this->parseKeyValue(mb_substr($data, $colonPos + 1), ';');
            } else {
                $provider = $data;
                $params   = [];
            }
            $result = array_merge([self::DSN_PREFIX => $provider], $params);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function parseKeyValue(string $data, string $separator): array {
        $result = [];
        parse_str(str_replace($separator, "&", $data), $result);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function kebabToCamel(?string $kebab, bool $upperFirstChar = true): ?string {
        $result = null;
        if ($kebab != null) {
            $result = str_replace('-', '', ucwords($kebab, '-'));
            if (!$upperFirstChar) {
                $result = lcfirst($result);
            }
        }
        return $result;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\IStrings
     */
    public static function getInstance(): IStrings {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IStrings);
        return $result;
    }
}

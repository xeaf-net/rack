<?php

/**
 * EntityException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе ORM
 *
 * @package XEAF\Rack\ORM\Utils\Exceptions
 */
class EntityException extends Exception {

    /**
     * Синтаксическая ошибка в позиции
     */
    public const SYNTAX_ERROR = 'EF001';

    /**
     * Непарная скобка
     */
    public const UNPAIRED_BRACKET = 'EF002';

    /**
     * Незакрытая одинарная кавычка
     */
    public const UNCLOSED_SINGLE_QUOTE = 'EF003';

    /**
     * Некорректный псевдоним сущности связывания
     */
    public const INVALID_JOIN_ALIAS = 'EF004';

    /**
     * Неизвестное имя сущности
     */
    public const UNKNOWN_ENTITY = 'EF005';

    /**
     * Неизвестный псевдоним сущности
     */
    public const UNKNOWN_ENTITY_ALIAS = 'EF006';

    /**
     * Неизвестное свойство сущности
     */
    public const UNKNOWN_ENTITY_PROPERTY = 'EF007';

    /**
     * Пустое значение первичного ключа
     */
    public const PRIMARY_KEY_IS_NULL = 'EF008';

    /**
     * Нежиданный конец XQL выражения
     */
    public const UNEXPECTED_EXPRESSION_END = 'F009';

    /**
     * Внутренняя ошибка ORM
     */
    public const INTERNAL_ERROR = 'EF999';

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = '';
        switch ($code) {
            case self::SYNTAX_ERROR:
                $result = "Syntax error at position [%d].";
                break;
            case self::UNPAIRED_BRACKET:
                $result = "Unpaired bracket at position [%d].";
                break;
            case self::UNCLOSED_SINGLE_QUOTE:
                $result = "Unclosed single quote at position [%d].";
                break;
            case self::INVALID_JOIN_ALIAS:
                $result = 'Invalid JOIN alias [%s].';
                break;
            case self::UNKNOWN_ENTITY:
                $result = 'Unknown entity [%s].';
                break;
            case self::UNKNOWN_ENTITY_ALIAS:
                $result = 'Unknown entity alias [%s].';
                break;
            case self::UNKNOWN_ENTITY_PROPERTY:
                $result = 'Unknown entity property [%s::%s]';
                break;
            case self::PRIMARY_KEY_IS_NULL:
                $result = 'Primary key is NULL.';
                break;
            case self::UNEXPECTED_EXPRESSION_END:
                $result = 'Unexpected expression end.';
                break;
            case self::INTERNAL_ERROR:
                $result = 'Internal ORM error.';
                break;
        }
        return $result;
    }

    /**
     * Синтаксическая ошибка
     *
     * @param int $position Позиция в строке
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function syntaxError(int $position): self {
        return new self(self::SYNTAX_ERROR, [$position]);
    }

    /**
     * Непарная скобка
     *
     * @param int $position Позиция в строке
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function unpairedBracket(int $position): self {
        return new self(self::UNPAIRED_BRACKET, [$position]);
    }

    /**
     * Незакрытая одинарная кавычка
     *
     * @param int $position Позиция в строке
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function unclosedSingleQuote(int $position): self {
        return new self(self::UNCLOSED_SINGLE_QUOTE, [$position]);
    }

    /**
     * Некорректный псевдоним сущности связывания
     *
     * @param string $alias Псевдоним
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function invalidJoinAlias(string $alias): self {
        return new self(self::INVALID_JOIN_ALIAS, [$alias]);
    }

    /**
     * Неизвестное имя сущности
     *
     * @param string $entity Имя сущности
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function unknownEntity(string $entity): self {
        return new self(self::UNKNOWN_ENTITY, [$entity]);
    }

    /**
     * Неизвестный псевдоним сущности
     *
     * @param string $alias Псевдоним сущности
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function unknownEntityAlias(string $alias): self {
        return new self(self::UNKNOWN_ENTITY_ALIAS, [$alias]);
    }

    /**
     * Неизвестное свойство сущности
     *
     * @param string $entity   Имя сущности
     * @param string $property Имя свойства
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function unknownEntityProperty(string $entity, string $property): self {
        return new self(self::UNKNOWN_ENTITY_PROPERTY, [$entity, $property]);
    }

    /**
     * Пустое значение первичного ключа
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function primaryKeyIsNull(): self {
        return new self(self::PRIMARY_KEY_IS_NULL);
    }

    /**
     * Неожиданные конец XQL выражения
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function unexpectedExpressionEnd(): self {
        return new self(self::UNEXPECTED_EXPRESSION_END);
    }

    /**
     * Внутренняя ошибка ORM
     *
     * @param \Throwable $reason Причина возникновения ошибки
     *
     * @return \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public static function internalError(Throwable $reason): self {
        return new self(self::INTERNAL_ERROR, [], $reason);
    }
}

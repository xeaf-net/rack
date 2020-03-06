<?php declare(strict_types = 1);

/**
 * DatabaseException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе с базой данных
 *
 * @package XEAF\Rack\Db\Utils\Exceptions
 */
class DatabaseException extends Exception {

    /**
     * Ошибка конифгурации базы данных
     */
    public const CONFIGURATION_ERROR = 'DB001';

    /**
     * Ошибка подключения к базе данных
     */
    public const CONNECTION_ERROR = 'DB002';

    /**
     * Ошибка работы с транзакциями
     */
    public const TRANSACTION_ERROR = 'DB003';

    /**
     * Ошибка исполнения SQL команды
     */
    public const SQL_COMMAND_ERROR = 'DB004';

    /**
     * Ошибка исполнения SQL запроса
     */
    public const SQL_QUERY_ERROR = 'DB005';

    /**
     * Нет открытого соединения
     */
    public const CONNECTION_NOD_OPENED = 'DB006';

    /**
     * Нет открытой транзакции
     */
    public const NO_ACTIVE_TRANSACTION = 'DB007';

    /**
     * Соединение с базой данных уже установлено
     */
    public const CONNECTION_ALREADY_OPENED = 'DB008';

    /**
     * Транзакация уже открыта
     */
    public const TRANSACTION_ALREADY_STARTED = 'DB009';

    /**
     * Ошибка конфигурации базы данных
     *
     * @param string     $name   Идентификатор базы данных
     * @param \Throwable $reason Причина ошибки
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function configurationError(string $name, Throwable $reason): self {
        return new self(self::CONFIGURATION_ERROR, [$name], $reason);
    }

    /**
     * Ошибка подключения к базе данных
     *
     * @param string     $name   Идентификатор базы данных
     * @param \Throwable $reason Причина ошибки
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function connectionError(string $name, Throwable $reason): self {
        return new self(self::CONNECTION_ERROR, [$name], $reason);
    }

    /**
     * Ошибка работы с транзакциями
     *
     * @param string     $name   Идентификатор базы данных
     * @param \Throwable $reason Причина ошибки
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function transactionError(string $name, Throwable $reason): self {
        return new self(self::TRANSACTION_ERROR, [$name], $reason);
    }

    /**
     * Ошибка исполнения SQL команды
     *
     * @param string     $name   Идентификатор базы данных
     * @param \Throwable $reason Причина ошибки
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function sqlCommandError(string $name, Throwable $reason): self {
        return new self(self::SQL_COMMAND_ERROR, [$name], $reason);
    }

    /**
     * Ошибка исполнения SQL запроса
     *
     * @param string     $name   Идентификатор базы данных
     * @param \Throwable $reason Причина ошибки
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function sqlQueryError(string $name, Throwable $reason): self {
        return new self(self::SQL_QUERY_ERROR, [$name], $reason);
    }

    /**
     * Нет открытого соединения
     *
     * @param string $name Идентификатор базы данных
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function noOpenConnection(string $name): self {
        return new self(self::CONNECTION_NOD_OPENED, [$name]);
    }

    /**
     * Нет активной транзакции
     *
     * @param string $name Идентификатор базы данных
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function noActiveTransaction(string $name): self {
        return new self(self::NO_ACTIVE_TRANSACTION, [$name]);
    }

    /**
     * Соединение с базой данных уже установлено
     *
     * @param string $name Идентификатор базы данных
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function connectionAlreadyOpened(string $name): self {
        return new self(self::CONNECTION_ALREADY_OPENED, [$name]);
    }

    /**
     * Транзакация уже открыта
     *
     * @param string $name Идентификатор базы данных
     *
     * @return \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public static function transactionAlreadyStarted(string $name): self {
        return new self(self::TRANSACTION_ALREADY_STARTED, [$name]);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = '';
        switch ($code) {
            case self::CONFIGURATION_ERROR:
                $result = 'Database [%s] configuration error.';
                break;
            case self::CONNECTION_ERROR:
                $result = 'Database [%s] connection error.';
                break;
            case self::TRANSACTION_ERROR:
                $result = 'Database [%s] transaction error.';
                break;
            case self::SQL_COMMAND_ERROR:
                $result = 'SQL command error on database [%s].';
                break;
            case self::SQL_QUERY_ERROR:
                $result = 'SQL query error on database [%s].';
                break;
            case self::CONNECTION_NOD_OPENED:
                $result = 'There is no open connection for database [%s].';
                break;
            case self::NO_ACTIVE_TRANSACTION:
                $result = 'There is no active transaction for database [%s].';
                break;
            case self::CONNECTION_ALREADY_OPENED:
                $result = 'Connection already opened for database [%s].';
                break;
            case self::TRANSACTION_ALREADY_STARTED:
                $result = 'Transaction already started for database [%s].';
                break;
        }
        return $result;
    }
}

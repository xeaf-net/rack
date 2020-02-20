<?php

/**
 * RedisException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Redis\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при работе с сервером Redis
 *
 * @package XEAF\Rack\Redis\Utils\Exceptions
 */
class RedisException extends Exception {

    /**
     * Ошибка подключения к серверу Redis
     */
    public const CONNECTION_ERROR = 'RDS001';

    /**
     * Ошибка выбора базы данных
     */
    public const DBINDEX_ERROR = 'RDS002';

    /**
     * Ошибка получения данных с сервера
     */
    public const GETTING_ERROR = 'RDS003';

    /**
     * Ошибка сохранения данных на сервере
     */
    public const PUTTING_ERROR = 'RDS004';

    /**
     * Ошибка подключения к серверу Redis
     *
     * @param string     $name   Имя сервера
     * @param \Throwable $reason Причина возникновения ошибки
     *
     * @return \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public static function connectionError(string $name, Throwable $reason): self {
        return new self(self::CONNECTION_ERROR, [$name], $reason);
    }

    /**
     * Ошибка выбора базы данных
     *
     * @param string     $name    Имя сервера
     * @param int        $dbindex Индекс базы данных
     * @param \Throwable $reason  Причина возникновения ошибки
     *
     * @return \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public static function dbindexError(string $name, int $dbindex, Throwable $reason): self {
        return new self(self::DBINDEX_ERROR, [$name, $dbindex], $reason);
    }

    /**
     * Ошибка получения данных с сервера
     *
     * @param string     $name   Имя базы данных
     * @param \Throwable $reason Причина возникновения ошибки
     *
     * @return \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public static function gettingError(string $name, Throwable $reason): self {
        return new self(self::GETTING_ERROR, [$name], $reason);
    }

    /**
     * Ошибка сохранения данных на сервере
     *
     * @param string     $name   Имя сервера
     * @param \Throwable $reason Причина возникновения ошибки
     *
     * @return \XEAF\Rack\Redis\Utils\Exceptions\RedisException
     */
    public static function puttingError(string $name, Throwable $reason): self {
        return new self(self::PUTTING_ERROR, [$name], $reason);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = null;
        switch ($code) {
            case self::CONNECTION_ERROR:
                $result = 'Could not connect to Redis server [%s].';
                break;
            case self::DBINDEX_ERROR:
                $result = 'Could not select Redis database [$%s:%d].';
                break;
            case self::GETTING_ERROR:
                $result = 'Error while getting data from Redis server [%s].';
                break;
            case self::PUTTING_ERROR:
                $result = 'Error while sending data to Redis server [%s].';
                break;
        }
        return $result;
    }
}

<?php declare(strict_types = 1);

/**
 * PoliticException.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use XEAF\Rack\API\Core\Exception;

/**
 * Исключения политики безопасности
 *
 * @package  XEAF\Rack\API\Utils\Exceptions
 */
class PoliticException extends Exception {

    /**
     * Использование нативной сессии запрещено
     */
    public const PLT_NATIVE_SESSION = 'PLT001';

    /**
     * Использование режима отладки запрещено
     */
    public const PLT_DEBUG_MODE = 'PLT002';

    /**
     * Использование нативной сессии запрещено
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\PoliticException
     */
    public static function nativeSession(): self {
        return new self(self::PLT_NATIVE_SESSION);
    }

    /**
     * Использование нативной сессии запрещено
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\PoliticException
     */
    public static function debugMode(): self {
        return new self(self::PLT_DEBUG_MODE);
    }

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = '';
        switch ($code) {
            case self::PLT_NATIVE_SESSION:
                $result = 'Using a native session is prohibited.';
                break;
            case self::PLT_DEBUG_MODE:
                $result = 'Using debug mode is prohibited.';
                break;
        }
        return $result;
    }
}

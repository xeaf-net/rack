<?php

/**
 * ConfigurationException.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Exceptions;

use Throwable;
use XEAF\Rack\API\Core\Exception;

/**
 * Исключения при разборе параметров конфигурации
 *
 * @package XEAF\Rack\API\Utils\Exceptions
 */
class ConfigurationException extends Exception {

    /**
     * Не найден файл конфигурации
     */
    public const FILE_NOT_FOUND = 'CFG001';

    /**
     * Ошибка разбора файла конфигурации
     */
    public const PARSING_ERROR = 'CFG002';

    /**
     * Не найден раздел в файле конфигурации
     */
    public const SECTION_NOT_FOUND = 'CFG003';

    /**
     * Не найден параметр в файле конфигурации
     */
    public const PARAMETER_NOT_FOUND = 'CFG004';

    /**
     * @inheritDoc
     */
    protected function getFormat(string $code): ?string {
        $result = null;
        switch ($code) {
            case self::FILE_NOT_FOUND:
                $result = 'Could not open configuration file [%s].';
                break;
            case self::PARSING_ERROR:
                $result = 'Error while parsing configuration file.';
                break;
            case self::SECTION_NOT_FOUND:
                $result = 'Could not find section [%s] of configuration file.';
                break;
            case self::PARAMETER_NOT_FOUND:
                $result = 'Could not find parameter [%s.%s] of configuration file.';
                break;
        }
        return $result;
    }

    /**
     * Не найден файл конфигурации
     *
     * @param string $filePath Путь к файлу конфигурации
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public static function fileNotFound(string $filePath): self {
        return new self(self::FILE_NOT_FOUND, [$filePath]);
    }

    /**
     * Ошибка разбора файла конфигурации
     *
     * @param \Throwable $previous Причина возникновения исключения
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public static function parsingError(Throwable $previous): self {
        return new self(self::PARSING_ERROR, [], $previous);
    }

    /**
     * Не найден раздел в файле конфигурации
     *
     * @param string $section    Имя раздела
     * @param string $subsection Имя подраздела
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public static function sectionNotFound(string $section, string $subsection): self {
        $fullName = self::subsectionName($section, $subsection);
        return new self(self::SECTION_NOT_FOUND, [$fullName]);
    }

    /**
     * Не найден параметр в файле конфигурации
     *
     * @param string $section    Имя раздела
     * @param string $name       Параметр
     * @param string $subsection Имя подраздела
     *
     * @return \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     */
    public static function parameterNotFound(string $section, string $name, string $subsection = ''): self {
        $fullName = self::subsectionName($section, $subsection);
        return new self(self::PARAMETER_NOT_FOUND, [$fullName, $name]);
    }

    /**
     * Возвращает полное имя подсекции
     *
     * @param string $section    Имя секции
     * @param string $subsection Имя подсекции
     *
     * @return string
     */
    private static function subsectionName(string $section, string $subsection): string {
        return $subsection == '' ? $section : $section . '.' . $subsection;
    }
}

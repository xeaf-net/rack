<?php declare(strict_types = 1);

/**
 * FileLoggerProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Loggers;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\ILoggerProvider;
use XEAF\Rack\API\Models\Config\FileLoggerConfig;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Calendar;
use XEAF\Rack\API\Utils\Logger;

/**
 * Реализует методы провайдера файлового журнала
 *
 * @package XEAF\Rack\API\Utils\Loggers
 */
class FileLoggerProvider implements ILoggerProvider {

    use NamedObjectTrait;

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'file';

    /**
     * Расширение имени файла
     */
    protected const FILE_NAME_EXT = 'log';

    /**
     * Режим файла
     */
    protected const FILE_MODE = 0666;

    /**
     * Префиксы записей журала
     */
    public const LEVEL_PREFIXES = [
        Logger::ERROR   => 'ERR',
        Logger::WARNING => 'WNG',
        Logger::INFO    => 'INF',
        Logger::DEBUG   => 'DBG'
    ];

    /**
     * Имя файла журнала
     * @var string|null
     */
    private $_fileName = null;

    /**
     * Уровень записей из файла конфигурации
     * @var int
     */
    private $_configLevel = Logger::ERROR;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name        = $name;
        $config             = FileLoggerConfig::getInstance($name);
        $this->_fileName    = $this->getFileName($config->getPath(), $config->getPrefix());
        $this->_configLevel = $config->getLevel();
    }

    /**
     * @inheritDoc
     */
    public function getConfigLevel(): int {
        return $this->_configLevel;
    }

    /**
     * @inheritDoc
     */
    public function writeLog(int $level, string $message, $data = null): void {
        $text = self::logText($level, $message, $data);
        file_put_contents($this->_fileName, $text, FILE_APPEND | LOCK_EX);
        chmod($this->_fileName, self::FILE_MODE);
    }

    /**
     * Возвращает имя файла журнала
     *
     * @param string $path   Путь к файлам журнала
     * @param string $prefix Префикс имени файла
     *
     * @return string
     */
    protected function getFileName(string $path, string $prefix): string {
        $cal  = Calendar::getInstance();
        $date = $cal->normalizeDate();
        return "$path/$prefix-$date." . self::FILE_NAME_EXT;
    }

    /**
     * Возвращает дату в необходимом формате
     *
     * @return string
     */
    protected function getFormattedDate(): string {
        return date('Y-m-d', time());
    }

    /**
     * Возвращает дату и время в необходимом формате
     *
     * @return string
     */
    protected function getFormattedDateTime(): string {
        return date('Y-m-d H:i:s', time());
    }

    /**
     * Возвращает подготовленный текст для записи в журнал
     *
     * @param int        $level   Уровень записи
     * @param string     $message Текст сообщения
     * @param mixed|null $data    Дополнительная информация
     *
     * @return string
     */
    protected function logText(int $level, string $message, $data = null): string {
        $prefix = '[' . self::LEVEL_PREFIXES[$level] . '] ';
        $time   = $this->getFormattedDateTime();
        $debug  = __RACK_DEBUG_MODE__ && $data != null ? "\n" . print_r($data, true) : '';
        $lines  = explode("\n", $time . ' ' . $message . $debug);
        return $prefix . implode("\n" . $prefix, $lines) . "\n";
    }
}

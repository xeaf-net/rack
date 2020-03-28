<?php declare(strict_types = 1);

/**
 * Logger.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\ILogger;
use XEAF\Rack\API\Interfaces\ILoggerProvider;
use XEAF\Rack\API\Models\Config\LoggerConfig;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Models\Config\ProviderConfig;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Traits\ProviderFactoryTrait;

/**
 * Реализует методы журналирования
 *
 * @package XEAF\Rack\API\Utils
 */
class Logger implements ILogger {

    use NamedObjectTrait;
    use ProviderFactoryTrait;

    /**
     * Ошибки исполнения приложения
     */
    public const ERROR = 1;

    /**
     * Предупреждения
     */
    public const WARNING = 2;

    /**
     * Информационные сообщения
     */
    public const INFO = 3;

    /**
     * Информация для отладки
     */
    public const DEBUG = 4;

    /**
     * Имена уровней журанала
     */
    public const LEVEL_NAMES = [
        'ERROR'   => Logger::ERROR,
        'WARNING' => Logger::WARNING,
        'INFO'    => Logger::INFO,
        'DEBUG'   => Logger::DEBUG
    ];

    /**
     * Провайдер журнала операций
     * @var ILoggerProvider|null
     */
    private $_provider = null;

    /**
     * Уровень записей журнала операций
     * @var int
     */
    private $_level = self::ERROR;

    /**
     * Признак редима отладки
     * @var bool
     */
    private $_debugMode = false;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name      = $name;
        $this->_provider  = $this->createProvider();
        $this->_debugMode = PortalConfig::getInstance()->getDebugMode();
        $this->setLevel($this->_provider->getConfigLevel());
    }

    /**
     * @inheritDoc
     */
    public function getLevel(): int {
        return $this->_level;
    }

    /**
     * @inheritDoc
     */
    public function setLevel(int $level): void {
        $this->_level = $level;
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, $data = null): void {
        if ($this->_debugMode && $this->getLevel() >= self::DEBUG) {
            $this->_provider->writeLog(self::DEBUG, $message, $data);
        }
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, $data = null): void {
        if ($this->getLevel() >= self::INFO) {
            $this->_provider->writeLog(self::INFO, $message, $data);
        }
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, $data): void {
        if ($this->getLevel() >= self::WARNING) {
            $this->_provider->writeLog(self::WARNING, $message, $data);
        }
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, $data = null): void {
        if ($this->getLevel() >= self::ERROR) {
            $this->_provider->writeLog(self::ERROR, $message, $data);
        }
    }

    /**
     * @inheritDoc
     */
    public function exception(Throwable $exception): void {
        $this->error($exception->getMessage(), $exception);
    }

    /**
     * Выводит сообщение о фатальной ошибке и прекращает работу
     *
     * @param string $message Текст сообщения
     * @param null   $data    Дополнительная информация
     *
     * @return void
     */
    public static function fatalError(string $message, $data = null): void {
        $debugMode = PortalConfig::getInstance()->getDebugMode();
        print "FTL: $message\n\n";
        if ($debugMode && $data != null) {
            print_r($data);
        }
        die();
    }

    /**
     * Возвращает объект провайдера журнала операций
     *
     * @return \XEAF\Rack\API\Interfaces\ILoggerProvider
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    private function createProvider(): ILoggerProvider {
        $config    = ProviderConfig::getInstance(LoggerConfig::SECTION_NAME, $this->getName());
        $className = self::getProviderClass($config->getProvider());
        return new $className($this->getName());
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\API\Interfaces\ILogger
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): ILogger {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof ILogger);
        return $result;
    }
}

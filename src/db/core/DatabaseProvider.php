<?php declare(strict_types = 1);

/**
 * DatabaseProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Core;

use PDO;
use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\StdObject;
use XEAF\Rack\API\Interfaces\ILocalization;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Logger;
use XEAF\Rack\API\Utils\Strings;
use XEAF\Rack\Db\Interfaces\IDatabaseProvider;
use XEAF\Rack\Db\Models\Config\DatabaseConfig;
use XEAF\Rack\Db\Utils\Exceptions\DatabaseException;

/**
 * Реализует базовые методы для провайдеров подключения к базе данных
 *
 * @package XEAF\Rack\Db\Core
 */
abstract class DatabaseProvider extends StdObject implements IDatabaseProvider {

    use NamedObjectTrait;

    /**
     * SQL формат представления даты
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * SQL формат представления даты и времени
     */
    protected const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Представление константы FALSE в SQL
     */
    public const SQL_FALSE = 0;

    /**
     * Представление константы TRUE в SQL
     */
    public const SQL_TRUE = 1;

    /**
     * Ресурс подключения к базе данных
     * @var \PDO|null
     */
    protected ?PDO $_dbh = null;

    /**
     * Параметры конфигурации подключения
     * @var \XEAF\Rack\Db\Models\Config\DatabaseConfig|null
     */
    protected ?DatabaseConfig $_config;

    /**
     * Объект методов локализации
     * @var \XEAF\Rack\API\Interfaces\ILocalization|null
     */
    protected ?ILocalization $_localization;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\Db\Utils\Exceptions\DatabaseException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name         = $name;
        $this->_config       = DatabaseConfig::getInstance($name);
        $this->_localization = Localization::getInstance();
        $this->_localization->registerLanguageClass($this->getClassName());
        $this->connect();
    }

    /**
     * Метод уничтожения объекта класса
     */
    public function __destruct() {
        $this->disconnect();
    }

    /**
     * @inheritDoc
     */
    public function connect(): void {
        if (!$this->connected()) {
            $dsn     = $this->buildDSN();
            $options = $this->buildOptions();
            try {
                $this->_dbh = new PDO($dsn, $this->_config->getUser(), $this->_config->getPassword(), $options);
            } catch (Throwable $reason) {
                throw DatabaseException::connectionError($this->getName(), $reason);
            }
        } else {
            throw DatabaseException::connectionAlreadyOpened($this->getName());
        }
    }

    /**
     * @inheritDoc
     */
    public function disconnect(): void {
        if ($this->connected()) {
            try {
                if ($this->inTransaction()) {
                    $this->_dbh->rollBack();
                }
            } catch (Throwable $reason) {
                $logger = Logger::getInstance();
                $logger->error('Database disconnect error.', $reason);
            }
        }
        $this->_dbh = null;
    }

    /**
     * @inheritDoc
     */
    public function connected(): bool {
        return $this->_dbh != null;
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool {
        return $this->connected() && $this->_dbh->inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function startTransaction(): void {
        if (!$this->connected()) {
            throw DatabaseException::noOpenConnection($this->getName());
        }
        if (!$this->inTransaction()) {
            try {
                $this->_dbh->beginTransaction();
            } catch (Throwable $reason) {
                throw DatabaseException::transactionError($this->getName(), $reason);
            }
        } else {
            throw  DatabaseException::transactionAlreadyStarted($this->getName());
        }
    }

    /**
     * @inheritDoc
     */
    public function commitTransaction() {
        if (!$this->connected()) {
            throw DatabaseException::noOpenConnection($this->getName());
        }
        if ($this->inTransaction()) {
            try {
                $this->_dbh->commit();
            } catch (Throwable $reason) {
                throw DatabaseException::transactionError($this->getName(), $reason);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function rollbackTransaction(): void {
        if (!$this->connected()) {
            throw DatabaseException::noOpenConnection($this->getName());
        }
        if ($this->inTransaction()) {
            try {
                $this->_dbh->rollBack();
            } catch (Throwable $reason) {
                throw DatabaseException::transactionError($this->getName(), $reason);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function select(string $sql, array $params = [], int $count = 0, int $offset = 0): array {
        if (!$this->connected()) {
            throw DatabaseException::noOpenConnection($this->getName());
        } else {
            try {
                $qry = $this->limitSQL($sql, $count, $offset);
                $stm = $this->_dbh->prepare($qry);
                $stm->execute($params);
                return $stm->fetchAll();
            } catch (Throwable $reason) {
                throw DatabaseException::sqlQueryError($this->getName(), $reason);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function selectFirst(string $sql, array $params = []): ?array {
        $result  = null;
        $records = $this->select($sql, $params, 1);
        if ($records && count($records) > 0) {
            $result = $records[0];
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function execute(string $sql, array $params = []): int {
        if (!$this->inTransaction()) {
            throw DatabaseException::noActiveTransaction($this->getName());
        }
        try {
            $stm = $this->_dbh->prepare($sql);
            $stm->execute($params);
            return $stm->rowCount();
        } catch (Throwable $reason) {
            throw DatabaseException::sqlCommandError($this->getName(), $reason);
        }
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(): string {
        try {
            return $this->_dbh->lastInsertId();
        } catch (Throwable $reason) {
            throw DatabaseException::sqlCommandError($this->getName(), $reason);
        }
    }

    /**
     * Строит строку подключения к базе данных
     *
     * @return string
     */
    protected function buildDSN(): string {
        $result = '';
        $result .= $this->_config->getProvider() . ':';
        $result .= 'host=' . $this->_config->getHost() . ';';
        if ($this->_config->getPort() > 0) {
            $result .= 'port=' . $this->_config->getPort() . ';';
        }
        $result .= 'dbname=' . $this->_config->getDbName() . ';';
        if ($this->_config->getCharset() != '') {
            $result .= 'charset=' . $this->_config->getCharset() . ';';
        }
        return $result;
    }

    /**
     * Строит массив дполнительных параметров подключений
     *
     * @return array
     */
    protected function buildOptions(): array {
        $result = $this->defaultOptions();
        foreach ($this->connectionOptions() as $key => $option) {
            $result[$key] = $option;
        }
        return $result;
    }

    /**
     * Добавляет к тексту SQL запроса условия количественного отбора
     *
     * @param string $sql    Исходный текст запроса
     * @param int    $count  Количество записей
     * @param int    $offset Смещение
     *
     * @return string
     */
    protected function limitSQL(string $sql, int $count, int $offset): string {
        $limit = $count == 0 ? '' : "limit $count";
        if ($offset != 0) {
            $limit .= " offset $offset";
        }
        return "$sql $limit";
    }

    /**
     * Возвращает обязательные параметры подключения
     *
     * @return array
     */
    protected function defaultOptions(): array {
        $result                    = [];
        $result[PDO::ATTR_CASE]    = PDO::CASE_NATURAL;
        $result[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        // $result[PDO::ATTR_ORACLE_NULLS]       = PDO::NULL_TO_STRING;
        $result[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        $result[PDO::MYSQL_ATTR_FOUND_ROWS]   = true;
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function connectionOptions(): array {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function lowerCaseExpression(string $expression): string {
        return 'lower(' . $expression . ')';
    }

    /**
     * @inheritDoc
     */
    public function upperCaseExpression(string $expression): string {
        return 'upper(' . $expression . ')';
    }

    /**
     * @inheritDoc
     */
    public function formatDate(int $date): string {
        return date(self::DATE_FORMAT, $date);
    }

    /**
     * @inheritDoc
     */
    public function sqlDate(?string $date): ?int {
        $strings = Strings::getInstance();
        return $strings->stringToDateTime($date);
    }

    /**
     * @inheritDoc
     */
    public function formatDateTime(int $dateTime): string {
        return date(self::DATETIME_FORMAT, $dateTime);
    }

    /**
     * @inheritDoc
     */
    public function sqlDateTime(?string $dateTime): ?int {
        $strings = Strings::getInstance();
        return $strings->stringToDateTime($dateTime);
    }

    /**
     * @inheritDoc
     */
    public function formatBool(bool $flag): int {
        return $flag ? self::SQL_TRUE : self::SQL_FALSE;
    }

    /**
     * @inheritDoc
     */
    public function sqlBool(?int $flag): bool {
        return $flag !== null && $flag;
    }
}

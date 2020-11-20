<?php declare(strict_types = 1);

/**
 * Database.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Models\Config\ProviderConfig;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Traits\ProviderFactoryTrait;
use XEAF\Rack\Db\Interfaces\IDatabase;
use XEAF\Rack\Db\Interfaces\IDatabaseProvider;
use XEAF\Rack\Db\Models\Config\DatabaseConfig;

/**
 * Реализует методы работы с базой данных
 *
 * @package XEAF\Rack\Db\Utils
 */
class Database implements IDatabase {

    use NamedObjectTrait;
    use ProviderFactoryTrait;

    /**
     * Идентификатор продукта миграции
     */
    public const MIGRATION_PRODUCT = 'XEAF-RACK';

    /**
     * Объект провайдера подкючения к базе данных
     * @var \XEAF\Rack\Db\Interfaces\IDatabaseProvider
     */
    private IDatabaseProvider $_provider;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name     = $name;
        $this->_provider = $this->createProvider();
    }

    /**
     * Закрывает соединение с базой данных
     */
    public function __destruct() {
        $this->disconnect();
    }

    /**
     * @inheritDoc
     */
    public function connect(): void {
        $this->_provider->connect();
    }

    /**
     * @inheritDoc
     */
    public function disconnect(): void {
        $this->_provider->disconnect();
    }

    /**
     * @inheritDoc
     */
    public function connected(): bool {
        return $this->_provider->connected();
    }

    /**
     * @inheritDoc
     */
    public function inTransaction(): bool {
        return $this->_provider->inTransaction();
    }

    /**
     * @inheritDoc
     */
    public function startTransaction(): void {
        $this->_provider->startTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commitTransaction() {
        $this->_provider->commitTransaction();
    }

    /**
     * @inheritDoc
     */
    public function rollbackTransaction(): void {
        $this->_provider->rollbackTransaction();
    }

    /**
     * @inheritDoc
     */
    public function select(string $sql, array $params = [], int $count = 0, int $offset = 0): array {
        return $this->_provider->select($sql, $params, $count, $offset);
    }

    /**
     * @inheritDoc
     */
    public function selectFirst(string $sql, array $params = []): ?array {
        return $this->_provider->selectFirst($sql, $params);
    }

    /**
     * @inheritDoc
     */
    public function execute(string $sql, array $params = []): int {
        return $this->_provider->execute($sql, $params);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(): string {
        return $this->_provider->lastInsertId();
    }

    /**
     * @inheritDoc
     */
    public function connectionOptions(): array {
        return $this->_provider->connectionOptions();
    }

    /**
     * @inheritDoc
     */
    public function lowerCaseExpression(string $expression): string {
        return $this->_provider->lowerCaseExpression($expression);
    }

    /**
     * @inheritDoc
     */
    public function upperCaseExpression(string $expression): string {
        return $this->_provider->upperCaseExpression($expression);
    }

    /**
     * @inheritDoc
     */
    public function dateExpression(string $expression, string $locale = null): string {
        return $this->_provider->dateExpression($expression, $locale);
    }

    /**
     * @inheritDoc
     */
    public function timeExpression(string $expression, string $locale = null): string {
        return $this->_provider->timeExpression($expression, $locale);
    }

    /**
     * @inheritDoc
     */
    public function dateTimeExpression(string $expression, string $locale = null): string {
        return $this->_provider->dateTimeExpression($expression, $locale);
    }

    /**
     * Создает объект провайдера подключения к базе данных
     *
     * @return \XEAF\Rack\Db\Interfaces\IDatabaseProvider
     * @throws \XEAF\Rack\API\Utils\Exceptions\ConfigurationException
     * @throws \XEAF\Rack\API\Utils\Exceptions\ProviderException
     */
    protected function createProvider(): IDatabaseProvider {
        $config    = ProviderConfig::getInstance(DatabaseConfig::SECTION_NAME, $this->getName());
        $className = self::getProviderClass($config->getProvider());
        return new $className($this->getName());
    }

    /**
     * @inheritDoc
     */
    public function formatDate(int $date): string {
        return $this->_provider->formatDate($date);
    }

    /**
     * @inheritDoc
     */
    public function sqlDate(?string $date): ?int {
        return $this->_provider->sqlDate($date);
    }

    /**
     * @inheritDoc
     */
    public function formatDateTime(int $dateTime): string {
        return $this->_provider->formatDateTime($dateTime);
    }

    /**
     * @inheritDoc
     */
    public function sqlDateTime(?string $dateTime): ?int {
        return $this->_provider->sqlDateTime($dateTime);
    }

    /**
     * @inheritDoc
     */
    public function formatBool(bool $flag): int {
        return $this->_provider->formatBool($flag);
    }

    /**
     * @inheritDoc
     */
    public function sqlBool(?int $flag): bool {
        return $this->_provider->sqlBool($flag);
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @param string $name Имя объекта
     *
     * @return \XEAF\Rack\Db\Interfaces\IDatabase
     */
    public static function getInstance(string $name = Factory::DEFAULT_NAME): IDatabase {
        $result = Factory::getFactoryNamedObject(self::class, $name);
        assert($result instanceof IDatabase);
        return $result;
    }
}

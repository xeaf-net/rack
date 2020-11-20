<?php declare(strict_types = 1);

/**
 * TableResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего объектов данных для DataTable
 *
 * @property int $recordsTotal    Общее количество записей
 * @property int $recordsFiltered Количество отфильтрованных записей
 *
 * @package XEAF\Rack\API\Models\Results
 */
class TableResult extends ListResult {

    /**
     * Общее количество записей
     * @var int
     */
    protected int $_recordsTotal = 0;

    /**
     * Количество отфильтрованных записей
     * @var int
     */
    protected int $_recordsFiltered = 0;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection|null $list            Список объектов данных
     * @param int                                        $recordsTotal    Общее количество записей
     * @param int                                        $recordsFiltered Количество отфильбтрованных записей
     * @param bool                                       $useCache        Признак использования кеша
     * @param int                                        $status          Код состояния HTTP
     */
    public function __construct(?ICollection $list, int $recordsTotal = 0, int $recordsFiltered = 0, bool $useCache = false, int $status = HttpResponse::OK) {
        parent::__construct($list, $useCache, $status);
        $this->_recordsTotal    = $recordsTotal;
        $this->_recordsFiltered = $recordsFiltered;
    }

    /**
     * Возвращает общее количество записей
     *
     * @return int
     */
    public function getRecordsTotal(): int {
        if ($this->_recordsTotal == 0) {
            $this->_recordsTotal = $this->list->count();
        }
        return $this->_recordsTotal;
    }

    /**
     * Задает общее количество записей
     *
     * @param int $recordsTotal Общее количество записей
     *
     * @return void
     */
    public function setRecordsTotal(int $recordsTotal): void {
        $this->_recordsTotal = $recordsTotal;
    }

    /**
     * Возвращает количество отфильтрованных записей
     *
     * @return int
     */
    public function getRecordsFiltered(): int {
        if ($this->_recordsFiltered == 0) {
            $this->_recordsFiltered = $this->list->count();
        }
        return $this->_recordsFiltered;
    }

    /**
     * Задает количество отфильтрованных записей
     *
     * @param int $recordsFiltered Количество отфильтрованных записей
     *
     * @return void
     */
    public function setRecordsFiltered(int $recordsFiltered): void {
        $this->_recordsFiltered = $recordsFiltered;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function processResult(): void {
        $headers    = HttpResponse::getInstance();
        $serializer = Serializer::getInstance();
        $data       = [
            self::RESULT_PROPERTY => $this->getList()->toArray(),
            'recordsTotal'        => $this->getRecordsTotal(),
            'recordsFiltered'     => $this->getRecordsFiltered()
        ];
        $headers->responseCode($this->getStatusCode());
        $headers->contentJSON();
        print $serializer->jsonArrayEncode($data);
    }
}

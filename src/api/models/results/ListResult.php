<?php declare(strict_types = 1);

/**
 * ListResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Core\CachedResult;
use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего список объектов данных
 *
 * @property \XEAF\Rack\API\Core\Collection $list Список объектов данных
 *
 * @package XEAF\Rack\API\Models\Results
 */
class ListResult extends CachedResult {

    /**
     * Список объектов данных
     * @var \XEAF\Rack\API\Core\Collection|null
     */
    protected $_list = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection|null $list     Список объектов данных
     * @param bool                                       $useCache Признак использования кеша
     * @param int                                        $status   Код статуса HTTP
     */
    public function __construct(?ICollection $list, bool $useCache = false, int $status = HttpResponse::OK) {
        parent::__construct($status, $useCache);
        $this->_list = $list != null ? $list : new Collection();
    }

    /**
     * Возвращает список объектов данных
     *
     * @return \XEAF\Rack\API\Core\Collection|null
     */
    public function getList(): ?Collection {
        return $this->_list;
    }

    /**
     * Задает список объектов данных
     *
     * @param \XEAF\Rack\API\Core\Collection|null $list Список объектов данных
     *
     * @return void
     */
    public function setList(?Collection $list): void {
        $this->_list = $list;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function processResult(): void {
        $headers    = HttpResponse::getInstance();
        $serializer = Serializer::getInstance();
        $headers->responseCode($this->getStatusCode());
        $headers->contentJSON();
        print $serializer->jsonCollectionEncode($this->getList());
    }
}

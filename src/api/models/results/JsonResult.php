<?php declare(strict_types = 1);

/**
 * JsonResult.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Core\CachedResult;
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего простейший JSON
 *
 * @property \XEAF\Rack\API\Core\DataObject $dataObject Объект данных
 *
 * @package XEAF\Rack\API\Models\Results
 */
class JsonResult extends CachedResult {

    /**
     * Массив возвращаемых данных
     * @var array
     */
    protected array $_data;

    /**
     * Конструктор класса
     *
     * @param array $data     Массив данных
     * @param bool  $useCache Признак использования кеша
     * @param int   $status   Код статуса HTTP
     */
    public function __construct(array $data = [], bool $useCache = false, int $status = HttpResponse::OK) {
        parent::__construct($status, $useCache);
        $this->_data = $data;
    }

    /**
     * Возвращает массив данных
     *
     * @return array
     */
    public function getData(): array {
        return $this->_data;
    }

    /**
     * Задает  массив данных
     *
     * @param array $data Массив данных
     *
     * @return void
     */
    public function setData(array $data): void {
        $this->_data = $data;
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
        if ($this->getUseCache()) {
            $headers->fileCacheHeader();
        }
        if (!$this->_headersOnly) {
            print $serializer->jsonArrayEncode($this->_data);
        }
    }

    /**
     * Создает объект данных результата из объекта данных
     *
     * @param \XEAF\Rack\API\Core\DataObject $dataObject Объект данных
     *
     * @return \XEAF\Rack\API\Models\Results\JsonResult
     */
    public static function dataObject(DataObject $dataObject): self {
        return new self($dataObject->toArray());
    }

    /**
     * Создает объект данных результата по объявлению из хранилища Ключ-Значение
     *
     * @param \XEAF\Rack\API\Interfaces\IKeyValue|null $storage Хранилище
     *
     * @return \XEAF\Rack\API\Models\Results\JsonResult
     */
    public static function dataKeyValue(?IKeyValue $storage): self {
        $data = $storage == null ? [] : $storage->toArray();
        return new self($data);
    }
}

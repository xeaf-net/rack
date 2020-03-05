<?php declare(strict_types = 1);

/**
 * DataResult.php
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
use XEAF\Rack\API\Core\DataObject;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего объект данных
 *
 * @property \XEAF\Rack\API\Core\DataObject $dataObject Объект данных
 *
 * @package XEAF\Rack\API\Models\Results
 */
class DataResult extends CachedResult {

    /**
     * Возвращаемый объект данных
     * @var \XEAF\Rack\API\Core\DataObject|null
     */
    protected $_dataObject = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
     * @param bool                                $useCache   Признак исопльзования кеша
     * @param int                                 $status     Код статуса HTTP
     */
    public function __construct(DataObject $dataObject = null, bool $useCache = false, int $status = HttpResponse::OK) {
        parent::__construct($status, $useCache);
        $this->_dataObject = $dataObject != null ? $dataObject : new DataObject();
    }

    /**
     * Возвращает объект данных
     *
     * @return \XEAF\Rack\API\Core\DataObject
     */
    public function getDataObject(): DataObject {
        return $this->_dataObject;
    }

    /**
     * Задает объект данных
     *
     * @param \XEAF\Rack\API\Core\DataObject $dataObject Объект данных
     *
     * @return void
     */
    public function setDataObject(DataObject $dataObject): void {
        $this->_dataObject = $dataObject;
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
        print $serializer->jsonDataObjectEncode($this->getDataObject());
    }

    /**
     * Создает объект данных результата по объявлению из массива
     *
     * @param array $data Данные инициализации
     *
     * @return \XEAF\Rack\API\Models\Results\DataResult
     */
    public static function dataArray(array $data): self {
        $dataObject = new DataObject($data);
        return new self($dataObject);
    }

    /**
     * Создает объект данных результата по объявлению из хранилища Ключ-Значение
     *
     * @param \XEAF\Rack\API\Interfaces\IKeyValue|null $storage Хранилище
     *
     * @return \XEAF\Rack\API\Models\Results\DataResult
     */
    public static function dataKeyValue(?IKeyValue $storage): self {
        $data = $storage == null ? [] : $storage->toArray();
        return self::dataArray($data);
    }
}

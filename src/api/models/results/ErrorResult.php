<?php

/**
 * ErrorResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Core\ActionResult;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего информацию о ошибке
 *
 * @property       string $message      Текст сообщения об ошибке
 * @property-read  array  $objectErrors Информация об ошибках по объектам
 *
 * @package XEAF\Rack\API\Models\Results
 */
class ErrorResult extends ActionResult {

    /**
     * Текст сообщения об ошибке
     * @var string
     */
    protected $_message = '';

    /**
     * Список ошибок по объектам
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    protected $_objectErrors = null;

    /**
     * Конструктор класса
     *
     * @param string $message Сообщение об ошибке
     * @param int    $status  Код статуса HTTP
     */
    public function __construct(string $message = '', int $status = HttpResponse::OK) {
        parent::__construct($status);
        $this->_message      = $message;
        $this->_objectErrors = new KeyValue();
    }

    /**
     * Возвращает сообщение об ошибке
     *
     * @return string
     */
    public function getMessage(): string {
        return $this->_message;
    }

    /**
     * Задает сообщение об ошибке
     *
     * @param string $message Текст сообщения об ошибке
     *
     * @return void
     */
    public function setMessage(string $message): void {
        $this->_message = $message;
    }

    /**
     * Возвращает информацию об ошибках объектов
     *
     * @return \XEAF\Rack\API\Interfaces\IKeyValue
     */
    public function getObjectErrors(): IKeyValue {
        return $this->_objectErrors;
    }

    /**
     * Добавляет информацию об ошибке объекта
     *
     * @param string $id      Идентификатор объекта
     * @param string $message Текст сообщения
     *
     * @return void
     */
    public function addObjectError(string $id, string $message): void {
        $this->_objectErrors->put($id, $message);
    }

    /**
     * @inheritDoc
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function processResult(): void {
        $headers    = HttpResponse::getInstance();
        $serializer = Serializer::getInstance();
        $headers->responseCode($this->getStatusCode());
        if ($this->getMessage() || !$this->getObjectErrors()->isEmpty()) {
            $headers->contentJSON();
            print $serializer->jsonDataObjectEncode($this);
        }
    }
}

<?php declare(strict_types = 1);

/**
 * ISerializer.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Core\DataObject;

/**
 * Описывает методы сериализации данных
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ISerializer extends IFactoryObject {

    /**
     * Возвращает представление массива в формате JSON
     *
     * @param array $data Массив данных
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonArrayEncode(array $data): string;

    /**
     * Восстанавливает массив из данных в формате JSON
     *
     * @param string $json Данные в формате JSON
     *
     * @return array
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonArrayDecode(string $json): array;

    /**
     * Возвращает JSON представление объекта
     *
     * @param object|null $obj Объект
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonObjectEncode(object $obj = null): string;

    /**
     * Создает объект из JSON
     *
     * @param string $json Исходные данные в формате JSON
     *
     * @return object
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonObjectDecode(string $json): object;

    /**
     * Возвращает JSON представление объекта данных
     *
     * @param \XEAF\Rack\API\Core\DataObject|null $dataObject Объект данных
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonDataObjectEncode(DataObject $dataObject = null): string;

    /**
     * Создает объект данных из JSON
     *
     * @param string $json Исходные данные в формате JSON
     *
     * @return \XEAF\Rack\API\Core\DataObject
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonDataObjectDecode(string $json): DataObject;

    /**
     * Возвращает JSON представление коллекции объектов данных
     *
     * @param \XEAF\Rack\API\Core\Collection $list Список объектов данных
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonCollectionEncode(Collection $list): string;

    /**
     * Создает коллекцию объектов данных из JSON
     *
     * @param string $json Исходные данные в формате JSON
     *
     * @return \XEAF\Rack\API\Core\Collection
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonCollectionDecode(string $json): Collection;

    /**
     * Восстанавливает массив из файла данных в формате JSON
     *
     * @param string $fileName Имя файла
     * @param bool   $comments Признак наличия комментариев в файле
     *
     * @return array
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonDecodeFileArray(string $fileName, bool $comments = false): array;

    /**
     * Восстанавливает объект из файла данных в формате JSON
     *
     * @param string $fileName Имя файла
     * @param bool   $comments Признак наличия комментариев в файле
     *
     * @return object
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function jsonDecodeFileObject(string $fileName, bool $comments = false): object;

    /**
     * Сериализует данные для сохранения
     *
     * @param mixed  $data     Исходные данные
     * @param string $password Пароль для хеша
     *
     * @return string
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function serialize($data, string $password = ''): string;

    /**
     * Восстанавливает данные из сериализованного представления
     *
     * @param string $serialized Сериализованные данные
     * @param string $password   Пароль для хеша
     *
     * @return mixed
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function unserialize(string $serialized, string $password = '');
}

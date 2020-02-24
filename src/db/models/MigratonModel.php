<?php

/**
 * MigratonModel.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Db\Models;

use XEAF\Rack\API\Core\DataModel;

/**
 * Модель данных миграции
 *
 * @property string $product   Идентификатор продукта
 * @property string $version   Номер версии
 * @property string $timestamp Дата и время миграции
 * @property string $comment   Комментарий
 *
 * @package XEAF\Rack\Db\Models
 *
 * @since 1.0.3
 */
class MigrationModel extends DataModel {

    /**
     * Идентификатор продукта
     * @var string|null
     */
    protected $_product = null;

    /**
     * Номер версии
     * @var string|null
     */
    protected $_version = null;

    /**
     * Дата и время миграции
     * @var int|null
     */
    protected $_timestamp = null;

    /**
     * Комментарий
     * @var string|null
     */
    protected $_comment = null;

    /**
     * Возвращает идентификатор продукта
     *
     * @return string|null
     */
    public function getProduct(): ?string {
        return $this->_product;
    }

    /**
     * Задает идентификатор продукта
     *
     * @param string|null $product Идентификатор продукта
     *
     * @return void
     */
    public function setProduct(?string $product): void {
        $this->_product = $product;
    }

    /**
     * Возвращает номер версии миграции
     *
     * @return string|null
     */
    public function getVersion(): ?string {
        return $this->_version;
    }

    /**
     * Задает номер версии миграции
     *
     * @param string|null $version Номер версии миграции
     *
     * @return void
     */
    public function setVersion(?string $version): void {
        $this->_version = $version;
    }

    /**
     * Возвращает дату и время миграции
     *
     * @return int|null
     */
    public function getTimestamp(): ?int {
        return $this->_timestamp;
    }

    /**
     * Задает дату и время миграции
     *
     * @param int|null $timestamp Дата и время миграции
     *
     * @return void
     */
    public function setTimestamp(?int $timestamp): void {
        $this->_timestamp = $timestamp;
    }

    /**
     * Возвращет комментарий
     *
     * @return string|null
     */
    public function getComment(): ?string {
        return $this->_comment;
    }

    /**
     * Задает комментарий
     *
     * @param string|null $comment Комментарий
     *
     * @return void
     */
    public function setComment(?string $comment): void {
        $this->_comment = $comment;
    }
}

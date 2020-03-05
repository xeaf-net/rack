<?php declare(strict_types = 1);

/**
 * FromModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\DataModel;

/**
 * Описыват свойства модели данных конструкции FROM
 *
 * @property-read string $entity Имя сущности
 * @property-read string $alias  Псевдоним
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 */
class FromModel extends DataModel {

    /**
     * Имя сущности
     * @var string
     */
    protected $_entity = '';

    /**
     * Псевдоним
     * @var string
     */
    protected $_alias = '';

    /**
     * Конструктор класса
     *
     * @param string $entity Имя сущности
     * @param string $alias  Псевдоним
     */
    public function __construct(string $entity, string $alias) {
        parent::__construct();
        $this->_entity = $entity;
        $this->_alias  = $alias;
    }

    /**
     * Возвращает сущность
     *
     * @return string
     */
    public function getEntity(): string {
        return $this->_entity;
    }

    /**
     * Возвращает псевдоним
     *
     * @return string
     */
    public function getAlias(): string {
        return $this->_alias;
    }
}

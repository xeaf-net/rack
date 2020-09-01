<?php declare(strict_types = 1);

/**
 * ManyToManyProperty.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\RelationTypes;

/**
 * Реализует методы свойства отношения Многие ко многим
 *
 * @property-read string $interEntity Имя промежуточной сущности
 * @property array       $interLinks  Свойства связи прмежуточной сущности
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class ManyToManyProperty extends RelationModel {

    /**
     * Имя промежуточной сущности
     * @var string
     */
    protected $_interEntity;

    /**
     * Свойства связи промежуточной сущности
     * @var array
     */
    protected $_interLinks;

    /**
     * Конструктор класса
     *
     * @param string $entity      Имя сущности
     * @param string $interEntity Имя промежуточной сщности
     * @param array  $links       Свойства связи
     * @param array  $interLinks  Свойства связи промежуточной сущности
     */
    public function __construct(string $entity, string $interEntity, array $links = [], array $interLinks = []) {
        parent::__construct(RelationTypes::MANY_TO_MANY, $entity, $links);
        $this->_interEntity = $interEntity;
        $this->_interLinks  = $interLinks;
    }

    /**
     * Возвращает имя промежуточной сущности
     *
     * @return string
     */
    public function getInterEntity(): string {
        return $this->_interEntity;
    }

    /**
     * Возвращает свойства связи промежуточной сущности
     *
     * @return array
     */
    public function getInterLinks(): array {
        return $this->_interLinks;
    }

    /**
     * Задает свойства связи промежутчной сущности
     *
     * @param array $interLinks Свойства связей промежуточной сущности
     *
     * @return void
     */
    public function setInterLinks(array $interLinks): void {
        $this->_interLinks = $interLinks;
    }
}

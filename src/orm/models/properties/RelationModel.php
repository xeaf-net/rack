<?php declare(strict_types = 1);

/**
 * RelationModel.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\AccessTypes;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует базовые методы свойств отношений
 *
 * @property-read int    $type   Тип отношения
 * @property-read string $entity Имя сущности
 * @property      array  $links  Свойства связи
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
abstract class RelationModel extends PropertyModel {

    /**
     * Тип отношения
     * @var int
     */
    protected int $_type;

    /**
     * Имя сущности
     * @var string
     */
    protected string $_entity;

    /**
     * Свойства связи
     * @var array
     */
    protected array $_links;

    /**
     * Конструктор класса
     *
     * @param int    $relationType Тип отношения
     * @param string $entity       Имя сущности
     * @param array  $links        Свойства связи
     */
    public function __construct(int $relationType, string $entity, array $links) {
        $fieldName = $entity . '[' . implode(',', $links) . ']';
        parent::__construct(DataTypes::DT_OBJECT, 0, 0, $fieldName, false, AccessTypes::AC_RELATION);
        $this->_type   = $relationType;
        $this->_entity = $entity;
        $this->_links  = $links;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue() {
        return null;
    }

    /**
     * Возвращает тип отношения
     *
     * @return int
     */
    public function getType(): int {
        return $this->_type;
    }

    /**
     * Возвращает имя сущности
     *
     * @return string
     */
    public function getEntity(): string {
        return $this->_entity;
    }

    /**
     * Возвращает свойства связи
     *
     * @return array
     */
    public function getLinks(): array {
        return $this->_links;
    }

    /**
     * Задает свойства связи
     *
     * @param array $links Массив свойств связи
     *
     * @return void
     */
    public function setLinks(array $links): void {
        $this->_links = $links;
    }
}

<?php

/**
 * JoinModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;

/**
 * Описывает свойства модели данных соединений
 *
 * @property-read int    $type         Тип соединения
 * @property-read string $joinEntity   Имя связываемой сущности
 * @property-read string $joinAlias    Псевдоним связываемой сущности
 * @property-read string $joinProperty Имя свойства связываемой сущности
 * @property-read string $linkAlias    Имя внешней сущности
 * @property-read string $linkProperty Имя свойства внешней сущности
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 */
class JoinModel extends DataModel {

    /**
     * Тип соединения
     * @var int
     */
    protected $_type = TokenTypes::KW_LEFT;

    /**
     * Имя связываемой сущности
     * @var string
     */
    protected $_joinEntity = '';

    /**
     * Псевдоним связываемой сущности
     * @var string
     */
    protected $_joinAlias = '';

    /**
     * Имя свойства связываемой сущности
     * @var string
     */
    protected $_joinProperty = '';

    /**
     * Псевдоним внешней сущности
     * @var string
     */
    protected $_linkAlias = '';

    /**
     * Свойство внешней сущности
     * @var string
     */
    protected $_linkProperty = '';

    /**
     * Конструктор класса
     *
     * @param int    $type         Тип соединения
     * @param string $joinEntity   Имя связываемой сущности
     * @param string $joinAlias    Псевдоним связываемой сущности
     * @param string $joinProperty Имя свойства связываемой сущности
     * @param string $linkAlias    Имя внешней сущности
     * @param string $linkProperty Имя свойства внешней сущности
     */
    public function __construct(int $type, string $joinEntity, string $joinAlias, string $joinProperty, string $linkAlias, string $linkProperty) {
        parent::__construct();
        $this->_type         = $type;
        $this->_joinEntity   = $joinEntity;
        $this->_joinAlias    = $joinAlias;
        $this->_joinProperty = $joinProperty;
        $this->_linkAlias    = $linkAlias;
        $this->_linkProperty = $linkProperty;
    }

    /**
     * Возвращает тип связывания
     *
     * @return int
     */
    public function getType(): int {
        return $this->_type;
    }

    /**
     * Созвращает имя связываемой сущности
     *
     * @return string
     */
    public function getJoinEntity(): string {
        return $this->_joinEntity;
    }

    /**
     * Возвращает псевдоним связываемой сущности
     *
     * @return string
     */
    public function getJoinAlias(): string {
        return $this->_joinAlias;
    }

    /**
     * Возвращает имя свойства связываемой сущности
     *
     * @return string
     */
    public function getJoinProperty(): string {
        return $this->_joinProperty;
    }

    /**
     * Возвращает псевдоним сущности связи
     *
     * @return string
     */
    public function getLinkAlias(): string {
        return $this->_linkAlias;
    }

    /**
     * Возвращает имя свойства сущности связи
     *
     * @return string
     */
    public function getLinkProperty(): string {
        return $this->_linkProperty;
    }
}

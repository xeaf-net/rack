<?php

/**
 * OrderParser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Parsers;

use XEAF\Rack\ORM\Models\Parsers\OrderModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\QueryParser;

/**
 * Реализует методы разбора фазы ORDER
 *
 * @package XEAF\Rack\ORM\Utils\Parsers
 */
class OrderParser extends Parser {

    /**
     * Псевдоним
     * @var string
     */
    private $_alias = '';

    /**
     * Имя свойства
     * @var string
     */
    private $_property = '';

    /**
     * Направление сортировки
     * @var int
     */
    private $_direction = TokenTypes::KW_ASCENDING;

    /**
     * Матрица состояний
     */
    protected const STATES = [
        '00' => [TokenTypes::KW_ORDER => '01'],
        '01' => [TokenTypes::KW_BY => '02'],
        '02' => [TokenTypes::ID_UNKNOWN => '03'],
        '03' => [TokenTypes::SP_DOT => '04'],
        '04' => [TokenTypes::ID_UNKNOWN => '05'],
        '05' => [
            TokenTypes::KW_ASCENDING  => '06',
            TokenTypes::KW_DESCENDING => '06',
            TokenTypes::SP_COMMA      => '02',
            TokenTypes::ID_STOP       => 'ST'
        ],
        '06' => [
            TokenTypes::SP_COMMA => '02',
            TokenTypes::ID_STOP  => 'ST'
        ]
    ];

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\ORM\Models\QueryModel $queryModel Модель запроса
     */
    public function __construct(QueryModel $queryModel) {
        parent::__construct($queryModel, self::STATES);
    }

    /**
     * @inheritDoc
     */
    protected function move(string $from, string $dest): void {
        switch ($from . ':' . $dest) {
            case '02:03':
                $this->_alias = $this->_current->getText();
                break;
            case '04:05':
                $this->_direction = TokenTypes::KW_ASCENDING;
                $this->_property  = $this->_current->getText();
                break;
            case '05:02':
            case '05:06':
            case '05:ST':
                if ($this->_current->getType() == TokenTypes::KW_DESCENDING) {
                    $this->_direction = TokenTypes::KW_DESCENDING;
                } else {
                    $this->_direction = TokenTypes::KW_ASCENDING;
                }
                $orderModel = new OrderModel($this->_alias, $this->_property, $this->_direction);
                $this->_queryModel->addOrderModel($orderModel);
                if ($this->_current->getType() == TokenTypes::ID_STOP) {
                    $this->_phase = QueryParser::END_PHASE;
                }
                break;
        }
    }
}

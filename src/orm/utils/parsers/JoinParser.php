<?php

/**
 * JoinParser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Parsers;

use XEAF\Rack\ORM\Models\Parsers\JoinModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\QueryParser;

/**
 * Реализует методы разбора фазы JOIN
 *
 * @package XEAF\Rack\ORM\Utils\Parsers
 */
class JoinParser extends Parser {

    /**
     * Тип объединения
     * @var int
     */
    private $_join = TokenTypes::KW_LEFT;

    /**
     * Сушность
     * @var string
     */
    private $_entity = '';

    /**
     * Псевдоним
     * @var string
     */
    private $_alias = '';

    /**
     * Псевдоним сущности слева
     * @var string
     */
    private $_leftAlias = '';

    /**
     * Свойство сущности слева
     * @var string
     */
    private $_leftProperty = '';

    /**
     * Псевдоним сущности справа
     * @var string
     */
    private $_rightAlias = '';

    /**
     * Свойство сущности справа
     * @var string
     */
    private $_rightProperty = '';

    /**
     * Матрица состояний
     */
    protected const STATES = [
        '00' => [
            TokenTypes::KW_LEFT  => '01',
            TokenTypes::KW_RIGHT => '01',
            TokenTypes::KW_INNER => '01',
            TokenTypes::KW_OUTER => '01',
        ],
        '01' => [TokenTypes::KW_JOIN => '02'],
        '02' => [TokenTypes::ID_UNKNOWN => '03'],
        '03' => [TokenTypes::ID_UNKNOWN => '04'],
        '04' => [TokenTypes::KW_ON => '05'],
        '05' => [TokenTypes::ID_UNKNOWN => '06'],
        '06' => [TokenTypes::SP_DOT => '07'],
        '07' => [TokenTypes::ID_UNKNOWN => '08'],
        '08' => [TokenTypes::OP_EQ => '09'],
        '09' => [TokenTypes::ID_UNKNOWN => '10'],
        '10' => [TokenTypes::SP_DOT => '11'],
        '11' => [TokenTypes::ID_UNKNOWN => '12'],
        '12' => [
            TokenTypes::ID_STOP  => 'ST',
            TokenTypes::KW_LEFT  => '01',
            TokenTypes::KW_RIGHT => '01',
            TokenTypes::KW_INNER => '01',
            TokenTypes::KW_OUTER => '01',
            TokenTypes::KW_WHERE => 'ST',
            TokenTypes::KW_ORDER => 'ST'
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
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function move(string $from, string $dest): void {
        switch ($from . ':' . $dest) {
            case '00:01':
                $this->_join = $this->_current->getType();
                break;
            case '02:03':
                $this->_entity = $this->_current->getText();
                break;
            case '03:04':
                $this->_alias = $this->_current->getText();
                break;
            case '05:06':
                $this->_leftAlias = $this->_current->getText();
                break;
            case '07:08':
                $this->_leftProperty = $this->_current->getText();
                break;
            case '09:10':
                $this->_rightAlias = $this->_current->getText();
                break;
            case '11:12':
                $this->_rightProperty = $this->_current->getText();
                break;
            case '12:01':
                $joinModel   = $this->createJoinModel();
                $this->_join = $this->_current->getType();
                $this->_queryModel->addJoinModel($joinModel);
                break;
            case '12:ST':
                $joinModel = $this->createJoinModel();
                $this->_queryModel->addJoinModel($joinModel);
                switch ($this->_current->getType()) {
                    case TokenTypes::ID_STOP:
                        $this->_phase = QueryParser::END_PHASE;
                        break;
                    case  TokenTypes::KW_WHERE:
                        $this->_phase = QueryParser::WHERE_PHASE;
                        break;
                    case  TokenTypes::KW_ORDER:
                        $this->_phase = QueryParser::ORDER_PHASE;
                        break;
                }
                break;
        }
    }

    /**
     * Создает модель данных соединенния
     *
     * @return \XEAF\Rack\ORM\Models\Parsers\JoinModel
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    private function createJoinModel(): JoinModel {
        if ($this->_leftAlias != $this->_alias && $this->_rightAlias != $this->_alias) {
            throw EntityException::invalidJoinAlias($this->_alias);
        }
        if ($this->_leftAlias == $this->_alias) {
            $result = new JoinModel($this->_join, $this->_entity, $this->_leftAlias, $this->_leftProperty, $this->_rightAlias, $this->_rightProperty);
        } else {
            $result = new JoinModel($this->_join, $this->_entity, $this->_rightAlias, $this->_rightProperty, $this->_leftAlias, $this->_leftProperty);
        }
        return $result;
    }
}

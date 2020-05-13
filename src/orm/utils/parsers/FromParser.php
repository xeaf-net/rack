<?php declare(strict_types = 1);

/**
 * FromParser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Parsers;

use XEAF\Rack\ORM\Models\Parsers\FromModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\QueryParser;

/**
 * Реализует методы разбора фазы FROM
 *
 * @package XEAF\Rack\ORM\Utils\Parsers
 */
class FromParser extends Parser {

    /**
     * Матрица состояний
     */
    protected const STATES = [
        '00' => [TokenTypes::KW_FROM => '01'],
        '01' => [TokenTypes::ID_UNKNOWN => '02'],
        '02' => [
            TokenTypes::ID_UNKNOWN => '03',
            TokenTypes::ID_STOP    => 'ST',
            TokenTypes::SP_COMMA   => '01',
            TokenTypes::KW_LEFT    => 'ST',
            TokenTypes::KW_RIGHT   => 'ST',
            TokenTypes::KW_INNER   => 'ST',
            TokenTypes::KW_OUTER   => 'ST',
            TokenTypes::KW_WHERE   => 'ST',
            TokenTypes::KW_ORDER   => 'ST',
            TokenTypes::KW_FILTER  => 'ST'
        ],
        '03' => [
            TokenTypes::ID_STOP   => 'ST',
            TokenTypes::SP_COMMA  => '01',
            TokenTypes::KW_LEFT   => 'ST',
            TokenTypes::KW_RIGHT  => 'ST',
            TokenTypes::KW_INNER  => 'ST',
            TokenTypes::KW_OUTER  => 'ST',
            TokenTypes::KW_WHERE  => 'ST',
            TokenTypes::KW_ORDER  => 'ST',
            TokenTypes::KW_FILTER => 'ST'
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
            case '02:01':
                $fromModel = new FromModel($this->_previous->getText(), $this->_previous->getText());
                $this->_queryModel->addFromModel($fromModel);
                break;
            case '02:03':
                $fromModel = new FromModel($this->_previous->getText(), $this->_current->getText());
                $this->_queryModel->addFromModel($fromModel);
                break;
            case '02:ST':
            case '03:ST':
                if ($from == '02') { // Теперь ALIAS не обязателен!
                    $fromModel = new FromModel($this->_previous->getText(), $this->_previous->getText());
                    $this->_queryModel->addFromModel($fromModel);
                }
                print " current: " . $this->_current->getText() . ' ';
                switch ($this->_current->getType()) {
                    case TokenTypes::KW_LEFT:
                    case TokenTypes::KW_RIGHT:
                    case TokenTypes::KW_INNER:
                    case TokenTypes::KW_OUTER:
                        $this->_phase = QueryParser::JOIN_PHASE;
                        break;
                    case TokenTypes::KW_FILTER:
                        $this->_phase = QueryParser::FILTER_PHASE;
                        break;
                    case  TokenTypes::KW_WHERE:
                        $this->_phase = QueryParser::WHERE_PHASE;
                        break;
                    case  TokenTypes::KW_ORDER:
                        $this->_phase = QueryParser::ORDER_PHASE;
                        break;
                    case TokenTypes::ID_STOP:
                        $this->_phase = QueryParser::END_PHASE;
                        break;
                }
                break;
        }
    }
}

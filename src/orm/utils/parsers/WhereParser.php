<?php declare(strict_types = 1);

/**
 * WhereParser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Parsers;

use XEAF\Rack\ORM\Models\Parsers\WhereModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\QueryParser;

/**
 * Реализует методы разбора фазы WHERE
 *
 * @package XEAF\Rack\ORM\Utils\Parsers
 */
class WhereParser extends Parser {

    /**
     * Матрица состояний
     */
    protected const STATES = [
        '00' => [
            TokenTypes::KW_WHERE    => '01',
            TokenTypes::ID_UNKNOWN  => '01',
            TokenTypes::ID_CONSTANT => '01',
            TokenTypes::ID_NULL     => '01',
            TokenTypes::ID_FALSE    => '01',
            TokenTypes::ID_TRUE     => '01',
            TokenTypes::OP_OPEN_BR  => '01',
            TokenTypes::OP_CLOSE_BR => '01',
            TokenTypes::OP_PLUS     => '01',
            TokenTypes::OP_MINUS    => '01',
            TokenTypes::OP_NOT      => '01'
        ],
        '01' => [
            TokenTypes::ID_STOP     => 'ST',
            TokenTypes::KW_FILTER   => 'ST',
            TokenTypes::KW_ORDER    => 'ST',
            TokenTypes::ID_UNKNOWN  => '01',
            TokenTypes::ID_CONSTANT => '01',
            TokenTypes::ID_NULL     => '01',
            TokenTypes::ID_FALSE    => '01',
            TokenTypes::ID_TRUE     => '01',
            TokenTypes::OP_OPEN_BR  => '01',
            TokenTypes::OP_CLOSE_BR => '01',
            TokenTypes::OP_PLUS     => '01',
            TokenTypes::OP_MINUS    => '01',
            TokenTypes::OP_MULTIPLY => '01',
            TokenTypes::OP_DIVIDE   => '01',
            TokenTypes::OP_AND      => '01',
            TokenTypes::OP_OR       => '01',
            TokenTypes::OP_NOT      => '01',
            TokenTypes::OP_EQ       => '01',
            TokenTypes::OP_NE       => '01',
            TokenTypes::OP_GT       => '01',
            TokenTypes::OP_GE       => '01',
            TokenTypes::OP_LT       => '01',
            TokenTypes::OP_LE       => '01',
            TokenTypes::OP_LIKE     => '01',
            TokenTypes::SP_DOT      => '01',
            TokenTypes::SP_COLON    => '01',
        ]
    ];

    /**
     * Модель данных
     * @var \XEAF\Rack\ORM\Models\Parsers\WhereModel
     */
    private WhereModel $_whereModel;

    /**
     * Счетчик скобок
     * @var int
     */
    private int $_brackets = 0;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\ORM\Models\QueryModel $queryModel Модель запроса
     */
    public function __construct(QueryModel $queryModel) {
        parent::__construct($queryModel, self::STATES);
        $this->_whereModel = new WhereModel();
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function move(string $from, string $dest): void {
        switch ($from . ':' . $dest) {
            case '00:01':
            case '01:01':
                switch ($this->_current->getType()) {
                    case TokenTypes::SP_DOT:
                        $this->_previous->setType(TokenTypes::ID_ALIAS);
                        break;
                    case TokenTypes::ID_UNKNOWN:
                        if ($this->_previous->getType() == TokenTypes::SP_DOT) {
                            $this->_current->setType(TokenTypes::ID_PROPERTY);
                        } elseif ($this->_previous->getType() == TokenTypes::SP_COLON) {
                            $this->_current->setType(TokenTypes::ID_PARAMETER);
                        }
                        break;
                    case TokenTypes::OP_OPEN_BR:
                        $this->_brackets = $this->_brackets + 1;
                        break;
                    case TokenTypes::OP_CLOSE_BR:
                        $this->_brackets = $this->_brackets - 1;
                        if ($this->_brackets < 0) {
                            throw EntityException::unpairedBracket($this->_current->getPosition());
                        }
                        break;
                }
                if ($this->_current->getType() != TokenTypes::KW_WHERE) {
                    $this->_whereModel->addToken($this->_current);
                }
                break;
            case '01:ST':
                if ($this->_brackets > 0) {
                    throw EntityException::unpairedBracket($this->_current->getPosition());
                }
                $this->_queryModel->addWhereModel($this->_whereModel);
                switch ($this->_current->getType()) {
                    case  TokenTypes::KW_FILTER:
                        $this->_phase = QueryParser::FILTER_PHASE;
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

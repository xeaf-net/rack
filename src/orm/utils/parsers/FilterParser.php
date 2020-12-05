<?php declare(strict_types = 1);

/**
 * FilterParser.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Parsers;

use XEAF\Rack\ORM\Models\Parsers\FilterModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\QueryParser;

/**
 * Реализует методы разбора фазы FILTER
 *
 * @package XEAF\Rack\ORM\Utils\Parsers
 */
class FilterParser extends Parser {

    /**
     * Псевдоним
     * @var string
     */
    private string $_alias = '';

    /**
     * Имя свойства
     * @var string
     */
    private string $_property = '';

    /**
     * Матрица состояний
     */
    protected const STATES = [
        '00' => [TokenTypes::KW_FILTER => '01'],
        '01' => [TokenTypes::KW_BY => '02'],
        '02' => [TokenTypes::ID_UNKNOWN => '03'],
        '03' => [TokenTypes::SP_DOT => '04'],
        '04' => [TokenTypes::ID_UNKNOWN => '05'],
        '05' => [
            TokenTypes::SP_COMMA => '02',
            TokenTypes::KW_ORDER => 'ST',
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
                $this->_property = $this->_current->getText();
                break;
            case '05:02':
            case '05:ST':
                $filterModel = new FilterModel($this->_alias, $this->_property);
                $this->_queryModel->addFilterModel($filterModel);
                switch ($this->_current->getType()) {
                    case TokenTypes::ID_STOP:
                        $this->_phase = QueryParser::END_PHASE;
                        break;
                    case  TokenTypes::KW_ORDER:
                        $this->_phase = QueryParser::ORDER_PHASE;
                        break;
                }
                break;
        }
    }
}

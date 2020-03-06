<?php declare(strict_types = 1);

/**
 * AliasParser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Parsers;

use XEAF\Rack\ORM\Models\Parsers\AliasModel;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;
use XEAF\Rack\ORM\Utils\QueryParser;

/**
 * Реализует методы разбора фазы ALIASES
 *
 * @package XEAF\Rack\ORM\Utils\Parsers
 */
class AliasParser extends Parser {

    /**
     * Матрица состояний
     */
    protected const STATES = [
        '00' => [
            TokenTypes::ID_UNKNOWN => '01',
        ],
        '01' => [
            TokenTypes::SP_COMMA => '00',
            TokenTypes::KW_FROM  => 'ST'
        ],
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
            case '00:01':
                $alias = new AliasModel($this->_current->getText());
                $this->_queryModel->addAliasModel($alias);
                break;
            case '00:ST':
            case '01:ST':
                $this->_phase = QueryParser::FROM_PHASE;
                break;
        }
    }
}

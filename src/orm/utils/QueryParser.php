<?php

/**
 * QueryParser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\ORM\Interfaces\IQueryParser;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Utils\Parsers\AliasParser;
use XEAF\Rack\ORM\Utils\Parsers\FilterParser;
use XEAF\Rack\ORM\Utils\Parsers\FromParser;
use XEAF\Rack\ORM\Utils\Parsers\JoinParser;
use XEAF\Rack\ORM\Utils\Parsers\OrderParser;
use XEAF\Rack\ORM\Utils\Parsers\WhereParser;

/**
 * Реализует методы парзера языка запросов XQL
 *
 * @package XEAF\Rack\ORM\Utils
 */
class QueryParser implements IQueryParser {

    /**
     * Фаза разбора псевдонимов
     */
    public const ALIAS_PHASE = 0;

    /**
     * Фаза разбора конструкции FROM
     */
    public const FROM_PHASE = 1;

    /**
     * Фаза разбора конструкции JOIN
     */
    public const JOIN_PHASE = 2;

    /**
     * Фаза разбора конструкции WHERE
     */
    public const WHERE_PHASE = 3;

    /**
     * Фаза разбора конструкции FILTER
     */
    public const FILTER_PHASE = 4;

    /**
     * Фаза разбора конструкции ORDER
     */
    public const ORDER_PHASE = 5;

    /**
     * Фаза завершения обработки
     */
    public const END_PHASE = 6;

    /**
     * Исходный код XQL запроса
     * @var string
     */
    private $_xql = null;

    /**
     * Номер фазы разбора (alias, from, join, where, order)
     * @var int
     */
    private $_phase = self::ALIAS_PHASE;

    /**
     * Список лексем
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    private $_tokens = null;

    /**
     * Модель запроса
     * @var \XEAF\Rack\ORM\Models\QueryModel
     */
    private $_queryModel = null;

    /**
     * @inheritDoc
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function buildQueryModel(string $xql): QueryModel {
        if ($xql != $this->_xql) {
            $this->_xql        = $xql;
            $this->_phase      = self::ALIAS_PHASE;
            $this->_queryModel = new QueryModel();
            $this->processXQL();
        }
        return $this->_queryModel;
    }

    /**
     * Обрабатывает текст XQL запроса
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function processXQL(): void {
        $tokenizer     = Tokenizer::getInstance();
        $this->_tokens = $tokenizer->tokenize(' ' . $this->_xql);
        $parser        = null;
        while ($this->_tokens->count() > 1 && $this->_phase != self::END_PHASE) {
            switch ($this->_phase) {
                case self::ALIAS_PHASE:
                    $parser = new AliasParser($this->_queryModel);
                    break;
                case self::FROM_PHASE:
                    $parser = new FromParser($this->_queryModel);
                    break;
                case self::JOIN_PHASE:
                    $parser = new JoinParser($this->_queryModel);
                    break;
                case self::WHERE_PHASE:
                    $parser = new WhereParser($this->_queryModel);
                    break;
                case self::FILTER_PHASE:
                    $parser = new FilterParser($this->_queryModel);
                    break;
                case self::ORDER_PHASE:
                    $parser = new OrderParser($this->_queryModel);
                    break;
                default:
                    $this->_phase = self::END_PHASE;
                    break;
            }
            if ($this->_phase != self::END_PHASE) {
                $this->_phase = $parser->parse($this->_tokens);
            }
        }
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\ORM\Interfaces\IQueryParser
     */
    public static function getInstance(): IQueryParser {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IQueryParser);
        return $result;
    }
}

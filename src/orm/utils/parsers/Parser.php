<?php declare(strict_types = 1);

/**
 * Parser.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Utils\Parsers;

use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\API\Utils\Exceptions\CollectionException;
use XEAF\Rack\ORM\Models\QueryModel;
use XEAF\Rack\ORM\Models\TokenModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\QueryParser;

/**
 * Реализует базовые методы парзера конструкций XQL
 *
 * @package XEAF\Rack\ORM\Utils\Parsers
 */
abstract class Parser {

    /**
     * Начальное состояние
     */
    public const START = '00';

    /**
     * Конечное состояние
     */
    public const STOP = 'ST';

    /**
     * Состояние ошибки
     */
    public const ERROR = 'ER';

    /**
     * Код фазы
     * @var int
     */
    protected int $_phase = QueryParser::ALIAS_PHASE;

    /**
     * Модель запроса
     * @var \XEAF\Rack\ORM\Models\QueryModel
     */
    protected QueryModel $_queryModel;

    /**
     * Матрица состояний
     * @var array
     */
    protected array $_matrix = [];

    /**
     * Код текущего состояния
     * @var string
     */
    protected string $_state = self::START;

    /**
     * Текущая разбираемая лексема
     * @var \XEAF\Rack\ORM\Models\TokenModel|null
     */
    protected ?TokenModel $_current = null;

    /**
     * Предыдучая разбираемая лексема
     * @var \XEAF\Rack\ORM\Models\TokenModel|null
     */
    protected ?TokenModel $_previous = null;

    /**
     * Конструктор класса
     *
     * @param \XEAF\Rack\ORM\Models\QueryModel $queryModel Модель запроса
     * @param array                            $matrix     Матрица состояний
     */
    public function __construct(QueryModel $queryModel, array $matrix) {
        $this->_queryModel = $queryModel;
        $this->_matrix     = $matrix;
    }

    /**
     * Разбирает выражение фазы Alias и возвращает код следующей фазы
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $tokens Список лексем
     *
     * @return int
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function parse(ICollection $tokens): int {
        $this->_current  = null;
        $this->_previous = null;
        while (!$tokens->isEmpty() && $this->_state != self::ERROR && $this->_state != self::STOP) {
            $this->_previous = $this->_current;
            try {
                $token = $tokens->pop();
                assert($token instanceof TokenModel || $token === null);
                $this->_current = $token;
            } catch (CollectionException $exception) {
                throw EntityException::internalError($exception);
            }
            $newState = $this->step($this->_current);
            $this->move($this->_state, $newState);
            $this->_state = $newState;
        }
        $tokens->unpop($this->_current);
        return $this->_phase;
    }

    /**
     * Выполняет действия очередного шага разбора
     *
     * @param \XEAF\Rack\ORM\Models\TokenModel $token Лексема
     *
     * @return string
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function step(TokenModel $token): string {
        $result = $this->_matrix[$this->_state][$token->getType()] ?? self::ERROR;
        if ($result == self::ERROR) {
            $position = $token->getPosition();
            if ($position != 0) {
                throw EntityException::syntaxError($token->getPosition());
            } else {
                throw EntityException::unexpectedExpressionEnd();
            }
        }
        return $result;
    }

    /**
     * Обрабатывает переход автомата из одного состояния в другое
     *
     * @param string $from Исходное состояние
     * @param string $dest Конечное состояние
     *
     * @return void
     */
    abstract protected function move(string $from, string $dest): void;
}

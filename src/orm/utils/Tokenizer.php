<?php declare(strict_types = 1);

/**
 * Tokenizer.php
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
use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\ORM\Interfaces\ITokenizer;
use XEAF\Rack\ORM\Models\TokenModel;
use XEAF\Rack\ORM\Utils\Exceptions\EntityException;
use XEAF\Rack\ORM\Utils\Lex\KeyWords;
use XEAF\Rack\ORM\Utils\Lex\TokenChars;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;

/**
 * Реализует методы разбора текста XQL запроса на лексемы
 *
 * @package XEAF\Rack\ORM\Utils
 */
class Tokenizer implements ITokenizer {

    /**
     * Список лексем
     * @var ICollection
     */
    private $_tokens = null;

    /**
     * Символы разобранного текста XQL запроса
     * @var array
     */
    private $_chars = [];

    /**
     * Текущая позиция в массиве символов
     * @var int
     */
    private $_charPos = 0;

    /**
     * Счетчик скобок
     * @var int
     */
    private $_brackets = 0;

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->_tokens = new Collection();
    }

    /**
     * Возвращает коллекцию моделей лексем
     *
     * @param string $xql Исходный текст XQL
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    public function tokenize(string $xql): ICollection {
        $this->prepareXQL($xql);
        $count = count($this->_chars);
        while ($this->_charPos < $count) {
            $ch = $this->_chars[$this->_charPos];
            if (in_array($ch, TokenChars::SPACE)) {
                self::skipEmptySpace();
            } elseif (in_array($ch, TokenChars::OPERATOR)) {
                self::readOperator();
            } elseif (($ch >= TokenChars::LA && $ch <= TokenChars::LZ) || ($ch >= TokenChars::UA && $ch <= TokenChars::UZ) || $ch == TokenChars::US) {
                self::readIdentifier();
            } elseif ($ch == TokenChars::SQ) {
                self::readStringConstant();
            } elseif ($ch >= TokenChars::D0 && $ch <= TokenChars::D9) {
                self::readNumericConstant();
            } elseif (in_array($ch, TokenChars::SEPARATOR)) {
                self::readSeparator();
            } elseif ($ch == TokenChars::OB || $ch == TokenChars::CB) {
                self::readBrackets();
            } elseif ($ch == TokenChars::STOP) {
                if ($this->_brackets != 0) {
                    throw EntityException::unpairedBracket($this->_charPos);
                }
                $token = new TokenModel(TokenTypes::ID_STOP, $ch, 0);
                $this->_tokens->push($token);
                return $this->_tokens;
            }
            $this->_charPos++;
        }
        return $this->_tokens;
    }

    /**
     * Подготавливает запрос и парзер к работе
     *
     * @param string $xql Текст XQL запроса
     *
     * @return void
     */
    protected function prepareXQL(string $xql): void {
        $this->_chars    = preg_split('//u', $xql, -1, PREG_SPLIT_NO_EMPTY);
        $this->_chars[]  = TokenChars::STOP;
        $this->_charPos  = 0;
        $this->_brackets = 0;
        $this->_tokens->clear();
    }

    /**
     * Пропускает пространство из пробельных символов
     *
     * @return void
     */
    protected function skipEmptySpace(): void {
        $ch = $this->_chars[$this->_charPos];
        while (in_array($ch, TokenChars::SPACE)) {
            $ch = $this->_chars[++$this->_charPos];
        }
        $this->_charPos--;
    }

    /**
     * Читает оператор
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function readOperator(): void {
        $text = '';
        $ch   = $this->_chars[$this->_charPos];
        $pos  = $this->_charPos;
        switch ($ch) {
            case TokenChars::PC:
                if ($this->_chars[$this->_charPos + 1] == TokenChars::PC) {
                    $text = TokenChars::PC . TokenChars::PC;
                    $this->_charPos++;
                }
                break;
            case TokenChars::GT:
                $text = $ch;
                if ($this->_chars[$this->_charPos + 1] == TokenChars::EQ) {
                    $text = TokenChars::GT . TokenChars::EQ;
                    $this->_charPos++;
                }
                break;
            case TokenChars::LT:
                $text = $ch;
                if ($this->_chars[$this->_charPos + 1] == '=') {
                    $text = TokenChars::LT . TokenChars::EQ;
                    $this->_charPos++;
                }
                break;
            case TokenChars::EX:
                $text = $ch;
                if ($this->_chars[$this->_charPos + 1] == TokenChars::EQ) {
                    $text = TokenChars::EX . TokenChars::EQ;
                    $this->_charPos++;
                }
                break;
            case TokenChars::EQ:
                if ($this->_chars[$this->_charPos + 1] == TokenChars::EQ) {
                    $text = TokenChars::EQ . TokenChars::EQ;
                    $this->_charPos++;
                } else {
                    throw EntityException::syntaxError($pos);
                }
                break;
            case TokenChars::PP:
                if ($this->_chars[$this->_charPos + 1] == TokenChars::PP) {
                    $text = TokenChars::PP . TokenChars::PP;
                    $this->_charPos++;
                } else {
                    throw EntityException::syntaxError($pos);
                }
                break;
            case TokenChars::AM:
                if ($this->_chars[$this->_charPos + 1] == TokenChars::AM) {
                    $text = TokenChars::AM . TokenChars::AM;
                    $this->_charPos++;
                } else {
                    throw EntityException::syntaxError($pos);
                }
                break;
        }
        if ($text != '') {
            $type  = TokenTypes::OPERATOR_CODES[$text] ?? TokenTypes::ID_UNKNOWN;
            $token = new TokenModel($type, $text, $pos);
            $this->_tokens->push($token);
        }
    }

    /**
     * Читает идентификатор
     *
     * @return void
     */
    protected function readIdentifier(): void {
        $text = '';
        $ch   = $this->_chars[$this->_charPos];
        $pos  = $this->_charPos;
        while (($ch >= TokenChars::LA && $ch <= TokenChars::LZ) || ($ch >= TokenChars::UA && $ch <= TokenChars::UZ) || ($ch >= TokenChars::D0 && $ch <= TokenChars::D9) || $ch == TokenChars::US) {
            $text .= $ch;
            $ch   = $this->_chars[++$this->_charPos];
        }
        $this->_charPos--;
        $lowerText = strtolower($text);
        if (in_array($lowerText, KeyWords::KEY_WORDS)) {
            $type = TokenTypes::KEY_WORD_CODES[$lowerText] ?? TokenTypes::ID_UNKNOWN;
            $text = $lowerText;
        } else {
            $type = TokenTypes::ID_UNKNOWN;
        }
        $token = new TokenModel($type, $text, $pos);
        $this->_tokens->push($token);
    }

    /**
     * Читает строковую константу
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function readStringConstant(): void {
        $text = TokenChars::SQ;
        $pos  = $this->_charPos;
        $ch   = $this->_chars[++$this->_charPos];
        if ($ch == TokenChars::SQ) {
            $text = "''"; // Пустая строка
        } else {
            while ($ch != TokenChars::CR && $ch != TokenChars::LF && $ch != TokenChars::SQ && $ch != TokenChars::STOP) {
                $ch   = $this->_chars[$this->_charPos++];
                $text .= $ch;
            }
            if ($ch != TokenChars::SQ) {
                throw EntityException::unclosedSingleQuote($pos);
            }
            $this->_charPos--;
        }
        $token = new TokenModel(TokenTypes::ID_CONSTANT, $text, $pos);
        $this->_tokens->push($token);
    }

    /**
     * Читает цифровую константу
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function readNumericConstant(): void {
        $text   = '';
        $ch     = $this->_chars[$this->_charPos];
        $pos    = $this->_charPos;
        $points = 0;
        while (($ch >= TokenChars::D0 && $ch <= TokenChars::D9) || $ch == TokenChars::PT) {
            if ($ch == TokenChars::PT) {
                $points++;
                if ($points > 1) {
                    throw EntityException::syntaxError($pos);
                }
            }
            $text .= $ch;
            $ch   = $this->_chars[++$this->_charPos];
        }
        $this->_charPos--;
        $token = new TokenModel(TokenTypes::ID_CONSTANT, $text, $pos);
        $this->_tokens->push($token);
    }

    /**
     * Читает разделитель
     *
     * @return void
     */
    protected function readSeparator(): void {
        $ch   = $this->_chars[$this->_charPos];
        $type = TokenTypes::ID_UNKNOWN;
        switch ($ch) {
            case TokenChars::PT:
                $type = TokenTypes::SP_DOT;
                break;
            case TokenChars::CM:
                $type = TokenTypes::SP_COMMA;
                break;
            case TokenChars::CL:
                $type = TokenTypes::SP_COLON;
                break;
        }
        $token = new TokenModel($type, $ch, $this->_charPos);
        $this->_tokens->push($token);
    }

    /**
     * Читает и проверяет скобки
     *
     * @return void
     * @throws \XEAF\Rack\ORM\Utils\Exceptions\EntityException
     */
    protected function readBrackets(): void {
        $ch = $this->_chars[$this->_charPos];
        switch ($ch) {
            case TokenChars::OB:
                $this->_brackets++;
                break;
            case TokenChars::CB:
                $this->_brackets--;
                if ($this->_brackets < 0) {
                    throw EntityException::unpairedBracket($this->_charPos);
                }
                break;
        }
        $type  = TokenTypes::OPERATOR_CODES[$ch] ?? TokenTypes::ID_UNKNOWN;
        $token = new TokenModel($type, $ch, $this->_charPos);
        $this->_tokens->push($token);
    }

    /**
     * Возвразает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\ORM\Interfaces\ITokenizer
     */
    public static function getInstance(): ITokenizer {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ITokenizer);
        return $result;
    }
}

<?php declare(strict_types = 1);

/**
 * TokenModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Utils\Lex\TokenTypes;

/**
 * Описывает свойства модели данных лексем
 *
 * @property      int    $type     Тип лексемы
 * @property-read string $text     Текст
 * @property-read int    $position Позиция в строке
 *
 * @package XEAF\Rack\ORM\Models
 */
class TokenModel extends DataModel {

    /**
     * Тип лексемы
     * @var int
     */
    protected int $_type = TokenTypes::ID_UNKNOWN;

    /**
     * Текст
     * @var string
     */
    protected string $_text = '';

    /**
     * Позиция в строке
     * @var int
     */
    protected int $_position = 0;

    /**
     * Конструктор класса
     *
     * @param int    $type     Тип лексемы
     * @param string $text     Текст
     * @param int    $position Позиция в строке
     */
    public function __construct(int $type, string $text, int $position) {
        parent::__construct();
        $this->_type     = $type;
        $this->_text     = $text;
        $this->_position = $position;
    }

    /**
     * Возвращает тип лексемы
     *
     * @return int
     */
    public function getType(): int {
        return $this->_type;
    }

    /**
     * Задает тип лексемы
     *
     * @param int $type Тип лексемы
     *
     * @return void
     */
    public function setType(int $type): void {
        $this->_type = $type;
    }

    /**
     * Возвращает текст лексемы
     *
     * @return string
     */
    public function getText(): string {
        return $this->_text;
    }

    /**
     * Возвращает позицию в строке
     *
     * @return int
     */
    public function getPosition(): int {
        return $this->_position;
    }
}

<?php

/**
 * WhereModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\Collection;
use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\API\Interfaces\ICollection;
use XEAF\Rack\ORM\Models\TokenModel;

/**
 * Описывает свойства модели данных условий отбора
 *
 * @property \XEAF\Rack\API\Interfaces\ICollection $tokens Набор лексем
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 */
class WhereModel extends DataModel {

    /**
     * Набор лексем
     * @var \XEAF\Rack\API\Interfaces\ICollection
     */
    protected $_tokens = null;

    /**
     * Конструктор класса
     */
    public function __construct() {
        parent::__construct();
        $this->_tokens = new Collection();
    }

    /**
     * Возвращает набор данных лексем
     *
     * @return \XEAF\Rack\API\Interfaces\ICollection
     */
    public function getTokens(): ICollection {
        return $this->_tokens;
    }

    /**
     * Задает набора данных лексем
     *
     * @param \XEAF\Rack\API\Interfaces\ICollection $tokens Набор данных лексем
     *
     * @return void
     */
    public function setTokens(ICollection $tokens): void {
        $this->_tokens = $tokens;
    }

    /**
     * ДОбавляет в коллекцию модель данных лексемы
     *
     * @param \XEAF\Rack\ORM\Models\TokenModel $tokenModel Модель данных лексемы
     *
     * @return void
     */
    public function addToken(TokenModel $tokenModel): void {
        $this->_tokens->push($tokenModel);
    }
}

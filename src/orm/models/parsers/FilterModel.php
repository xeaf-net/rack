<?php declare(strict_types = 1);

/**
 * FilterModel.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Описывает свойства модели данных параметров фильтрации
 *
 * @property      int    $type       Тип параметра
 * @property-read string $alias      Псевдоним
 * @property-read string $property   Имя свойства
 * @property-read string $parameter  Имя параметра фильтрации
 * @property-read int    $filterType Тип фильтрации
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 *
 * @since   1.0.2
 */
class FilterModel extends DataModel {

    /**
     * Фильтрация похожих значений
     */
    public const FILTER_LIKE = 1;

    /**
     * Фильтрация по диапазону
     */
    public const FILTER_BETWEEN = 2;

    /**
     * Имя параметра фильтрации по умолчанию
     */
    public const FILTER_PARAMETER = '_filter';

    /**
     * Префика параметра нижней границы значений
     */
    public const MIN_FILTER_PREFIX = 'min_';

    /**
     * Префикс параметры верхней границы значений
     */
    public const MAX_FILTER_PREFIX = 'max_';

    /**
     * Тип параметра
     * @var int
     */
    protected int $_type = DataTypes::DT_STRING;

    /**
     * Псевдоним
     * @var string
     */
    protected string $_alias = '';

    /**
     * Имя свойства
     * @var string
     */
    protected string $_property = '';

    /**
     * Имя параметра фильтрации
     * @var string
     */
    protected string $_parameter = '';

    /**
     * Тип фильтрации
     * @var int
     */
    protected int $_filterType = self::FILTER_LIKE;

    /**
     * Конструктор класса
     *
     * @param string $alias      Псевдоним
     * @param string $property   Имя свойства
     * @param string $parameter  Имя параметра фильтрации
     * @param int    $filterType Тип фильтрации
     */
    public function __construct(string $alias, string $property, string $parameter = self::FILTER_PARAMETER, int $filterType = self::FILTER_LIKE) {
        parent::__construct();
        $this->_type       = DataTypes::DT_STRING;
        $this->_alias      = $alias;
        $this->_property   = $property;
        $this->_parameter  = $parameter;
        $this->_filterType = $filterType;
    }

    /**
     * Возвращает тип параметра
     *
     * @return int
     */
    public function getType(): int {
        return $this->_type;
    }

    /**
     * Задает тип параметра
     *
     * @param int $type Тип параметра
     *
     * @return void
     */
    public function setType(int $type): void {
        $this->_type = $type;
    }

    /**
     * Возвращает псевдоним
     *
     * @return string
     */
    public function getAlias(): string {
        return $this->_alias;
    }

    /**
     * Возвращает имя свойства
     *
     * @return string
     */
    public function getProperty(): string {
        return $this->_property;
    }

    /**
     * Возвращает имя параметра фильтрации
     *
     * @return string
     */
    public function getParameter(): string {
        return $this->_parameter;
    }

    /**
     * Возвращает тип фильтрации
     *
     * @return int
     */
    public function getFilterType(): int {
        return $this->_filterType;
    }
}


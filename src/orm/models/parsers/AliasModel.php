<?php declare(strict_types = 1);

/**
 * AliasModel.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Parsers;

use XEAF\Rack\API\Core\DataModel;
use XEAF\Rack\ORM\Models\EntityModel;

/**
 * Описывает свойства модели данных псевдонимов
 *
 * @property-read string                                 $name  Наименование псевдонима
 * @property      \XEAF\Rack\ORM\Models\EntityModel|null $model Модель сущности
 *
 * @package XEAF\Rack\ORM\Models\Parsers
 */
class AliasModel extends DataModel {

    /**
     * Наименование псевдонима
     * @var string
     */
    protected $_name = '';

    /**
     * Модель сущности
     * @var \XEAF\Rack\ORM\Models\EntityModel|null
     */
    protected $_model = null;

    /**
     * Конструктор класса
     *
     * @param string                                 $name  Наименование псевдонима
     * @param \XEAF\Rack\ORM\Models\EntityModel|null $model Модель сущности
     */
    public function __construct(string $name, EntityModel $model = null) {
        parent::__construct();
        $this->_name  = $name;
        $this->_model = $model;
    }

    /**
     * Возвращает наименование псевдонима
     *
     * @return string
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Возвращает модель сущности
     *
     * @return \XEAF\Rack\ORM\Models\EntityModel|null
     */
    public function getModel(): ?EntityModel {
        return $this->_model;
    }

    /**
     * Задает модель сущности
     *
     * @param \XEAF\Rack\ORM\Models\EntityModel|null $model Модель сущности
     *
     * @return void
     */
    public function setModel(?EntityModel $model): void {
        $this->_model = $model;
    }
}

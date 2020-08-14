<?php declare(strict_types = 1);

/**
 * OneToManyProperty.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

use XEAF\Rack\ORM\Utils\Lex\DataTypes;

/**
 * Реализует методы свойства типа Один ко многим
 *
 * @package  XEAF\Rack\ORM\Models\Properties
 */
class OneToManyProperty extends ResolvedModel {

    /**
     * Конструктор класса
     *
     * @param string $entityName Имя сущности
     * @param array  $keys       Массив свойств внешнего ключа
     */
    public function __construct(string $entityName, array $keys) {
        parent::__construct(DataTypes::DT_ONE_TO_MANY, $entityName, $keys);
    }
}

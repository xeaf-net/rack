<?php

/**
 * StatusProperty.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\ORM\Models\Properties;

/**
 * Реализует методы свойства типа состояния записи
 *
 * @package XEAF\Rack\ORM\Models\Properties
 */
class StatusProperty extends EnumProperty {

    /**
     * Сущность активна
     */
    public const ACTIVE = 'ACTIVE';

    /**
     * Сущность архивирована
     */
    public const ARCHIVED = 'ARCHIVED';

    /**
     * Действие сущности приостановлена
     */
    public const STOPPED = 'STOPPED';

    /**
     * Сущность помечена на удаление
     */
    public const DELETED = 'DELETED';

    /**
     * Список возможных состояний сущностей
     */
    public const STATUSES = [
        self::ACTIVE,
        self::ARCHIVED,
        self::STOPPED,
        self::DELETED
    ];

    /**
     * Конструктор класса
     *
     * @param string $fieldName Имя поля базы данных
     * @param bool   $readOnly  Признак поля только для чтения
     *
     */
    public function __construct(string $fieldName, bool $readOnly = false) {
        parent::__construct($fieldName, self::STATUSES, $readOnly);
    }
}

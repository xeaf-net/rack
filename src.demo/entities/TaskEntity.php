<?php declare(strict_types = 1);

/**
 * TaskEntity.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\Demo\Entities;

use XEAF\Rack\ORM\Core\Entity;
use XEAF\Rack\ORM\Models\EntityModel;

/**
 * Описывает поля сущности Задача
 *
 * @property string $id        Уникальный идентификатор
 * @property string $projectId Идентификатор проекта
 * @property string $status    Состояние
 * @property string $title     Наименование
 * @property string $comment   Комментарий
 *
 * @package XEAF\Rack\Demo\Entities
 */
class TaskEntity extends Entity {

    /**
     * Состояние активной задачи
     */
    public const ACTIVE = 'ACTIVE';

    /**
     * Состояние завершенной задачи
     */
    public const COMPLETE = 'COMPLETE';

    /**
     * Идентификаторы состояний
     */
    private const STATUSES = [
        self::ACTIVE,
        self::COMPLETE
    ];

    /**
     * @inheritDoc
     */
    protected function createEntityModel(): EntityModel {
        return new EntityModel('tasks', [
            'id'        => self::uuid('task_id', true),
            'projectId' => self::uuid('project_id'),
            'status'    => self::enum('task_status', self::STATUSES),
            'title'     => self::string('task_title', 64),
            'comment'   => self::text('task_comment'),
            'project'   => self::oneToMany('projects', ['projectId'])
        ]);
    }
}

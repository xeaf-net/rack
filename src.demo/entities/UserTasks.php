<?php declare(strict_types = 1);

/**
 * UserTasks.php
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
 * Описывает поля сущности задачи пользователя
 *
 * @property string                                   $userId Идентификатор пользователя
 * @property string                                   $taskId Идентификатор задачи
 *
 * @property-read \XEAF\Rack\Demo\Entities\UserEntity $user   Пользователь
 * @property-read \XEAF\Rack\Demo\Entities\TaskEntity $task   Задача
 *
 * @package XEAF\Rack\Demo\Entities
 */
class UserTasks extends Entity {

    /**
     * @inheritDoc
     */
    protected function createEntityModel(): EntityModel {
        return new EntityModel('user_tasks', [
            'userId' => self::uuid('user_id', true),
            'taskId' => self::uuid('task_id', true),
            'user'   => self::manyToOne('users', ['userId']),
            'task'   => self::manyToOne('tasks', ['taskId'])
        ]);
    }
}

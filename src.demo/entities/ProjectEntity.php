<?php declare(strict_types = 1);

/**
 * ProjectEntity.php
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
 * Описывает поля сущности Проект
 *
 * @property string                                          $id     Уникальный идентификатор
 * @property string                                          $userId Идентификатор пользователя
 * @property string                                          $title  Наименование
 *
 * @property-read \XEAF\Rack\Demo\Entities\UserEntity|null   $user   Пользователь
 * @property-read \XEAF\Rack\API\Interfaces\ICollection|null $tasks  Задачи
 *
 * @package XEAF\Rack\Demo\Entities
 */
class ProjectEntity extends Entity {

    /**
     * @inheritDoc
     */
    protected function createEntityModel(): EntityModel {
        return new EntityModel('projects', [
            'id'     => self::uuid('project_id', true),
            'userId' => self::uuid('user_id'),
            'title'  => self::string('project_title', 64),
            'user'   => self::manyToOne('users', ['userId']),
            'tasks'  => self::oneToMany('tasks', ['projectId'])
        ]);
    }
}

<?php

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
 * @property string $id    Уникальный идентификатор
 * @property string $title Наименование
 *
 * @package XEAF\Rack\Demo\Entities
 */
class ProjectEntity extends Entity {

    /**
     * @inheritDoc
     */
    protected function createEntityModel(): EntityModel {
        return new EntityModel('projects', [
            'id'    => self::uuid('project_id', true),
            'title' => self::string('project_title', 64)
        ]);
    }
}

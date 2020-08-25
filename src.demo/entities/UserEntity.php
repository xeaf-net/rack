<?php declare(strict_types = 1);

/**
 * UserEntity.php
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
 * Содержит поля сущности Пользователь
 *
 * @property string $id       Уникальный идентификатор
 * @property string $email    Адрес электронной почты
 * @property string $fullName Полное имя пользователя
 *
 * @package XEAF\Rack\Demo\Entities
 */
class UserEntity extends Entity {

    /**
     * @inheritDoc
     */
    protected function createEntityModel(): EntityModel {
        return new EntityModel('users', [
            'id'       => self::uuid('user_id', true),
            'email'    => self::string('user_email', 127),
            'fullName' => self::string('user_full_name', 64)
        ]);
    }
}

-- ---------------------------------------------------------------------------------------------------------------------
-- Удаление устаревших версий таблиц
-- ---------------------------------------------------------------------------------------------------------------------
drop table if exists rack_migrations;
drop table if exists rack_storage;

-- ---------------------------------------------------------------------------------------------------------------------
-- Информация о миграциях
-- ---------------------------------------------------------------------------------------------------------------------
create table rack_migrations (

    migration_product   varchar(120)                                    not null    comment 'Идентификатор продукта'   ,
    migration_version   varchar(20)             default '0.0.0'         not null    comment 'Номер версии'             ,
    migration_timestamp timestamp               default now()           not null    comment 'Дата и время миграции'    ,
    migration_comment   text                                                        comment 'Комментарий'              ,

    constraint pk_rack_migrations primary key (migration_product, migration_version)

) engine='InnoDb' comment='Информация о миграциях';

-- Инициализация -------------------------------------------------------------------------------------------------------
insert into rack_migrations values ('rack-net/rack', '1.0.0', now(), '');

-- ---------------------------------------------------------------------------------------------------------------------
-- Хранилище данных
-- ---------------------------------------------------------------------------------------------------------------------
create table rack_storage (

    storage_name        varchar(120)                                    not null    comment 'Имя хранилища'            ,
    storage_key         varchar(120)                                    not null    comment 'Ключ'                     ,
    storage_value       text                                                        comment 'Значение'                 ,
    storage_validity    timestamp                                       not null    comment 'Строк окончания действия' ,

    constraint pk_rack_storage primary key (storage_name, storage_key)

) engine='InnoDb' comment='Хранилище данных';


--
-- Удаление устаревших версий таблиц
--
drop table if exists migrations;
drop table if exists storage;

-- ----------------------------------------------------------------------------------
-- Информация о миграциях
-- ----------------------------------------------------------------------------------
create table migrations (

    migration_product   varchar(120)                                    not null    ,
    migration_version   varchar(20)         default '0.0.0'             not null    ,
    migration_timestamp timestamp           default now()               not null    ,
    migration_comment   text                                                        ,

    constraint pk_migrations primary key (migration_product, migration_version)
);

-- Комментарии ----------------------------------------------------------------------
comment on table  migrations                     is 'Информация о миграциях';
comment on column migrations.migration_product   is 'Идентификатор продукта';
comment on column migrations.migration_version   is 'Номер версии';
comment on column migrations.migration_timestamp is 'Дата и время миграции';
comment on column migrations.migration_comment   is 'Комментарий';

-- Инициализация --------------------------------------------------------------------
insert into migrations values ('xeaf-net/rack', '1.0.0', now(), '');

-- ----------------------------------------------------------------------------------
-- Хранилище данных
-- ----------------------------------------------------------------------------------
create table storage (

    storage_name        varchar(120)                                    not null    ,
    storage_key         varchar(120)                                    not null    ,
    storage_value       text                                                        ,
    storage_validity    timestamp                                       not null    ,

    constraint pk_storage primary key (storage_name, storage_key)
);

-- Комментарии ----------------------------------------------------------------------
comment on table  storage                  is 'Хранилище данных';
comment on column storage.storage_name     is 'Имя хранилища';
comment on column storage.storage_key      is 'Ключ';
comment on column storage.storage_value    is 'Значение';
comment on column storage.storage_validity is 'Срок окончания действия';



--
-- Удаление устаревших версий таблиц
--
drop table if exists rack_migrations;
drop table if exists rack_storage;

-- ----------------------------------------------------------------------------------
-- Информация о миграциях
-- ----------------------------------------------------------------------------------
create table rack_migrations (

    migration_product   varchar(120)                                    not null    ,
    migration_version   varchar(20)         default '0.0.0'             not null    ,
    migration_timestamp timestamp           default now()               not null    ,
    migration_comment   text                                                        ,

    constraint pk_rack_migrations primary key (migration_product, migration_version)
);

-- Комментарии ----------------------------------------------------------------------
comment on table  rack_migrations                     is 'Информация о миграциях';
comment on column rack_migrations.migration_product   is 'Идентификатор продукта';
comment on column rack_migrations.migration_version   is 'Номер версии';
comment on column rack_migrations.migration_timestamp is 'Дата и время миграции';
comment on column rack_migrations.migration_comment   is 'Комментарий';

-- Инициализация --------------------------------------------------------------------
insert into rack_migrations values ('rack-net/rack', '1.0.0', now(), '');

-- ----------------------------------------------------------------------------------
-- Хранилище данных
-- ----------------------------------------------------------------------------------
create table rack_storage (

    storage_name        varchar(120)                                    not null    ,
    storage_key         varchar(120)                                    not null    ,
    storage_value       text                                                        ,
    storage_validity    timestamp                                       not null    ,

    constraint pk_rack_storage primary key (storage_name, storage_key)
);

-- Комментарии ----------------------------------------------------------------------
comment on table  rack_storage                  is 'Хранилище данных';
comment on column rack_storage.storage_name     is 'Имя хранилища';
comment on column rack_storage.storage_key      is 'Ключ';
comment on column rack_storage.storage_value    is 'Значение';
comment on column rack_storage.storage_validity is 'Срок окончания действия';


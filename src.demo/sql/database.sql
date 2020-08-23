-- ------------------------------------------------------------------
-- Удаление старых версий таблиц
-- ------------------------------------------------------------------
drop table if exists user_tasks;
drop table if exists tasks;
drop table if exists projects;
drop table if exists users;

-- ------------------------------------------------------------------
-- Пользователи
-- ------------------------------------------------------------------
create table users (

    user_id         uuid                                not null    ,
    user_email      varchar(127)                        not null    ,
    user_full_name  varchar(64)                                     ,

    -- Первичный ключ -----------------------------------------------
    constraint pk_users
        primary key (user_id)

);

-- Комментарии ------------------------------------------------------
comment on table  users                is 'Пользователи';
comment on column users.user_id        is 'Уникальный идентификатор';
comment on column users.user_email     is 'Адрес электронной почты';
comment on column users.user_full_name is 'Полное имя пользователя';

-- Инициализация ----------------------------------------------------
insert into users (
    user_id         ,
    user_email      ,
    user_full_name
) values (
    '18f0682f-7167-49b8-acd9-e6967c15c528'  ,
    'i.ivanov@example.com'                  ,
    'Иван И. Иванов'
);

insert into users (
    user_id         ,
    user_email      ,
    user_full_name
) values (
    '9c625038-be93-42cc-a80f-231cd77ea2ea'  ,
    'p.pertov@example.com'                  ,
    'Петр П. Петров'
);

-- ------------------------------------------------------------------
-- Проекты
-- ------------------------------------------------------------------
create table projects (

    project_id      uuid                                not null    ,
    user_id         uuid                                            ,
    project_title   varchar(64)                         not null    ,

    -- Первичный ключ -----------------------------------------------
    constraint pk_projects
        primary key (project_id)                                    ,

    -- Ссылка на пользователя ---------------------------------------
    constraint fk_projects_user_id
        foreign key (user_id) references users (user_id)

);

-- Комментарии ------------------------------------------------------
comment on table  projects               is 'Проеекты';
comment on column projects.project_id    is 'Уникальный идентификатор';
comment on column projects.user_id       is 'Идентификатор владельца';
comment on column projects.project_title is 'Наименование';

-- Инициализация ----------------------------------------------------
insert into projects (
    project_id      ,
    user_id         ,
    project_title
) values (
    'd2ebe471-9308-4879-a4d2-f6143a6bf7e6'  ,
    '18f0682f-7167-49b8-acd9-e6967c15c528'  ,
    'Замена масла'
);

insert into projects (
    project_id      ,
    user_id         ,
    project_title
) values (
    'be01a180-2951-4c64-a971-445fcf8bd469'  ,
    null                                    , -- '9c625038-be93-42cc-a80f-231cd77ea2ea'  ,
    'Зимняя резина'
);

-- ------------------------------------------------------------------
-- Задачи
-- ------------------------------------------------------------------
create table tasks (

    task_id         uuid                                not null    ,
    project_id      uuid                                not null    ,
    task_status     varchar(20)     default 'ACTIVE'    not null    ,
    task_title      varchar(64)                         not null    ,
    task_comment    text                                            ,

    -- Первичный ключ -----------------------------------------------
    constraint pk_tasks
        primary key (task_id)                                       ,

    -- Ссылка на проект ---------------------------------------------
    constraint fk_tasks_project_id
        foreign key (project_id) references projects (project_id)   ,

    -- Проверка для статуса -----------------------------------------
    constraint ch_tasks_task_status
        check (task_status in ('ACTIVE', 'COMPLETE'))

);

-- Комментарии ------------------------------------------------------
comment on table  tasks              is 'Задачи';
comment on column tasks.task_id      is 'Уникальный идентификатор';
comment on column tasks.project_id   is 'Идентифиатор проекта';
comment on column tasks.task_title   is 'Наименование';
comment on column tasks.task_status  is 'Состояние';
comment on column tasks.task_comment is 'Комментарий';

-- Инициализация ----------------------------------------------------
insert into tasks (
    task_id         ,
    project_id      ,
    task_status     ,
    task_title      ,
    task_comment
) values (
    'ac0b77d5-d7f0-457b-b08b-699648c4c2e1'  ,
    'd2ebe471-9308-4879-a4d2-f6143a6bf7e6'  ,
    'ACTIVE'                                ,
    'Слить отработанное масло'              ,
    ''
);

insert into tasks (
    task_id         ,
    project_id      ,
    task_status     ,
    task_title      ,
    task_comment
) values (
    '1aa1d868-0bdf-48f6-9681-d2f7fa47415d'  ,
    'd2ebe471-9308-4879-a4d2-f6143a6bf7e6'  ,
    'ACTIVE'                                ,
    'Промывка'                              ,
    ''
);

insert into tasks (
    task_id         ,
    project_id      ,
    task_status     ,
    task_title      ,
    task_comment
) values (
    'f080cb06-89d5-4141-8cf0-1f0dea327b88'  ,
    'd2ebe471-9308-4879-a4d2-f6143a6bf7e6'  ,
    'ACTIVE'                                ,
    'Залить свежее масло'                   ,
    ''
);

insert into tasks (
    task_id         ,
    project_id      ,
    task_status     ,
    task_title      ,
    task_comment
) values (
    '73256393-8bff-4593-a4fe-59d672b4d000'  ,
    'd2ebe471-9308-4879-a4d2-f6143a6bf7e6'  ,
    'ACTIVE'                                ,
    'Снять летнюю резину'                   ,
    ''
);

insert into tasks (
    task_id         ,
    project_id      ,
    task_status     ,
    task_title      ,
    task_comment
) values (
    '6c2c6235-5796-434f-8575-b2dc85221a8f'  ,
    'd2ebe471-9308-4879-a4d2-f6143a6bf7e6'  ,
    'ACTIVE'                                ,
    'Установить зимнюю резину'              ,
    ''
);

-- ------------------------------------------------------------------
-- Исполнители задач
-- ------------------------------------------------------------------
create table user_tasks (

    user_id         uuid                                not null    ,
    task_id         uuid                                not null    ,

    -- Первичный ключ -----------------------------------------------
    constraint pk_user_tasks
        primary key (user_id, task_id)                              ,

    -- Ссылка на пользователя ---------------------------------------
    constraint fk_user_tasks_user_id
        foreign key (user_id) references users (user_id)            ,

    -- Ссылка на задачу ---------------------------------------------
    constraint fk_user_tasks_task_id
        foreign key (task_id) references tasks (task_id)

);

-- Комментарии ------------------------------------------------------
comment on table  user_tasks         is 'Исполнители задач';
comment on column user_tasks.user_id is 'Идентификатор пользователя';
comment on column user_tasks.task_id is 'Идентификатор задачи';

-- Инициализация ----------------------------------------------------

-- Иванов сливает старое масло
insert into user_tasks (
    user_id ,
    task_id
) values (
    '18f0682f-7167-49b8-acd9-e6967c15c528'  ,
    'ac0b77d5-d7f0-457b-b08b-699648c4c2e1'
);

-- Иванов и Петров занимаются промывкой
insert into user_tasks (
    user_id ,
    task_id
) values (
    '18f0682f-7167-49b8-acd9-e6967c15c528'  ,
    '1aa1d868-0bdf-48f6-9681-d2f7fa47415d'
);

insert into user_tasks (
    user_id ,
    task_id
) values (
    '9c625038-be93-42cc-a80f-231cd77ea2ea'  ,
    '1aa1d868-0bdf-48f6-9681-d2f7fa47415d'
);

-- Иванов и Петров заливают новое масло
insert into user_tasks (
    user_id ,
    task_id
) values (
    '18f0682f-7167-49b8-acd9-e6967c15c528'  ,
    'f080cb06-89d5-4141-8cf0-1f0dea327b88'
);

insert into user_tasks (
    user_id ,
    task_id
) values (
    '9c625038-be93-42cc-a80f-231cd77ea2ea'  ,
    'f080cb06-89d5-4141-8cf0-1f0dea327b88'
);

-- Иванов снимает летнюю резину
insert into user_tasks (
    user_id ,
    task_id
) values (
    '18f0682f-7167-49b8-acd9-e6967c15c528'  ,
    '73256393-8bff-4593-a4fe-59d672b4d000'
);

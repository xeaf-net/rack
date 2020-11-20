<?php declare(strict_types = 1);

/**
 * ILocalization.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Interfaces;

/**
 * Реализует методы локализации
 *
 * @package XEAF\Rack\API\Interfaces
 */
interface ILocalization extends IFactoryObject {

    /**
     * Возвращает объект локали по имени
     *
     * @param string $name Имя локали
     *
     * @return \XEAF\Rack\API\Interfaces\ILocale
     */
    public function getLocale(string $name): ILocale;

    /**
     * Возвращает объект локали по умолчанию
     *
     * @return \XEAF\Rack\API\Interfaces\ILocale
     */
    public function getDefaultLocale(): ILocale;

    /**
     * Задает идентификатор локали по умолчанию
     *
     * @param string $name Имя локали
     *
     * @return void
     */
    public function setDefaultLocale(string $name): void;

    /**
     * Регистрирует новую локаль
     *
     * @param string                            $name    Имя локали
     * @param \XEAF\Rack\API\Interfaces\ILocale $locale  Локаль
     * @param bool                              $default Признак локали по умолчанию
     *
     * @return void
     */
    public function registerLocale(string $name, ILocale $locale, bool $default = false): void;

    /**
     * Отменяет регистрацию локали
     *
     * @param string $name Имя локали
     *
     * @return void
     */
    public function unregisterLocale(string $name): void;

    /**
     * Регистрирует класс для загрузки значений языковых переменных
     *
     * @param string $className Имя класса
     *
     * @return void
     */
    public function registerLanguageClass(string $className): void;

    /**
     * Возвращает значение языковой переменной
     *
     * @param string      $name   Имя переменной
     * @param string|null $locale Имя локали
     *
     * @return string
     */
    public function getLanguageVar(string $name, string $locale = null): string;

    /**
     * Форматирует данные с использованием языковой переменной
     *
     * @param string      $name   Имя переменной
     * @param array       $args   Аргументы
     * @param string|null $locale Имя локали
     *
     * @return string
     */
    public function fmtLanguageVar(string $name, array $args, string $locale = null): string;

    /**
     * Возвращает список значений всех переменных локали
     *
     * @param string|null $locale Имя локали
     *
     * @return \XEAF\Rack\API\Interfaces\IKeyValue
     */
    public function getLocaleVars(string $locale = null): IKeyValue;

    /**
     * Возвращает список значений всех языковых переменных
     *
     * @param string|null $locale Имя локали
     *
     * @return \XEAF\Rack\API\Interfaces\IKeyValue
     */
    public function getLanguageVars(string $locale = null): IKeyValue;

    /**
     * Возвращает локализованную версию пути файла
     *
     * @param string      $filePath Путь к файлу
     * @param string|null $ext      Расширение имени файла
     * @param string|null $locale   Локаль
     *
     * @return string
     */
    public function localizedFilePath(string $filePath, string $ext = null, string $locale = null): string;
}

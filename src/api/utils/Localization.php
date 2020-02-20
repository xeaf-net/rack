<?php

/**
 * Localization.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyValue;
use XEAF\Rack\API\Interfaces\IKeyValue;
use XEAF\Rack\API\Interfaces\ILocale;
use XEAF\Rack\API\Interfaces\ILocalization;
use XEAF\Rack\API\Utils\Exceptions\CoreException;
use XEAF\Rack\API\Utils\Locales\EnglishLocale;
use XEAF\Rack\API\Utils\Locales\RussianLocale;

/**
 * Реализует методы локализации
 *
 * @package XEAF\Rack\API\Utils
 */
class Localization implements ILocalization {

    /**
     * Имя локали по умолчанию
     */
    public const DEFAULT_LOCALE = EnglishLocale::LOCALE_NAME;

    /**
     * Идентификатор локализации
     */
    public const L10N = 'l10n';

    /**
     * Идентификатор языковых переменных
     */
    public const LANG = 'lang';

    /**
     * Директория файлов языковых переменных
     */
    protected const LANG_FILE_DIR = self::L10N;

    /**
     * Расширение имени файла
     */
    protected const LANG_FILE_EXT = 'lng';

    /**
     * Зарегистрированные локали
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_locales = null;

    /**
     * Имя локали по умолчанию
     * @var string
     */
    private $_defaultLocale = self::DEFAULT_LOCALE;

    /**
     * Список языковых переменных
     * @var \XEAF\Rack\API\Interfaces\IKeyValue
     */
    private $_languageVars = null;

    /**
     * Список уже загруженных файлов
     * @var array
     */
    private $_languageFiles = [];

    /**
     * Список классов для загрузки файлов языковых переменных
     * @var array
     */
    private $_languageClasses = [];

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->_locales      = new KeyValue();
        $this->_languageVars = new KeyValue();
        $this->registerLocale(EnglishLocale::LOCALE_NAME, new EnglishLocale(), true);
        $this->registerLocale(RussianLocale::LOCALE_NAME, new RussianLocale());
    }

    /**
     * @inheritDoc
     */
    public function getLocale(string $name): ILocale {
        $result = $this->_locales->get($name);
        if (!$result) {
            $result = $this->getDefaultLocale();
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultLocale(): ILocale {
        return $this->getLocale($this->_defaultLocale);
    }

    /**
     * @inheritDoc
     */
    public function setDefaultLocale(string $name): void {
        $this->_defaultLocale = $name;
    }

    /**
     * @inheritDoc
     */
    public function registerLocale(string $name, ILocale $locale, bool $default = false): void {
        $this->_locales->put($name, $locale);
        if ($default) {
            $this->setDefaultLocale($name);
        }
    }

    /**
     * @inheritDoc
     */
    public function unregisterLocale(string $name): void {
        if ($name != self::DEFAULT_LOCALE) {
            $this->_locales->delete($name);
        }
        if ($this->_defaultLocale == $name) {
            $this->setDefaultLocale(self::DEFAULT_LOCALE);
        }
    }

    /**
     * @inheritDoc
     */
    public function registerLanguageClass(string $className): void {
        if (!in_array($className, $this->_languageClasses)) {
            $this->_languageClasses[] = $className;
        }
    }

    /**
     * @inheritDoc
     */
    public function getLanguageVar(string $name, string $locale = null): string {
        $loc    = $locale == null ? $this->_defaultLocale : $locale;
        $result = $this->internalGetLanguageVar($loc, $name);
        if ($result == null) {
            $this->reloadLanguageFiles($loc);
            $result = $this->internalGetLanguageVar($loc, $name);
            if ($result == null) {
                $result = $name;
                $this->internalPutLanguageVar($loc, $name, $result);
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function fmtLanguageVar(string $name, array $args, string $locale = null): string {
        $format = $this->getLanguageVar($name, $locale);
        return vsprintf($format, $args);
    }

    /**
     * @inheritDoc
     */
    public function getLocaleVars(string $locale = null): IKeyValue {
        $name   = $locale == null ? $this->_defaultLocale : $locale;
        $data   = $this->getLocale($name)->toArray();
        $result = new KeyValue();
        foreach ($data as $key => $value) {
            $result->put($key, $value);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getLanguageVars(string $locale = null): IKeyValue {
        $loc = $locale == null ? $this->_defaultLocale : $locale;
        $this->reloadLanguageFiles($locale);
        return $this->_languageVars->get($loc) ?? new KeyValue();
    }

    /**
     * Перезагружает файлы значений языковых переменных
     *
     * @param string $locale Имя локали
     *
     * @return void
     */
    protected function reloadLanguageFiles(string $locale): void {
        foreach ($this->_languageClasses as $className) {
            $this->loadClassLanguageFile($className, $locale);
        }
    }

    /**
     * Загружает файл значений языковых переменных для класса
     *
     * @param string $className Имя класса
     * @param string $locale    Имя локали
     *
     * @return void
     */
    protected function loadClassLanguageFile(string $className, string $locale): void {
        $fileName = $this->classLanguageFileName($className, $locale);
        if ($fileName && !in_array($fileName, $this->_languageFiles)) {
            $data = parse_ini_file($fileName, true);
            foreach ($data as $section => $sectionData) {
                foreach ($sectionData as $name => $value) {
                    $key = "$section.$name";
                    $this->internalPutLanguageVar($locale, $key, $value);
                }
            }
            $this->_languageFiles[] = $fileName;
        }
    }

    /**
     * Возвращает имя файла значений языковых переменных
     *
     * @param string      $className Имя класса
     * @param string|null $locale    Имя локали
     *
     * @return string
     */
    protected function classLanguageFileName(string $className, string $locale = null): string {
        $result = '';
        try {
            $fs   = FileSystem::getInstance();
            $ref  = Reflection::getInstance();
            $cf   = $ref->classFileName($className);
            $dir  = $fs->fileDir($cf) . '/' . self::LANG_FILE_DIR;
            $file = $fs->fileName($cf) . '.' . $locale . '.' . self::LANG_FILE_EXT;
            $path = $dir . '/' . $file;
            if ($fs->fileExists($path)) {
                $result = $path;
            }
        } catch (CoreException $reason) {
            Logger::getInstance()->exception($reason);
        }
        return $result;
    }

    /**
     * Внутренний метод получения значения языковой пременной
     *
     * @param string $locale Имя локали
     * @param string $name   Имя переменной
     *
     * @return string|null
     */
    private function internalGetLanguageVar(string $locale, string $name): ?string {
        $result  = null;
        $storage = $this->_languageVars->get($locale);
        if ($storage) {
            assert($storage instanceof IKeyValue);
            $result = $storage->get($name);
        }
        return $result;
    }

    /**
     * Внутренний метод сохранения значения языковой переменной
     *
     * @param string $locale Имя локали
     * @param string $name   Имя переменной
     * @param string $value  Значение
     *
     * @return void
     */
    private function internalPutLanguageVar(string $locale, string $name, string $value): void {
        if (!$this->_languageVars->exists($locale)) {
            $this->_languageVars->put($locale, new KeyValue());
        }
        $storage = $this->_languageVars->get($locale);
        assert($storage instanceof IKeyValue);
        $storage->put($name, $value);
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\ILocalization
     */
    public static function getInstance(): ILocalization {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof ILocalization);
        return $result;
    }
}

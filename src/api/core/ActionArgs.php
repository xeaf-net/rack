<?php declare(strict_types = 1);

/**
 * ActionArgs.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Interfaces\IActionArgs;
use XEAF\Rack\API\Models\UploadedFile;
use XEAF\Rack\API\Utils\FileMIME;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Strings;

/**
 * Реализует методы контейнера параметров вызова приложения
 *
 * @property-read string      $methodName Имя метода
 * @property-read string|null $actionNode Имя узла маршрута
 * @property-read string|null $actionPath Путь действия
 * @property-read string|null $actionMode Режим вызова действия
 * @property-read string|null $objectId   Идентификатор объекта действия
 * @property-read string|null $objectPath Путь объекта действия
 * @property-read string|null $currentURL Полный URL вызова действия
 * @property-read string      $locale     Локаль
 *
 * @package XEAF\Rack\API\Core
 */
abstract class ActionArgs extends DataModel implements IActionArgs {

    /**
     * Имя метода
     * @var string
     */
    protected $_methodName = '';

    /**
     * Имя узла действия
     * @var string
     */
    protected $_actionNode = Router::ROOT_NODE;

    /**
     * Путь действия
     * @var string
     */
    protected $_actionPath = null;

    /**
     * Режим вызова действия
     * @var string|null
     */
    protected $_actionMode = null;

    /**
     * Идентификатор объекта действия
     * @var string|null
     */
    protected $_objectId = null;

    /**
     * Путь объекта действия
     * @var string|null
     */
    protected $_objectPath = null;

    /**
     * Полный URL вызова действия
     * @var string|null
     */
    protected $_currentURL = null;

    /**
     * Параметры вызова действия
     * @var array
     */
    protected $_parameters = [];

    /**
     * Информация о переданных файлах
     * @var array
     */
    protected $_files = [];

    /**
     * Параметры заголовков вызова
     * @var array
     */
    protected $_headers = [];

    /**
     * @inheritDoc
     */
    public function getMethodName(): string {
        return $this->_methodName;
    }

    /**
     * @inheritDoc
     */
    public function getActionNode(): string {
        return $this->_actionNode;
    }

    /**
     * @inheritDoc
     */
    public function getActionPath(): ?string {
        return $this->_actionPath;
    }

    /**
     * @inheritDoc
     */
    public function getActionMode(): ?string {
        return $this->_actionMode;
    }

    /**
     * @inheritDoc
     */
    public function getObjectId(): ?string {
        return $this->_objectId;
    }

    /**
     * @inheritDoc
     */
    public function getObjectPath(): ?string {
        return $this->_objectPath;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentURL(): ?string {
        return $this->_currentURL;
    }

    /**
     * Возвращает признак передачи параметра
     *
     * @param string $name Имя параметра
     *
     * @return bool
     */
    public function exists(string $name): bool {
        return array_key_exists($name, $this->_parameters);
    }

    /**
     * @inheritDoc
     */
    public function get(string $name, $defaultValue = null) {
        return $this->_parameters[$name] ?? $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function getString(string $name, string $defaultValue = null): ?string {
        $value = $this->_parameters[$name] ?? $defaultValue;
        if ($value !== null) {
            $value = (string)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getBool(string $name, bool $defaultValue = false): bool {
        $value = $this->_parameters[$name] ?? $defaultValue;
        return (bool)$value;
    }

    /**
     * @inheritDoc
     */
    public function getInteger(string $name, int $defaultValue = 0): int {
        $value   = $this->_parameters[$name] ?? $defaultValue;
        $strings = Strings::getInstance();
        return $strings->stringToInteger((string)$value, $defaultValue);
    }

    /**
     * @inheritDoc
     */
    public function getFloat(string $name, float $defaultValue = 0.00): float {
        $value   = $this->_parameters[$name] ?? $defaultValue;
        $strings = Strings::getInstance();
        return $strings->stringToFloat((string)$value, $defaultValue);
    }

    /**
     * @inheritDoc
     */
    public function getUUID(string $name, string $defaultValue = null): ?string {
        $value   = $this->_parameters[$name] ?? $defaultValue;
        $strings = Strings::getInstance();
        if (!$strings->isUUID((string)$value)) {
            $value = $defaultValue;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getArray(string $name, array $defaultValue = []): array {
        $value = $this->_parameters[$name] ?? $defaultValue;
        if (!is_array($value)) {
            $value = $defaultValue;
        }
        return $value;
    }

    /**
     * Возвращает значение целочисленного элемента из массива
     *
     * @param string $name         Имя параметра массива
     * @param string $element      Имя элемента
     * @param int    $defaultValue Значение по умолчанию
     *
     * @return int
     */
    public function getArrayInt(string $name, string $element, int $defaultValue = 0): int {
        $data    = $this->getArray($name);
        $value   = $data[$element] ?? $defaultValue;
        $strings = Strings::getInstance();
        return $strings->stringToInteger((string)$value, $defaultValue);
    }

    /**
     * Возвращает значение строкового элемента из массива
     *
     * @param string      $name         Имя параметра массива
     * @param string      $element      Имя элемента
     * @param string|null $defaultValue Значение по умолчанию
     *
     * @return string|null
     */
    public function getArrayString(string $name, string $element, string $defaultValue = null): ?string {
        $data  = $this->getArray($name);
        $value = $data[$element] ?? $defaultValue;
        if ($value !== null) {
            $value = (string)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getArrayUUID(string $name, string $element, string $defaultValue = null): ?string {
        $value = $this->getArrayString($name, $element, $defaultValue);
        if ($value !== null) {
            $strings = Strings::getInstance();
            if (!$strings->isUUID($value)) {
                $value = $defaultValue;
            }
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getFile(string $name): ?UploadedFile {
        return $this->_files[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name, $defaultValue = null) {
        return $this->_headers[$name] ?? $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function getContentType(): string {
        return $this->getHeader(HttpResponse::CONTENT_TYPE, FileMIME::DEFAULT_MIME_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function getContentMIME(): string {
        $arr = explode(';', $this->getContentType());
        return trim($arr[0]);
    }

    /**
     * @inheritDoc
     */
    public function getContentLength(): int {
        return $this->getHeader(HttpResponse::CONTENT_LENGTH, 0);
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array {
        return $this->_headers;
    }
}

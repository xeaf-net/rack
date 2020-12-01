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
use XEAF\Rack\API\Interfaces\IValidator;
use XEAF\Rack\API\Models\UploadedFile;
use XEAF\Rack\API\Utils\FileMIME;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Validator;

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
    protected string $_methodName = '';

    /**
     * Имя узла действия
     * @var string
     */
    protected string $_actionNode = Router::ROOT_NODE;

    /**
     * Путь действия
     * @var string|null
     */
    protected ?string $_actionPath = null;

    /**
     * Режим вызова действия
     * @var string|null
     */
    protected ?string $_actionMode = null;

    /**
     * Идентификатор объекта действия
     * @var string|null
     */
    protected ?string $_objectId = null;

    /**
     * Путь объекта действия
     * @var string|null
     */
    protected ?string $_objectPath = null;

    /**
     * Полный URL вызова действия
     * @var string|null
     */
    protected ?string $_currentURL = null;

    /**
     * Параметры вызова действия
     * @var array
     */
    protected array $_parameters = [];

    /**
     * Информация о переданных файлах
     * @var array
     */
    protected array $_files = [];

    /**
     * Параметры заголовков вызова
     * @var array
     */
    protected array $_headers = [];

    /**
     * Объект методов проверки значений
     * @var \XEAF\Rack\API\Interfaces\IValidator
     */
    private IValidator $_validator;

    /**
     * Конструктор класса
     *
     * @param array $data Данные инициализации
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        $this->_validator = Validator::getInstance();
    }

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
    public function getString(string $name, string $defaultValue = null, bool $check = false, string $tag = null): ?string {
        $value = $this->_parameters[$name] ?? $defaultValue;
        if ($value !== null) {
            $value = (string)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getStringNN(string $name, string $defaultValue = null, string $tag = null): string {
        $value = $this->_parameters[$name] ?? $defaultValue;
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        return (string)$value;
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
    public function getInteger(string $name, int $defaultValue = null, string $tag = null): ?int {
        $value = $this->_parameters[$name] ?? $defaultValue;
        if ($value !== null) {
            $this->_validator->checkIsInteger($value, $tag ? $tag : $name);
            $value = (int)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getIntegerNN(string $name, int $defaultValue = null, string $tag = null): int {
        $value = $this->_parameters[$name] ?? $defaultValue;
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        $this->_validator->checkIsInteger($value, $tag ? $tag : $name);
        return (int)$value;
    }

    /**
     * @inheritDoc
     */
    public function getNumeric(string $name, float $defaultValue = null, string $tag = null): ?float {
        $value = $this->_parameters[$name] ?? $defaultValue;
        if ($value !== null) {
            $this->_validator->checkIsNumeric($value, $tag ? $tag : $name);
            $value = (float)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getNumericNN(string $name, float $defaultValue = null, string $tag = null): float {
        $value = $this->_parameters[$name] ?? $defaultValue;
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        $this->_validator->checkIsNumeric($value, $tag ? $tag : $name);
        return (float)$value;
    }

    /**
     * @inheritDoc
     */
    public function getUUID(string $name, string $defaultValue = null, string $tag = null): ?string {
        $value = (string)($this->_parameters[$name] ?? $defaultValue);
        if ($value !== null) {
            $this->_validator->checkUUID($value, $tag ? $tag : $name);
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getUUIDNN(string $name, string $defaultValue = null, string $tag = null): string {
        $value = (string)($this->_parameters[$name] ?? $defaultValue);
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        $this->_validator->checkUUID($value, $tag ? $tag : $name);
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getEmail(string $name, string $defaultValue = null, string $tag = null): ?string {
        $value = (string)($this->_parameters[$name] ?? $defaultValue);
        if ($value !== null) {
            $this->_validator->checkEmail($value, $tag ? $tag : $name);
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getEmailNN(string $name, string $defaultValue = null, string $tag = null): string {
        $value = (string)($this->_parameters[$name] ?? $defaultValue);
        $this->_validator->checkEmail($value, $tag);
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getPhone(string $name, string $defaultValue = null, string $tag = null): ?string {
        $value = (string)($this->_parameters[$name] ?? $defaultValue);
        if ($value !== null) {
            $this->_validator->checkPhone($value, $tag);
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getPhoneNN(string $name, string $defaultValue = null, string $tag = null): string {
        $value = (string)($this->_parameters[$name] ?? $defaultValue);
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        $this->_validator->checkPhone($value, $tag ? $tag : $name);
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
     * @inheritDoc
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
    public function getArrayStringNN(string $name, string $element, string $defaultValue = null, string $tag = null): string {
        $data  = $this->getArray($name);
        $value = $data[$element] ?? $defaultValue;
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getArrayInteger(string $name, string $element, int $defaultValue = null, string $tag = null): ?int {
        $data  = $this->getArray($name);
        $value = $data[$element] ?? $defaultValue;
        if ($value !== null) {
            $this->_validator->checkIsInteger($value, $tag ? $tag : $name);
            $value = (int)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getArrayIntegerNN(string $name, string $element, int $defaultValue = null, string $tag = null): int {
        $data  = $this->getArray($name);
        $value = $data[$element] ?? $defaultValue;
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        $this->_validator->checkIsInteger($value, $tag ? $tag : $name);
        return (int)$value;
    }

    /**
     * @inheritDoc
     */
    public function getArrayNumeric(string $name, string $element, float $defaultValue = null, string $tag = null): ?float {
        $data  = $this->getArray($name);
        $value = $data[$element] ?? $defaultValue;
        if ($value !== null) {
            $this->_validator->checkIsNumeric($value, $tag ? $tag : $name);
            $value = (float)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getArrayNumericNN(string $name, string $element, float $defaultValue = null, string $tag = null): float {
        $data  = $this->getArray($name);
        $value = $data[$element] ?? $defaultValue;
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        $this->_validator->checkIsNumeric($value, $tag ? $tag : $name);
        return (float)$value;
    }

    /**
     * @inheritDoc
     */
    public function getArrayUUID(string $name, string $element, string $defaultValue = null, string $tag = null): ?string {
        $data  = $this->getArray($name);
        $value = $data[$element] ?? $defaultValue;
        if ($value !== null) {
            $this->_validator->checkUUID($value, $tag ? $tag : $name);
            $value = (string)$value;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getArrayUUIDNN(string $name, string $element, string $defaultValue = null, string $tag = null): string {
        $data  = $this->getArray($name);
        $value = (string)($data[$element] ?? $defaultValue);
        $this->_validator->checkNotEmpty($value, $tag ? $tag : $name);
        $this->_validator->checkUUID($value, $tag ? $tag : $name);
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

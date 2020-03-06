<?php declare(strict_types = 1);

/**
 * FileStorageProvider.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils\Storage;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Core\KeyStorage;
use XEAF\Rack\API\Interfaces\IStorageProvider;
use XEAF\Rack\API\Models\Config\FileStorageConfig;
use XEAF\Rack\API\Traits\NamedObjectTrait;
use XEAF\Rack\API\Utils\Exceptions\FileSystemException;
use XEAF\Rack\API\Utils\FileSystem;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Провайдер файлового хранилища Ключ-Значение
 *
 * @package XEAF\Rack\API\Utils\Storage
 */
class FileStorageProvider extends KeyStorage implements IStorageProvider {

    use NamedObjectTrait;

    /**
     * Имя провайдера
     */
    public const PROVIDER_NAME = 'file';

    /**
     * Расширение имени файла по умолчанию
     */
    public const FILE_NAME_EXT = 'dat';

    /**
     * Путь из файла конфигурации
     * @var string|null
     */
    private $_path = null;

    /**
     * Объект методов доступа к файловой системе
     * @var \XEAF\Rack\API\Interfaces\IFileSystem|null
     */
    private $_fileSystem = null;

    /**
     * Объект метдов сериализации данных
     * @var \XEAF\Rack\API\Interfaces\ISerializer|null
     */
    private $_serializer = null;

    /**
     * Конструктор класса
     *
     * @param string $name Имя объекта
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\FileSystemException
     */
    public function __construct(string $name = Factory::DEFAULT_NAME) {
        $this->_name       = $name;
        $this->_path       = FileStorageConfig::getInstance($name)->getPath();
        $this->_fileSystem = FileSystem::getInstance();
        $this->_serializer = Serializer::getInstance();
        if (!$this->_fileSystem->folderExists($this->_path)) {
            throw FileSystemException::folderNotFound($this->_path);
        }
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function clear(): void {
        $data = $this->toArray();
        foreach ($data as $key => $value) {
            $this->delete($key);
        }
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function get(string $key, $defaultValue = null) {
        if (parent::exists($key)) {
            $result = parent::get($key, $defaultValue);
        } else {
            $fs       = FileSystem::getInstance();
            $fileName = $this->getFileName($key);
            if ($fs->fileExists($fileName)) {
                $data   = file_get_contents($fileName);
                $result = $this->_serializer->unserialize($data);
            } else {
                $result = $defaultValue;
            }
            parent::put($key, $result);
        }
        return $result;
    }

    /**
     * @inheritDoc
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function put(string $key, $value = null, int $ttl = 0): void {
        parent::put($key, $value);
        $fileName = $this->getFileName($key);
        $value    = $this->_serializer->serialize($value);
        file_put_contents($fileName, $value, LOCK_EX);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): void {
        parent::delete($key);
        $fileName = $this->getFileName($key);
        $this->_fileSystem->deleteFile($fileName);
    }

    /**
     * @inheritDoc
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function exists(string $key): bool {
        $fileName = $this->getFileName($key);
        return $this->_fileSystem->fileExists($fileName);
    }

    /**
     * Возвращает имя файла для хранения значения переменной
     *
     * @param string $key Ключ
     *
     * @return string
     */
    private function getFileName(string $key): string {
        return $this->_path . '/' . $key . '.' . self::FILE_NAME_EXT;
    }
}

<?php declare(strict_types = 1);

/**
 * Parameters.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Core\ActionArgs;
use XEAF\Rack\API\Interfaces\IActionArgs;
use XEAF\Rack\API\Models\Config\PortalConfig;
use XEAF\Rack\API\Models\Results\StatusResult;
use XEAF\Rack\API\Models\UploadedFile;

/**
 * Реализует методы разбора параметров
 *
 * @package XEAF\Rack\API\Utils
 */
class Parameters extends ActionArgs {

    /**
     * Идентификатор HTTP метода GET
     */
    public const GET_METHOD_NAME = 'GET';

    /**
     * Идентификатор HTTP метода HEAD
     */
    public const HEAD_METHOD_NAME = 'HEAD';

    /**
     * Идентификатор HTTP метода POST
     */

    public const POST_METHOD_NAME = 'POST';

    /**
     * Идентификатор HTTP метода PATCH
     */
    public const PATCH_METHOD_NAME = 'PATCH';

    /**
     * Идентификатор HTTP метода DELETE
     */
    public const DELETE_METHOD_NAME = 'DELETE';

    /**
     * Идентификатор HTTP метода OPTIONS
     */
    public const OPTIONS_METHOD_NAME = 'OPTIONS';

    /**
     * Разрешен доступ с любого домена
     */
    public const ORIGIN_ALL = '*';

    /**
     * Время действия заголовка origin в секундах
     */
    public const ORIGIN_AGE = 24 * 60 * 60;

    /**
     * Идентификатор парамерта заголовка имени файла
     */
    private const HEADER_FILE_NAME = 'X-FileName';

    /**
     * Идентификатор параметра имени файла
     */
    public const FILE_PARAMETER_NAME = 'x-file';

    /**
     * Идентификатор параметра пути
     */
    private const PATH_PARAMETER_NAME = 'x-path';

    /**
     * Конструктор класса
     *
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    public function __construct() {
        parent::__construct();
        $this->processRequestMethod();
        $this->processRequestOrigin();
        $this->processRequestHeaders();
        switch ($this->_methodName) {
            case self::GET_METHOD_NAME:
            case self::HEAD_METHOD_NAME:
            case self::DELETE_METHOD_NAME:
                $this->processRequestParameters($_GET);
                break;
            case self::POST_METHOD_NAME:
            case self::PATCH_METHOD_NAME:
                $this->processRequestParameters($_GET);
                $this->processRequestParameters($_POST);
                $this->processInputStream();
                $this->processRequestFiles();
                break;
            case self::OPTIONS_METHOD_NAME:
                $this->processOptionsHeaders();
                die(); // Только заголовки
                /** @noinspection PhpUnreachableStatementInspection */
                break;
            default:
                $result = StatusResult::notImplemented();
                $result->processResult();
                die();
                /** @noinspection PhpUnreachableStatementInspection */
                break;
        }
        $this->postProcessParameters();
    }

    /**
     * Обрабатывает методы вызова приложения
     *
     * @return void
     */
    protected function processRequestMethod(): void {
        $this->_methodName = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Устанавливает заголовки origins
     *
     * @return void
     */
    protected function processRequestOrigin(): void {
        $config       = PortalConfig::getInstance();
        $configOrigin = $config->getOrigin();
        $serverOrigin = $_SERVER['HTTP_ORIGIN'] ?? null;
        if ($configOrigin == self::ORIGIN_ALL || $configOrigin == $serverOrigin) {
            header("Access-Control-Allow-Origin: $serverOrigin");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: ' . self::ORIGIN_AGE);
        }
    }

    /**
     * Разбирает заголовки запроса
     *
     * @return void
     */
    protected function processRequestHeaders(): void {
        $this->_headers = getallheaders();
    }

    /**
     * Разбирает параметры вызова приложения
     *
     * @param array $parameters
     *
     * @return void
     */
    protected function processRequestParameters(array $parameters): void {
        foreach ($parameters as $name => $value) {
            if ($name == self::PATH_PARAMETER_NAME) {
                $this->processPathParameter($value);
            } else {
                $this->_parameters[$name] = $value;
            }
        }
    }

    /**
     * Разбирает данные из входного потока
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    protected function processInputStream(): void {
        $contentMIME = $this->getContentMIME();
        if ($contentMIME) {
            $content = file_get_contents('php://input');
            if ($contentMIME == FileMIME::APPLICATION_JSON) {
                $this->processInputJSON((string)$content);
            } else {
                $this->processInputFile($contentMIME, $content);
            }
        }
    }

    /**
     * Обрабатывает JSON из входного потока
     *
     * @param string $jsonData Данные в формате JSON
     *
     * @return void
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    protected function processInputJSON(string $jsonData): void {
        $strings = Strings::getInstance();
        if (!$strings->isEmpty($jsonData)) {
            $serializer = Serializer::getInstance();
            $params     = $serializer->jsonArrayDecode($jsonData);
            if (is_array($params)) {
                foreach ($params as $name => $value) {
                    $this->_parameters[$name] = $value;
                }
            }
        }
    }

    /**
     * Обрабатывает файлы из входного потока
     *
     * @param string $mime    MIME файла
     * @param mixed  $content Содержимое входного потока
     *
     * @return void
     */
    protected function processInputFile(string $mime, $content): void {
        if (count($_FILES) > 0) {
            return;
        }
        $fileMIME   = FileMIME::getInstance();
        $fileSystem = FileSystem::getInstance();
        if ($fileMIME->isSupportedMIME($mime)) {
            $fileName = $this->get(self::FILE_PARAMETER_NAME, $this->getHeader(self::HEADER_FILE_NAME));
            if ($fileName) {
                $tempPath = $fileSystem->tempFileName();
                file_put_contents($tempPath, $content, FILE_APPEND);
                $fileSize                                = $fileSystem->fileSize($tempPath);
                $this->_files[self::FILE_PARAMETER_NAME] = $this->createUploadedFile($fileName, $mime, $fileSize, $tempPath);
            }
        }
    }

    /**
     * Обрабатывает заголовки метода OPTIONS
     *
     * @return void
     */
    protected function processOptionsHeaders(): void {
        if ($this->methodName == self::OPTIONS_METHOD_NAME) {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header('Access-Control-Allow-Methods: GET, HEAD, POST, PATCH, DELETE, OPTIONS');
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
        }
    }

    /**
     * Разбирает данные о переданных файлах
     *
     * @return void
     */
    protected function processRequestFiles(): void {
        foreach ($_FILES as $name => $file) {
            if ($file['name']) {
                $this->_files[$name] = $this->createUploadedFile($file['name'], $file['type'], $file['size'], $file['tmp_name']);
            }
        }
    }

    /**
     * Дополнительная обработка парамеров
     *
     * @return void
     */
    protected function postProcessParameters(): void {
        foreach ($this->_parameters as $name => $value) {
            if ($value != null && !is_array($value)) {
                $this->_parameters[$name] = trim((string)$value);
            }
        }
    }

    /**
     * Разбирает параметер пути
     *
     * @param string $path Путь
     *
     * @return void
     */
    private function processPathParameter(string $path): void {
        $router  = Router::getInstance();
        $node    = $router->extractPathNode($path);
        $rest    = trim(substr($path, strlen($node)), '/');
        $strings = Strings::getInstance();
        $arr     = explode('/', $rest);
        switch (count($arr)) {
            case 1:
                $this->_actionPath = $arr[0];
                break;
            case 2:
                $this->_actionPath = $arr[0];
                if ($strings->isObjectId($arr[1])) {
                    $this->_objectId = $arr[1];
                } else {
                    $this->_actionMode = $arr[1];
                }
                break;
            case 3:
                $this->_actionPath = $arr[0];
                $this->_actionMode = $arr[1];
                if ($strings->isObjectId($arr[2])) {
                    $this->_objectId = $arr[2];
                } else {
                    $this->_objectPath = $arr[2];
                }
                break;
            default:
                $this->_actionPath = $arr[0];
                $this->_actionMode = $arr[1];
                unset($arr[0]);
                unset($arr[1]);
                $this->_objectPath = implode('/', $arr);
                break;
        }
        $this->_actionNode = $node;
        $this->_actionPath = Router::ROOT_NODE . trim($node . '/' . $this->_actionPath, '/');
    }

    /**
     * Возвращает массив с информацией о файле
     *
     * @param string $fileName Имя файла
     * @param string $fileMIME MIME файла
     * @param int    $fileSize Размер файла
     * @param string $tempPath Путь к файлу
     *
     * @return \XEAF\Rack\API\Models\UploadedFile
     */
    private function createUploadedFile(string $fileName, string $fileMIME, int $fileSize, string $tempPath): UploadedFile {
        $result           = new UploadedFile();
        $result->name     = $fileName;
        $result->mime     = $fileMIME;
        $result->size     = $fileSize;
        $result->tempPath = $tempPath;
        return $result;
    }

    /**
     * Возвращет единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IActionArgs
     */
    public static function getInstance(): IActionArgs {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IActionArgs);
        return $result;
    }
}


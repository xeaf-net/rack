<?php declare(strict_types = 1);

/**
 * HttpResponse.php
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
use XEAF\Rack\API\Interfaces\IHttpResponse;
use XEAF\Rack\API\Models\Config\PortalConfig;

/**
 * Содержит константы кодов состояний HTTP и методы отправки заголовков
 *
 * @package XEAF\Rack\API\Utils
 */
class HttpResponse implements IHttpResponse {

    /**
     * Успешное завершение с возвратом данных
     */
    public const OK = 200;

    /**
     * Успешное завершение с созданием нового ресурса
     */
    public const CREATED = 201;

    /**
     * Успешное завершение без возврата данных
     */
    public const NO_CONTENT = 204;

    /**
     * Постоянная переадресация
     */
    public const MOVED_PERMANENTLY = 301;

    /**
     * Временная переадресация
     */
    public const MOVED_TEMPORARILY = 302;

    /**
     * Ошибка в запросе
     */
    public const BAD_REQUEST = 400;

    /**
     * Пользователь не авторизован
     */
    public const UNAUTHORIZED = 401;

    /**
     * Недостаточно прав доступа к ресурсу
     */
    public const FORBIDDEN = 403;

    /**
     * Объект не найден
     */
    public const NOT_FOUND = 404;

    /**
     * Конфликт изменения (или удаления) данных
     */
    public const CONFLICT = 409;

    /**
     * Фатальная ошибка сервера
     */
    public const FATAL_ERROR = 500;

    /**
     * Метод не реализован
     */
    public const NOT_IMPLEMENTED = 501;

    /**
     * Имя параметра контента
     */
    public const CONTENT_TYPE = 'Content-Type';

    /**
     * Имя параметра размера контента
     */
    public const CONTENT_LENGTH = 'Content-Length';

    /**
     * Тексты сообщений для кодов ответов HTTP протокола
     */
    public const MESSAGES = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
    ];

    /**
     * Кодировка UTF-8
     */
    protected const UTF8 = 'utf-8';

    /**
     * @inheritDoc
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function responseCode(int $statusCode): void {
        if (array_key_exists($statusCode, self::MESSAGES)) {
            http_response_code($statusCode);
            $header = "HTTP/1.1 $statusCode " . self::MESSAGES[$statusCode];
            header($header, true, $statusCode);
        } else {
            $this->responseCode(self::FATAL_ERROR);
        }
    }

    /**
     * @inheritDoc
     */
    public function authenticateBearer(int $statusCode): void {
        if ($statusCode == HttpResponse::UNAUTHORIZED) {
            $config = PortalConfig::getInstance();
            $bearer = $config->getBearer();
            $realm  = $config->getHost();
            header('WWW-Authenticate: ' . $bearer . ' realm="' . $realm . '"');
        }
    }

    /**
     * @inheritDoc
     */
    public function contentType(string $mimeType, ?string $charset = ''): void {
        $header = "Content-Type: $mimeType";
        if ($charset) {
            $header .= "; charset = $charset";
        }
        header($header);
    }

    /**
     * @inheritDoc
     */
    public function contentJSON(): void {
        $mimeType = FileMIME::getInsance()->getMIME('json');
        $this->contentType($mimeType, self::UTF8);
    }

    /**
     * @inheritDoc
     */
    public function locationHeader(string $url): void {
        header('Location: ' . $url);
    }

    /**
     * @inheritDoc
     */
    public function fileAttachmentHeader(string $fileName): void {
        $header = 'Content-Disposition: attachment; ';
        if ($this->isIE()) {
            $header .= 'filename="' . rawurlencode($fileName) . '"';
        } else {
            $header .= 'filename*=UTF-8\'\'' . rawurlencode($fileName);
        }
        header('Access-Control-Expose-Headers: Content-Disposition');
        header($header);
    }

    /**
     * Добавляет заголовок кеширования отправляемого файла
     *
     * @return void
     */
    public function fileCacheHeader(): void {
        $formatter = Formatter::getInstance();
        $cacheSecs = Calendar::SECONDS_PER_HOUR;
        $cacheTime = $formatter->formatCacheDateTime(time() + $cacheSecs);
        header("Expires: $cacheTime");
        header('Pragma: cache');
        header("Cache-Control: max-age=$cacheSecs");
    }

    /**
     * Возвращает признак использования Internet Explorer
     *
     * @return bool
     */
    protected function isIE(): bool {
        return strpos($_SERVER ['HTTP_USER_AGENT'], 'MSIE') !== false;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\IHttpResponse
     */
    public static function getInstance(): IHttpResponse {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IHttpResponse);
        return $result;
    }
}

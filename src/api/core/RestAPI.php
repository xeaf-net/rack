<?php declare(strict_types = 1);

/**
 * RestAPI.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Core;

use XEAF\Rack\API\Interfaces\IRestAPI;
use XEAF\Rack\API\Utils\FileMIME;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует базовые методы для построения провайдеров обращения к внешним API
 *
 * @package XEAF\Rack\API\Core
 */
class RestAPI implements IRestAPI {

    /**
     * Заголовки
     * @var array
     */
    private $_headers;

    /**
     * Объект методов сериализации
     * @var \XEAF\Rack\API\Utils\Serializer|null
     */
    private $_serializer;

    /**
     * Код статуса последнего обращения к API
     * @var int
     */
    private $_statusCode = HttpResponse::OK;

    /**
     * Сообщение об ошибке
     * @var string
     */
    private $_errorMessage = '';

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->_headers    = $this->defaultHeaders();
        $this->_serializer = Serializer::getInstance();
    }

    /**
     * Возвращает набор заголовков по умолчанию
     *
     * @return array
     */
    protected function defaultHeaders(): array {
        return [];
    }

    /**
     * Обращается к API по методу GET и возвращает JSON
     *
     * @param string $url  URL стороннего API
     * @param array  $args Массив параметров
     *
     * @return string|bool
     */
    protected function get(string $url, array $args = []) {
        $api    = curl_init();
        $apiURL = $this->buildURL($url, $args);
        $header = $this->_headers;
        curl_setopt_array($api, [
            CURLOPT_URL            => $apiURL,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true
        ]);
        $result = curl_exec($api);
        $this->processError($api);
        curl_close($api);
        return $result;
    }

    /**
     * Обращается к API по методу POST и возвращает JSON
     *
     * @param string $url      URL стороннего API
     * @param array  $args     Агрументы пути
     * @param array  $postArgs Агрументы метода POST
     *
     * @return string|bool
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    protected function post(string $url, array $args = [], array $postArgs = []) {
        $api      = curl_init();
        $apiURL   = $this->buildURL($url, $args);
        $json     = $this->_serializer->jsonArrayEncode($postArgs);
        $header   = $this->_headers;
        $header[] = 'Content-Type: ' . FileMIME::APPLICATION_JSON;
        $header[] = 'Content-Length: ' . strlen($json);
        curl_setopt_array($api, [
            CURLOPT_URL            => $apiURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $json
        ]);
        $result = curl_exec($api);
        $this->processError($api);
        curl_close($api);
        return $result;
    }

    /**
     * Обращается к API по методу DELETE и возвращает JSON
     *
     * @param string $url      URL стороннего API
     * @param array  $args     Агрументы пути
     * @param array  $postArgs Агрументы метода POST
     *
     * @return string|bool
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     */
    protected function delete(string $url, array $args = [], array $postArgs = []) {
        $api      = curl_init();
        $apiURL   = $this->buildURL($url, $args);
        $header   = $this->_headers;
        $json     = $this->_serializer->jsonArrayEncode($postArgs);
        $header[] = 'Content-Type: ' . FileMIME::APPLICATION_JSON;
        $header[] = 'Content-Length: ' . strlen($json);
        curl_setopt_array($api, [
            CURLOPT_URL            => $apiURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_POSTFIELDS     => $json
        ]);
        $result              = curl_exec($api);
        $this->_errorMessage = curl_error($api);
        curl_close($api);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int {
        return $this->_statusCode;
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(): string {
        return $this->_errorMessage;
    }

    /**
     * @inheritDoc
     */
    public function getErrorState(): bool {
        return $this->getStatusCode() != HttpResponse::OK;
    }

    /**
     * Добавляет относительный путь к URL API портала
     *
     * @param string $url  URL стороннего API
     * @param array  $args Массив параметров
     *
     * @return string
     */
    protected function buildURL(string $url, array $args = []): string {
        $result = rtrim($url, '/');
        if ($args) {
            $result .= '?' . http_build_query($args);
        }
        return $result;
    }

    /**
     * Обрабатывает ошибочную ситуацию
     *
     * @param resource|null $api Ресурс подключения к API
     *
     * @return void
     */
    protected function processError($api = null): void {
        if ($api) {
            $this->_statusCode   = $this->statusCode($api);
            $this->_errorMessage = curl_error($api);
        } else {
            $this->_statusCode   = HttpResponse::OK;
            $this->_errorMessage = '';
        }
    }

    /**
     * Преобразует полученный код состояния
     *
     * @param resource|null $api Ресурс подключения к API
     *
     * @return int
     */
    protected function statusCode($api = null): int {
        $result = HttpResponse::OK;
        if ($api) {
            $code   = curl_getinfo($api, CURLINFO_HTTP_CODE);
            $result = ($code) ? intval($code) : HttpResponse::OK;
            if ($code == 0) {
                $result = HttpResponse::OK;
            }
        }
        return $result;
    }
}

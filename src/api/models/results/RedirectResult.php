<?php declare(strict_types = 1);

/**
 * RedirectResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Core\ActionResult;
use XEAF\Rack\API\Utils\HttpResponse;

/**
 * Реализует методы результата возвращающего требование перенаправления
 *
 * @property string $url URL для перенаправления
 *
 * @package XEAF\Rack\API\Models\Results
 */
class RedirectResult extends ActionResult {

    /**
     * URL для перенаправления
     * @var string
     */
    protected $_url = '';

    /**
     * Конструктор класса
     *
     * @param string $url    URL для перенаправления
     * @param int    $status Код состояния HTTP
     */
    public function __construct(string $url, int $status = HttpResponse::OK) {
        parent::__construct($status);
        $this->_url = $url;
    }

    /**
     * Возвращает URL для перенаправления
     *
     * @return string
     */
    public function getUrl(): string {
        return $this->_url;
    }

    /**
     * Задает URL для перенаправления
     *
     * @param string $url URL для перенаправления
     *
     * @return void
     */
    public function setUrl(string $url): void {
        $this->_url = $url;
    }

    /**
     * Возвращает объект с перенеправлением на действие домашней страницы
     *
     * @return \XEAF\Rack\API\Models\Results\RedirectResult
     */
    public static function redirectToHome(): self {
        return new self(Router::ROOT_NODE);
    }

    /**
     * Возвращает объект с перенеправлением на действие авторизации
     *
     * @return static
     */
    public static function redirectToLogin(): self {
        return new self(Router::LOGIN_PATH);
    }

    /**
     * @inheritDoc
     */
    public function processResult(): void {
        $headers = HttpResponse::getInstance();
        $headers->responseCode($this->getStatusCode());
        $headers->locationHeader($this->getUrl());
    }
}

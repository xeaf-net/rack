<?php declare(strict_types = 1);

/**
 * ErrorResult.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Models\Results;

use XEAF\Rack\API\Interfaces\IActionResult;
use XEAF\Rack\API\Traits\CommonErrorsTrait;
use XEAF\Rack\API\Utils\HttpResponse;
use XEAF\Rack\API\Utils\Localization;
use XEAF\Rack\API\Utils\Serializer;

/**
 * Реализует методы результата возвращающего информацию о ошибке
 *
 * @property string $message Текст сообщения об ошибке
 * @property string $tag     Тег
 *
 * @package XEAF\Rack\API\Models\Results
 */
class ErrorResult extends StatusResult {

    use CommonErrorsTrait;

    /**
     * Текст сообщения об ошибке
     * @var string
     */
    protected $_message = '';

    /**
     * Тег
     * @var string
     */
    protected $_tag = '';

    /**
     * Конструктор класса
     *
     * @param int    $status  Код состояния HTTP
     * @param string $langFmt Имя языковой переменной или формат сообщения
     * @param array  $args    Аргементы сообщения
     * @param string $tag     Тег
     */
    public function __construct(int $status, string $langFmt = '', array $args = [], string $tag = '') {
        parent::__construct($status);
        $format         = Localization::getInstance()->getLanguageVar($langFmt);
        $this->_message = vsprintf($format, $args);
        $this->_tag     = $tag;
    }

    /**
     * Возвращает сообщение об ошибке
     *
     * @return string
     */
    public function getMessage(): string {
        return $this->_message;
    }

    /**
     * Задает сообщение об ошибке
     *
     * @param string $message Текст сообщения об ошибке
     *
     * @return void
     */
    public function setMessage(string $message): void {
        $this->_message = $message;
    }

    /**
     * Возвращает тег
     * @return string
     */
    public function getTag(): string {
        return $this->_tag;
    }

    /**
     * Задает значение тега
     *
     * @param string $tag Тег
     */
    public function setTag(string $tag): void {
        $this->_tag = $tag;
    }

    /**
     * @inheritDoc
     * @throws \XEAF\Rack\API\Utils\Exceptions\SerializerException
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function processResult(): void {
        $result = [
            'status'  => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'tag'     => $this->getTag()
        ];

        $headers = HttpResponse::getInstance();
        $headers->responseCode($this->getHeaderStatusCode());
        $headers->contentJSON();

        if ($result['message'] == '') {
            $result['message'] = HttpResponse::MESSAGES[$this->getStatusCode()] ?? '';
        }
        if ($result['tag'] == '') {
            unset($result['tag']);
        }

        print Serializer::getInstance()->jsonArrayEncode($result);
    }

    /**
     * Возвращает код состояния HTTP для установки в заголовок
     *
     * @return int
     */
    protected function getHeaderStatusCode(): int {
        return $this->getStatusCode();
    }

    /**
     * Сообщение об ошибке аргумента
     *
     * @param string $id      Идентификатор аргумента
     * @param string $langFmt Имя языковой переменной или формат сообщения
     * @param array  $args    Аргументы сообщения
     *
     * @return \XEAF\Rack\API\Interfaces\IActionResult
     */
    public static function argument(string $id, string $langFmt, array $args = []): IActionResult {
        return new ErrorResult(HttpResponse::BAD_REQUEST, $langFmt, $args, $id);
    }
}

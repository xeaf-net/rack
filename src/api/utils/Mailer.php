<?php declare(strict_types = 1);

/**
 * Mailer.php
 *
 * Файл является неотъемлемой частью проекта RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use Throwable;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\Interfaces\IMailer;
use XEAF\Rack\API\Models\Config\MailerConfig;

/**
 * Реализует методы отправки сообщений электронной почты
 *
 * @package  XEAF\Rack\API\Utils
 */
class Mailer implements IMailer {

    /**
     * Служба отправики сообщений
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    private $_phpMailer = null;

    /**
     * Результат последней отправки
     * @var string|null
     */
    private $_lastError = null;

    /**
     * Объект методов журналирования
     * @var \XEAF\Rack\API\Interfaces\ILogger
     */
    private $_logger;

    /**
     * @inheritDoc
     */
    public function __construct() {
        $this->_logger = Logger::getInstance();
        $this->clear();
    }

    /**
     * @inheritDoc
     */
    public function clear(): void {
        $this->_phpMailer = $this->createPhpMailer();
    }

    /**
     * @inheritDoc
     */
    public function addAddress(string $email, string $name = ''): void {
        try {
            $this->_phpMailer->addAddress($email, $name);
            $this->_lastError = null;
        } catch (Throwable $me) {
            $this->_lastError = $me->getMessage();
            $this->_logger->exception($me);
        }
    }

    /**
     * @inheritDoc
     */
    public function addReplayTo(string $email, string $name = ''): void {
        try {
            $this->_phpMailer->addReplyTo($email, $name);
            $this->_lastError = null;
        } catch (Throwable $me) {
            $this->_lastError = $me->getMessage();
            $this->_logger->exception($me);
        }
    }

    /**
     * @inheritDoc
     */
    public function addCC(string $email, string $name): void {
        try {
            $this->_phpMailer->addCC($email, $name);
            $this->_lastError = null;
        } catch (Throwable $me) {
            $this->_lastError = $me->getMessage();
            $this->_logger->exception($me);
        }
    }

    /**
     * @inheritDoc
     */
    public function addBCC(string $email, string $name): void {
        try {
            $this->_phpMailer->addBCC($email, $name);
            $this->_lastError = null;
        } catch (Throwable $me) {
            $this->_lastError = $me->getMessage();
            $this->_logger->exception($me);
        }
    }

    /**
     * @inheritDoc
     */
    public function addAttachment(string $filePath, string $fileName = ''): void {
        try {
            $this->_phpMailer->addAttachment($filePath, $fileName);
            $this->_lastError = null;
        } catch (Throwable $me) {
            $this->_lastError = $me->getMessage();
            $this->_logger->exception($me);
        }
    }

    /**
     * @inheritDoc
     */
    public function setHtml(bool $isHTML): void {
        $this->_phpMailer->isHTML($isHTML);
    }

    /**
     * @inheritDoc
     */
    public function send(string $subject, string $body, string $altBody = ''): void {
        try {
            $this->_phpMailer->Subject = $subject;
            $this->_phpMailer->Body    = $body;
            $this->_phpMailer->AltBody = $altBody;
            $this->_phpMailer->send();
            $this->_lastError = null;
        } catch (Throwable $me) {
            $this->_lastError = $this->_phpMailer->ErrorInfo;
            Logger::getInstance()->exception($me);
        }
    }

    /**
     * @inheritDoc
     */
    public function getLastError(): ?string {
        return $this->_lastError;
    }

    /**
     * Создает объект службы отправки сообщений
     *
     * @return \PHPMailer\PHPMailer\PHPMailer|null
     */
    protected function createPhpMailer(): ?PHPMailer {
        try {
            $result = new PHPMailer();
            $config = MailerConfig::getInstance();
            if ($config->getSmtp()) {
                $result->isSMTP();
                $result->Host       = $config->getHost();
                $result->SMTPAuth   = $config->getAuth();
                $result->Username   = $config->getUserName();
                $result->Password   = $config->getPassword();
                $result->SMTPSecure = $config->getSecure();
                $result->Port       = $config->getPort();
            } else {
                $result->isMail();
            }
            $result->isHTML(true);
            $result->setFrom($config->getSendFrom(), $config->getSenderName());
        } catch (Throwable $me) {
            Logger::getInstance()->exception($me);
            $this->_lastError = $me->getMessage();
            $result           = null;
        }
        return $result;
    }

    /**
     * Возвращает единичный экземпляр объекта
     *
     * @return \XEAF\Rack\API\Interfaces\IMailer
     */
    public static function getInstance(): IMailer {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IMailer);
        return $result;
    }
}

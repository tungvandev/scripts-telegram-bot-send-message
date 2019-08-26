<?php
/**
 * Project scripts-telegram-bot-send-message
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 8/24/19
 * Time: 10:09
 */

namespace tungvandev\Example\TelegramBOT;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Curl\Curl;

/**
 * Class Telegram
 *
 * @package tungvandev\Example\TelegramBOT
 */
class Telegram implements TelegramInterface
{
    /** @var object|null Alias cURL class \Curl\Curl */
    private $request;
    /** @var object|null \Monolog\Logger */
    private $logger;
    /** @var array|null Mảng cấu hình thông tin BOT */
    private $config;
    /** @var string|int|null ID của chat room */
    private $chat_id;
    /** @var string|null|mixed Nội dung tin nhắn cần gửi đi */
    private $message;

    /**
     * Telegram constructor.
     *
     * @throws \ErrorException
     * @throws \Exception
     */
    public function __construct()
    {
        $this->request = new Curl();
        // create a log channel
        $this->logger = new Logger('telegram');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs', Logger::INFO));
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig($config = array())
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Hàm gán chat_id cho cuộc trò chuyện
     *
     * @param string $chat_id
     *
     * @return $this
     */
    public function setChatId($chat_id = '')
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    /**
     * Hàm gán nội dung cho cuộc trò chuyện
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message = '')
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Hàm gửi Message qua Telegram
     *
     * @return bool
     */
    public function sendMessage()
    {
        // Bắt lỗi
        // Ko tồn tại chat_id || message || không xác định được request
        if (empty($this->chat_id) || empty($this->message) || empty($this->config) || !isset($this->config['bot_token'])) {
            $errorContext = array(
                'chat_id' => $this->chat_id,
                'message' => $this->message,
                'config'  => $this->config
            );
            $this->logger->error('Không xác định được thông tin cần thiết', $errorContext);

            return FALSE;
        }
        // Xác đinh Endpoint gửi tin đi
        $endpoint = 'https://api.telegram.org/bot' . $this->config['bot_token'] . '/sendMessage';

        // Xác định Request gửi tin đi
        $params = [
            'chat_id' => $this->chat_id,
            'text'    => $this->message
        ];

        // Request tới Telegram
        $handle = $this->request->get($endpoint, $params);
        if (empty($handle)) {
            $this->logger->error('Không thực hiện được request CURL');

            // Không xác định được response, lỗi chứ còn gì nữa
            return FALSE;
        }
        if ($handle->error) {
            $this->logger->error('CURL Request is Error', $handle->errorMessage);

            return FALSE;
        }

        // Nếu không có lỗi gì, request response sẽ trả về 1 object
        // Example: stdClass (2) (
        //    public 'ok' -> boolean true
        //    public 'result' -> stdClass (5) (
        //        public 'message_id' -> integer 10
        //        public 'from' -> stdClass (4) (
        //            public 'id' -> integer 892069632
        //            public 'is_bot' -> boolean true
        //            public 'first_name' -> UTF-8 string (13) "Test Bot Chơi"
        //            public 'username' -> string (14) "ong_a_test_bot"
        //        )
        //        public 'chat' -> stdClass (5) (
        //            public 'id' -> integer 474860058
        //            public 'first_name' -> string (4) "Hung"
        //            public 'last_name' -> string (6) "Nguyen"
        //            public 'username' -> string (12) "nguyenanhung"
        //            public 'type' -> string (7) "private"
        //        )
        //        public 'date' -> integer 1566063717
        //        public 'text' -> UTF-8 string (29) "Test con bot chơi cái nhờ :))"
        //    )
        //)


        if (isset($handle->ok) && $handle->ok == TRUE) {
            // Send Message thành công, body trường ok == true
            return TRUE;
        }

        return FALSE;


    }
}

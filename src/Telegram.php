<?php

namespace tungvandev\Example\TelegramBOT;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Curl\Curl;

/**
 * Class Telegram
 *
 * @package tungvandev\Example\TelegramBOT
 */
class Telegram
{

    private $request; // Alias cURL
    private $logger; // Logger
    private $config; // Mảng cấu hình thông tin BOT

    private $chat_id; // ID chat room
    private $message; // Nội dung tin nhắn cần gửi đi

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
    public function setConfig($config = [])
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
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
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chat_id;
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
        if ($handle->error) {
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

        if (empty($handle)) {
            // Không xác định được response, lỗi chứ còn gì nữa
            return FALSE;
        }
        if (isset($handle->ok) && $handle->ok == TRUE) {
            // Send Message thành công, body trường ok == true
            return TRUE;
        }

        return FALSE;


    }
}

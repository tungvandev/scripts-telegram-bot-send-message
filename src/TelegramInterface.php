<?php
/**
 * Project scripts-telegram-bot-send-message
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 8/26/19
 * Time: 10:11
 */

namespace tungvandev\Example\TelegramBOT;

/**
 * Interface TelegramInterface
 *
 * @package   tungvandev\Example\TelegramBOT
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface TelegramInterface
{
    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig($config = array());

    /**
     * Hàm gán chat_id cho cuộc trò chuyện
     *
     * @param string $chat_id
     *
     * @return $this
     */
    public function setChatId($chat_id = '');

    /**
     * Hàm gán nội dung cho cuộc trò chuyện
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message = '');

    /**
     * @return mixed
     */
    public function getMessage();

    /**
     * Hàm gửi Message qua Telegram
     *
     * @return bool
     */
    public function sendMessage();
}

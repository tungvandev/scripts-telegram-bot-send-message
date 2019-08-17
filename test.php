<?php
require_once __DIR__ . '/vendor/autoload.php';

use tungvandev\Example\TelegramBOT\Telegram;

// Nạp config
$config = require_once __DIR__ . '/config.php';

// Khởi tạo class telegram
$telegram = new Telegram();

$telegram->setConfig($config)
         ->setChatId(474860058)
         ->setMessage('Test con bot chơi cái nhờ :))');

// Send Message

$result = $telegram->sendMessage();

d($result);

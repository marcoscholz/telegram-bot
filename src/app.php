<?php

use Marco\Telegram\Bot\MarcoBot;

require_once __DIR__ . '/../vendor/autoload.php';

$dir = !empty(Phar::running()) ? dirname(Phar::running(false)) : __DIR__ . '/..';

$options = [
    'loopIntervalSec' => 5,
    'maxLoops' => null,
    'configFile' => "$dir/marcoBotConfig.json",
    'replyToUnknownCommand' => true,
    'apiTimeout' => 5.0,
    'messageParseMode' => 'HTML'
];

$bot = new MarcoBot($options);
$bot->loop();

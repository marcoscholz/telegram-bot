<?php

use Marco\Telegram\Bot\MarcoBot;

require __DIR__ . "/../vendor/autoload.php";

$options = [
    'loopIntervalSec' => 5,
    'maxLoops' => null,
    'configFile' => realpath(__DIR__."/../config.json"),
    'replyToUnknownCommand' => true,
    'apiTimeout' => 5.0,
    'messageParseMode' => 'HTML'
];

$bot = new MarcoBot($options);
$bot->loop();

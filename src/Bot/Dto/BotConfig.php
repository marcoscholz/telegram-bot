<?php

namespace Marco\Telegram\Bot\Dto;

class BotConfig
{
    public int $loopIntervalSec = 5;
    public ?int $maxLoops = null;
    public string $token;
    public bool $replyToUnknownCommand = false;
    public float $apiTimeout = 3.0;
    public string $messageParseMode = 'HTML';

    public function __construct(array $options)
    {
        foreach ($options as $property => $value) {
            if ($property === 'configFile') {
                foreach (json_decode(file_get_contents($value)) as $fileProperty => $fileValue) {
                    $this->$fileProperty = $fileValue;
                }
                continue;
            }
            $this->$property = $value;
        }
    }
}
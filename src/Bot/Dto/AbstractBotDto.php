<?php

namespace Marco\Telegram\Bot\Dto;

class AbstractBotDto
{
    public function __construct(object $data)
    {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }
}

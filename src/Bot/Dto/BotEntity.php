<?php

namespace Marco\Telegram\Bot\Dto;

class BotEntity extends AbstractBotDto
{
    public int $offset;
    public int $length;
    public string $type;
}
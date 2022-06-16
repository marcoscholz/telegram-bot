<?php

namespace Marco\Telegram\Bot\Dto;

class BotChat extends AbstractBotDto
{
    public int $id;
    public ?string $first_name;
    public ?string $last_name;
    public string $username;
    public string $type;
}

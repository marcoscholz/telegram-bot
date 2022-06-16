<?php

namespace Marco\Telegram\Bot\Dto;

class BotFrom extends AbstractBotDto
{
    public int $id;
    public bool $is_bot = false;
    public ?string $first_name;
    public ?string $last_name;
    public string $username;
    public ?string $language_code;
}
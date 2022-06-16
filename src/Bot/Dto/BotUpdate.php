<?php

namespace Marco\Telegram\Bot\Dto;

use JetBrains\PhpStorm\Pure;

class BotUpdate
{
    public int $update_id;
    public BotMessage $message;
    public ?string $command = null;

    public function __construct(object $data)
    {
        $this->update_id = $data->update_id;
        $this->message = new BotMessage($data->message);

        $this->resolveCommands();
    }

    private function resolveCommands()
    {
        foreach ($this->message->entities as $entity) {
            if ($entity->type === 'bot_command') {
                $this->command = trim(substr($this->message->text, $entity->offset + 1, $entity->length));
            }
        }
    }
}
<?php

namespace Marco\Telegram\Bot\Dto;

class BotMessage
{
    public int $message_id;
    public BotFrom $from;
    public BotChat $chat;
    public int $date;
    public string $text;
    /** @var BotEntity[] */
    public array $entities = [];

    public function __construct(object $data)
    {
        $this->message_id = $data->message_id;
        $this->from = new BotFrom($data->from);
        $this->chat = new BotChat($data->chat);
        $this->date = $data->date;
        $this->text = $data->text;
        $data->entities = $data->entities ?? [];
        foreach ($data->entities as $entity) {
            $this->entities[] = new BotEntity($entity);
        }
    }
}

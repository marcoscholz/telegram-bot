<?php

namespace Marco\Telegram\Bot;

use DateTime;
use GuzzleHttp\Client;
use Marco\Telegram\Bot\Dto\BotConfig;
use Marco\Telegram\Bot\Dto\BotUpdate;

abstract class AbstractBot
{
    public const DATETIME_FORMAT = "Y-m-d\TH:i:s\Z";

    protected BotConfig $config;
    protected Client $client;
    protected int $updateOffset = 0;
    protected int $loopCount = 0;
    protected int $executedCommands = 0;
    protected DateTime $startedTs;
    protected array $userHistory = [];

    protected array $commands = [];

    public function __construct(array $options)
    {
        $this->startedTs = new DateTime();
        $this->config = new BotConfig($options);
        $this->client = new Client([
            'base_uri' => 'https://api.telegram.org/bot' . $this->config->token . '/',
            'timeout' => $this->config->apiTimeout,
        ]);
        $this->setCommands();
    }

    abstract public function loop();

    protected function setCommands()
    {
        // Could be extended to scope
        $commands = [];
        foreach ($this->commands as $command => $details) {
            $commands[] = ['command' => $command, 'description' => $details['description']];
        }
        $this->apiRequest("setMyCommands", ['commands' => $commands]);
    }

    /**
     * @return BotUpdate[]
     */
    protected function getUpdates(): array
    {
        $response = $this->apiRequest("getUpdates", ['offset' => $this->updateOffset]);

        $updates = [];

        foreach ($response->result as $update) {
            $update = new BotUpdate($update);
            $this->updateOffset = $update->update_id + 1;
            $updates[] = $update;
        }

        return $updates;
    }

    protected function sendMessage(int $chatId, string $message)
    {
        $this->apiRequest("sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }

    protected function apiRequest(string $uri, array $body = []): object
    {
        $response = $this->client->post($uri, [
            'body' => json_encode($body),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    protected function runCommand(BotUpdate $update)
    {
        if (array_key_exists($update->command, $this->commands)) {
            $user = $update->message->from->username;
            $this->userHistory[$user] = $this->userHistory[$user] ?? 0;
            $this->userHistory[$user]++;
            $this->executedCommands++;
            $method = $this->commands[$update->command]['method'];
            $this->$method($update);
        } else {
            if ($this->config->replyToUnknownCommand) {
                $this->sendMessage($update->message->chat->id, "Sorry, I don't know that command");
            }
        }
    }
}
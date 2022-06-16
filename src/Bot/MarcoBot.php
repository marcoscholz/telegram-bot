<?php

namespace Marco\Telegram\Bot;

use DateTime;
use Marco\Telegram\Bot\Dto\BotUpdate;
use Marco\Telegram\CryptoCom\SpotApi;

class MarcoBot extends AbstractBot
{
    protected array $commands = [
        'btc' => ['method' => 'getPriceForTicker', 'description' => 'get current Bitcoin Price'],
        'eth' => ['method' => 'getPriceForTicker', 'description' => 'get current Ethereum Price'],
        'sol' => ['method' => 'getPriceForTicker', 'description' => 'get current Solana Price'],
        'overview' => ['method' => 'getTickerOverview', 'description' => 'get current Solana Price'],
        'stats' => ['method' => 'botStats', 'description' => 'get infos about the bot since start']
    ];

    protected ?SpotApi $api = null;


    public function loop(): void
    {
        $this->api = $this->api ?? new SpotApi();

        while ($this->loopCount < $this->config->maxLoops || $this->config->maxLoops === null) {
            foreach ($this->getUpdates() as $update) {
                if ($update->command !== null) {
                    $this->runCommand($update);
                }
            }
            sleep($this->config->loopIntervalSec);
            $this->loopCount++;
        }
    }

    protected function getPriceForTicker(BotUpdate $update)
    {
        $map = [
            'btc' => 'BTC_USDT',
            'eth' => 'ETH_USDT',
            'sol' => 'SOL_USDT'
        ];
        $this->api = $this->api ?? new SpotApi();
        $ticker = $this->api->getTicker($map[$update->command]);
        $changePercent = round($ticker->change / ($ticker->lastTrade + $ticker->change) * 100, 2);
        $ts = DateTime::createFromFormat('U', round($ticker->ts / 1000, 0))->format(self::DATETIME_FORMAT);
        $msg = <<<HTML
        <b>$ticker->instrument_name</b><code>
        $ticker->lastTrade
        $ticker->change ($changePercent%)
        </code>        
        <i>($ts)</i>
        HTML;

        $this->sendMessage($update->message->chat->id, $msg);
    }

    protected function getTickerOverview(BotUpdate $update)
    {
        $map = [
            'BTC' => 'BTC_USDT',
            'ETH' => 'ETH_USDT',
            'SOL' => 'SOL_USDT',
            'ADA' => 'ADA_USDT',
            'XRP' => 'XRP_USDT',
        ];

        $msg = '<code>';
        foreach ($map as $key => $symbol) {
            $ticker = $this->api->getTicker($symbol);
            $changePercent = round($ticker->change / ($ticker->lastTrade + $ticker->change) * 100, 2);
            $msg .= $key . sprintf("%12.2F", $ticker->lastTrade) . sprintf("%+8.2f", $changePercent) . "%\n";
        }
        $msg .= '</code>';

        $this->sendMessage($update->message->chat->id, $msg);
    }

    protected function botStats(BotUpdate $update)
    {
        $ts = $this->startedTs->format(self::DATETIME_FORMAT);
        $now = new DateTime();
        $interval = $now->diff($this->startedTs);

        $runtime = sprintf(
            '%d:%02d:%02d',
            ($interval->d * 24) + $interval->h,
            $interval->i,
            $interval->s
        );

        $history = '';
        $config = $this->config;
        arsort($this->userHistory);
        foreach ($this->userHistory as $user => $count) {
            $history .= "-> <i>$user</i> $count" . PHP_EOL;
        }
        $reply = $config->replyToUnknownCommand ? 'true' : 'false';
        $loopPercent = $config->maxLoops === null ? null : ("(" . round($this->loopCount / $config->maxLoops * 100, 1) . " %)");

        $msg = <<<HTML
        <code><b>[Started]</b>   $ts</code>
        <code><b>[Runtime]</b>   $runtime</code>
        <code><b>[Loops]</b>     $this->loopCount $loopPercent</code>
        <code><b>[Commands]</b>  $this->executedCommands</code>
        
        <code><b>[User History]</b>
        $history</code>        
        <code><b>[Config]</b>
         loopIntervalSec:  $config->loopIntervalSec
         maxLoops:         $config->maxLoops $loopPercent         
         apiTimeout:       $config->apiTimeout
         messageParseMode: $config->messageParseMode
         replyToUnknownCommand: <i>$reply</i>
        </code>
        HTML;
        $this->sendMessage($update->message->chat->id, $msg);
    }
}

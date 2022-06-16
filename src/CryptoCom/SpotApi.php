<?php

namespace Marco\Telegram\CryptoCom;

use GuzzleHttp\Client;
use Marco\Telegram\Bot\Dto\BotConfig;
use Marco\Telegram\CryptoCom\Dto\Ticker;

class SpotApi
{
    public const BASE_URL = "https://api.crypto.com/v2/";
    public const MAX_TICKER_AGE_SEC = 10;

    private Client $client;
    /** @var Ticker[] */
    private array $tickerData = [];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => 5.0,
        ]);
        $this->refreshTicker();
    }

    private function refreshTicker()
    {
        $response = $this->client->get('public/get-ticker');
        $json = json_decode($response->getBody()->getContents());

        foreach ($json->result->data as $i) {
            $i = new Ticker($i);
            $this->tickerData[$i->instrument_name] = $i;
        }
    }

    public function getTicker(string $symbol)
    {
        $ticker = $this->tickerData[$symbol];
        $ageSec = time() - (int)($ticker->ts / 1000);
        if ($ageSec > self::MAX_TICKER_AGE_SEC) {
            $this->refreshTicker();
        }

        return $this->tickerData[$symbol];
    }
}
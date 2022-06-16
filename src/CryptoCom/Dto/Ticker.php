<?php

namespace Marco\Telegram\CryptoCom\Dto;

class Ticker
{
    public string $instrument_name;
    public ?float $bid;
    public ?float $ask;
    public ?float $lastTrade;
    public int $ts;
    public float $volume24h;
    public float $high24h;
    public float $low24h;
    public float $change;

    public function __construct(object $data)
    {
        $this->instrument_name = $data->i;
        $this->bid = $data->b;
        $this->ask = $data->k;
        $this->lastTrade = $data->a;
        $this->ts = $data->t;
        $this->volume24h = $data->v;
        $this->high24h = $data->h;
        $this->low24h = $data->l;
        $this->change = $data->c;
    }
}

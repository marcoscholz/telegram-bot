[![ubuntu](https://img.shields.io/badge/Ubuntu-20.04_LTS_(focal_fossa)-brightgreen.svg)](http://releases.ubuntu.com/20.04/)
[![PHP-Version](https://img.shields.io/badge/php-8.1-blue.svg)](https://packages.ubuntu.com/eoan/libapache2-mod-php8.1)

# Usage
Install and compile (using box.json) with [Composer](https://getcomposer.org/)

    composer install

Put the bots token in a file `config.json` in the repositories root folder

    {
      "token": "mytoken"
    }

Run the Bot with 

    php src/app.php


# Commands
- `\btc` returns Bitcoin Price Details
- `\eth` returns Ethereum Price Details
- `\sol` returns Solana Price Details
- `\overview` returns an overview for BTC, ETH, SOL, ADA, XRP
- `\stats` shows statistics about the bot

# Changelog

## [1.0.0] - 2022-06-17 00:00 UTC
### Initial
- `.gitignore`
- `composer.json`
    - guzzlehttp/guzzle dependency
- `README.md`
### Added
- `CryptoCom/SpotApi` class
- `Bot/AbstractBot` class
- `Bot/MarcoBot` class

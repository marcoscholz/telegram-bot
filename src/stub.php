#!/usr/bin/env php
<?php

Phar::mapPhar('marcoBot.phar');

require_once 'phar://marcoBot.phar/src/app.php';

__HALT_COMPILER();

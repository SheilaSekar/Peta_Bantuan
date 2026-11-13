<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

return ['default' => 'null', 'deprecations' => ['channel' => 'null', 'trace' => false], 'channels' => ['null' => ['driver' => 'monolog', 'handler' => NullHandler::class], 'stderr' => ['driver' => 'monolog', 'handler' => StreamHandler::class, 'with' => ['stream' => 'php://stderr']]]];

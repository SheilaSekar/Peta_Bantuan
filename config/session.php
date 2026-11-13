<?php

return ['driver' => 'array', 'lifetime' => 0, 'expire_on_close' => true, 'encrypt' => false, 'files' => storage_path('framework/sessions'), 'connection' => null, 'table' => 'sessions', 'store' => null, 'lottery' => [0, 100], 'cookie' => 'unused', 'path' => '/', 'domain' => null, 'secure' => null, 'http_only' => true, 'same_site' => 'lax', 'partitioned' => false];

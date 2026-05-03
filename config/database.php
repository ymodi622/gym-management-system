<?php

declare(strict_types=1);

require_once __DIR__ . '/app.php';
require_once __DIR__ . '/../classes/Database.php';

return \App\Database::getInstance();

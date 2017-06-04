<?php

call_user_func(function() {
    foreach ([
                 dirname(__DIR__) . '/vendor/autoload.php', // composer
                 dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/autoload.php', // composer dependency
                 dirname(dirname(dirname(__DIR__))) . '/autoload.php', // fallback
             ] as $file) {
        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            require_once $file;
            return;
        }
    }
    die('file autoload.php not found' . PHP_EOL);
});


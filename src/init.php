<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

\Brontosaurus\Config::clean();
\Brontosaurus\Config::loadFromFile(__DIR__."/default_config.yml");

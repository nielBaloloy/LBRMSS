<?php

class SessionManager {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }
    }

    public function getSession($key) {
        return $_SESSION[$key] ?? null; // Return value if exists, otherwise null
    }
}



?>
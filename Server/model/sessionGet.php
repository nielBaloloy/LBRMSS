<?php
class SessionModel {
    
        public function __construct() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }
    
        // Set session data (auto-encodes arrays/objects)
        public function setSessionData($key, $value) {
            $_SESSION[$key] = is_array($value) || is_object($value) ? json_encode($value) : $value;
        }
    
        // Get session data (auto-decodes JSON)
        public function getSessionData($key) {
            if (!isset($_SESSION[$key])) return null;
    
            $data = $_SESSION[$key];
            $decoded = json_decode($data, true);
            return $decoded ?? $data; // Return decoded JSON if valid, otherwise raw value
        }
    
        // Remove session key
        public function removeSessionData($key) {
            unset($_SESSION[$key]);
        }
    
        // Destroy entire session
        public function destroySession() {
            session_destroy();
            $_SESSION = [];
        }
    
    
}

// Example usage:
// $session = new SessionModel();
// $session->setSessionData('user_id', 1);
// echo $session->getSessionData('user_id');

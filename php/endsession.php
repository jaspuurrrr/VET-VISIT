<?php
// Start or resume the session
session_start();

// Check if a session is active
if (session_status() === PHP_SESSION_ACTIVE) {
    // End the current session
    session_destroy();

    // Optionally, unset all session variables
    $_SESSION = array();
}

exit();
?>
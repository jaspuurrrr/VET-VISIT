<?php
session_start();
$error = "Session expired.";

// Check if the data is available in the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    echo json_encode(['email' => $email]);
} else {
    // If no data is found, return an empty response
    echo json_encode(['error' => $error]);
}
?>
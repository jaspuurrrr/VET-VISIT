<?php

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// login.php
$databaseFile = "../assets/db/dbtest.db";

try {
    $pdo = new PDO("sqlite:$databaseFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Additional configurations if needed
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('log.txt', print_r($data, true), FILE_APPEND);

$pfp = base64_decode($data['pfp']);
$name = $data['name'];
$contact = $data['contact'];

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'image' file or 'defaultImage' is set in the POST request
    if (isset($data['pfp']) AND isset($data['name']) AND isset($data['contact'])) {

        //$pfp = $_FILES['pfp']['tmp_name'];

        //$profilepic = file_get_contents($pfp);
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        $acctype = $_SESSION['acctype'];

        // Get other user data
        //$name = isset($_POST['name']) ? $_POST['name'] : 'DefaultName';
        //$contact = isset($_POST['contact']) ? $_POST['contact'] : '0000000000';

        // Insert the data into the database
        $stmt = $pdo->prepare('INSERT INTO clients (email, profile_picture, name, contact_number) VALUES (?, ?, ?, ?)');
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $pfp, PDO::PARAM_LOB);
        $stmt->bindParam(3, $name, PDO::PARAM_STR);
        $stmt->bindParam(4, $contact, PDO::PARAM_STR);
        $stmt->execute();

        $stmt2 = $pdo->prepare('INSERT INTO logintb (email, password, acctype) VALUES (?, ?, ?)');
        $stmt2->bindParam(1, $email, PDO::PARAM_STR);
        $stmt2->bindParam(2, $password, PDO::PARAM_STR);
        $stmt2->bindParam(3, $acctype, PDO::PARAM_STR);
        $stmt2->execute();

        // Respond with success message
        echo json_encode(['success' => true, 'message' => 'User data uploaded successfully']);
    } else {
        // Respond with error message for incomplete data
        echo json_encode(['success' => false, 'message' => 'Incomplete data received']);
    }
} else {
    // Respond with error message for invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}


// Close the database connection
$pdo = null;
?>
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
//file_put_contents('log.txt', print_r($data, true), FILE_APPEND);

$pfp = base64_decode($data['pfp']);
$name = $data['name'];
$type = $data['type'];
$age = $data['age'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check data contents
    if (isset($data['pfp']) AND isset($data['name']) AND isset($data['type']) AND isset($data['age'])) {

        $email = $_SESSION['email'];

        $count = $pdo->prepare('SELECT petno FROM pets ORDER BY petno DESC LIMIT 1');
        $count->execute();
        $list = $count->fetch(PDO::FETCH_ASSOC);
        $currentPetNo = 0;

        if ($list) {
            $currentPetNo = $list['petno'] + 1;
        } else {
            $currentPetNo = 1;
        }

        // Insert the data into the database
        $stmt = $pdo->prepare('INSERT INTO pets (owner, photo, name, type, age, petno) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $pfp, PDO::PARAM_LOB);
        $stmt->bindParam(3, $name, PDO::PARAM_STR);
        $stmt->bindParam(4, $type, PDO::PARAM_STR);
        $stmt->bindParam(5, $age, PDO::PARAM_INT);
        $stmt->bindParam(6, $currentPetNo, PDO::PARAM_INT);
        $stmt->execute();

        // Respond with success message
        echo json_encode(['success' => true, 'message' => 'Pet added successfully.']);
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
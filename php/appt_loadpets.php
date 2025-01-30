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

$email = $_SESSION['email'];

$stmt = $pdo->prepare('SELECT * FROM pets WHERE owner = :email');
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$petlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($petlist){
    $jsonObjects = [];
    foreach ($petlist as $pet) {
        $jsonObjects[] = json_encode(['petid' => $pet['petid'], 'petno' => $pet['petno'], 'pfp' => base64_encode($pet['photo']), 'name' => $pet['name'], 'type' => $pet['type'], 'age' => $pet['age']]);
    }
    echo json_encode($jsonObjects);
} else {
    echo json_encode(['nopets' => true]);
}

// Close the database connection
$pdo = null;
?>
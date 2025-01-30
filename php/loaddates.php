<?php

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$data = json_decode(file_get_contents('php://input'), true);

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

$stmt = $pdo->prepare('SELECT * FROM appointments WHERE date = :date');
$stmt->bindParam(':date', $data['date'], PDO::PARAM_STR);
$stmt->execute();
$datelist = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($datelist){
    $jsonObjects = [];
    foreach ($datelist as $date) {
        $jsonObjects[] = json_encode(['email' => $date['email'], 'id' => $date['id'], 'date' => $date['date'], 'pet' => $date['pet'], 'time' => $date['time'], 'vet' => $date['vet'], 'status' => $date['status'], 'remarks' => $date['remarks'], 'des' => $date['des']]);
    }
    echo json_encode($jsonObjects);
} else {
    echo json_encode([]);
}

// Close the database connection
$pdo = null;
?>
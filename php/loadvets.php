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

$stmt = $pdo->prepare('SELECT id, name FROM vets');
$stmt->execute();
$vetlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($vetlist){
    $jsonObjects = [];
    foreach ($vetlist as $vet) {
        $jsonObjects[] = json_encode(['vetid' => $vet['id'], 'name' => $vet['name']]);
    }
    echo json_encode($jsonObjects);
} else {
    echo json_encode(['novets' => true]);
}

// Close the database connection
$pdo = null;
?>
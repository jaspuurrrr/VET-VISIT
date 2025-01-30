<?php

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// login.php
$databaseFile = "../assets/db/dbtest.db";
$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO("sqlite:$databaseFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Additional configurations if needed
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$email = $_SESSION['email'];

$id = $data['referId'];
$status = $data['status'];
$sql = "UPDATE appointments
SET status = :status
WHERE id = :id";
$stmt = $pdo->prepare($sql);
//$str = 'testingaccount@account.com';
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();

// Close the database connection
$pdo = null;
?>
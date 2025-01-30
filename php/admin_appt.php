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
/*
$sql = "SELECT id FROM vets where email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetch(PDO::FETCH_ASSOC);
$vetId = $results['id'];
*/

$sql = "SELECT 
    a.date AS adate,
    a.time AS atime,
    c.name,
    a.vet,
    a.status,
    p.refno,
    p.accno,
    p.time AS ptime,
    receipt,
    a.id
FROM 
    clients c
JOIN 
    appointments a ON c.email = a.email
JOIN 
    payments p ON c.email = p.email AND a.id = p.id
WHERE 
    a.status = 'Pending' ";
$stmt = $pdo->prepare($sql);
//$str = 'testingaccount@account.com';
//$stmt->bindParam(':vet', $vetId, PDO::PARAM_STR);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($clients){
    $jsonObjects = [];
    foreach ($clients as $user) {
        $jsonObjects[] = json_encode(['adate' => $user['adate'], 'atime' => $user['atime'], 'name' => $user['name'], 'vet' => $user['vet'], 'status' => $user['status'],'refno' => $user['refno'],'accno' => $user['accno'], 'ptime' => $user['ptime'], 'receipt' => base64_encode($user['receipt']), 'id' => $user['id']]);
    }
    echo json_encode($jsonObjects);
} else {
    echo json_encode(['noclients' => true]);
}

// Close the database connection
$pdo = null;
?>
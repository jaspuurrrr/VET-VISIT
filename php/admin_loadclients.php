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

$sql = "SELECT DISTINCT c.profile_picture, c.name, c.email, c.contact_number, a.date, a.time, a.id, a.status, a.pet
              FROM clients c
              INNER JOIN appointments a ON c.email = a.email
              ORDER BY
              substr(a.date,7)||substr(a.date,1,2) ||substr(a.date,4,2) DESC,
              SUBSTR(a.time, INSTR(a.time, 'AM -') * (INSTR(a.time, 'AM -') > 0) + INSTR(a.time, 'PM -') * (INSTR(a.time, 'PM -') > 0), 2) DESC,
              CAST(substr(a.time, 1, instr(a.time, ' ') - 3) AS INTEGER) < 12 DESC,
              CAST(substr(a.time, 1, instr(a.time, ' ') - 3) AS INTEGER) DESC";
$stmt = $pdo->prepare($sql);
//$str = 'testingaccount@account.com';
//$stmt->bindParam(':vet', $vetId, PDO::PARAM_STR);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($clients){
    $jsonObjects = [];
    foreach ($clients as $user) {
        $jsonObjects[] = json_encode(['email' => $user['email'], 'name' => $user['name'], 'pfp' => base64_encode($user['profile_picture']), 'contact' => $user['contact_number'], 'date' => $user['date'],'time' => $user['time'],'id' => $user['id'],'status' => $user['status'], 'pet' => $user['pet']]);
    }
    echo json_encode($jsonObjects);
} else {
    echo json_encode(['noclients' => true]);
}

// Close the database connection
$pdo = null;
?>
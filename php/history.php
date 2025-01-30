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

$sql = "SELECT * FROM appointments WHERE email = :email ORDER BY
              substr(date,7)||substr(date,1,2) ||substr(date,4,2) DESC,
              SUBSTR(time, INSTR(time, 'AM -') * (INSTR(time, 'AM -') > 0) + INSTR(time, 'PM -') * (INSTR(time, 'PM -') > 0), 2) DESC,
              CAST(substr(time, 1, instr(time, ' ') - 3) AS INTEGER) < 12 DESC,
              CAST(substr(time, 1, instr(time, ' ') - 3) AS INTEGER) DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$apptlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($apptlist){
    $jsonObjects = [];
    foreach ($apptlist as $appt) {
        $jsonObjects[] = json_encode($appt);
    }
    echo json_encode($jsonObjects);
} else {
    echo json_encode(['noappt' => true]);
}

// Close the database connection
$pdo = null;
?>
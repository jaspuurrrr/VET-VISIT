<?php
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
$email = $data['email'];
$query = $pdo->prepare("DELETE FROM logintb WHERE email = :email");
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();

// Close the database connection
$pdo = null;
?>
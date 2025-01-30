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


session_start();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['petno'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("DELETE FROM pets WHERE petno = :petno");
    $checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    $checkQuery->execute();
    echo json_encode(['message' => 'Successfully deleted.']);
}

// Close the database connection
$pdo = null;
?>
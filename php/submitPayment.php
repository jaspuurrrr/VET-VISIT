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
$email = $_SESSION['email'];
$data = json_decode(file_get_contents('php://input'), true);
$sender = $data['sender'];
$id = $data['id'];
$accno = $data['accno'];
$refno = $data['refno'];
$time = $data['time'];
$receipt = base64_decode($data['receipt']);

$checkQuery = $pdo->prepare("INSERT INTO payments (email, id, sender, accno, refno, time, receipt) VALUES (:email, :id, :sender, :accno, :refno, :time, :receipt)");
$checkQuery->bindParam(':email', $email, PDO::PARAM_STR);
$checkQuery->bindParam(':id', $id, PDO::PARAM_STR);
$checkQuery->bindParam(':sender', $sender, PDO::PARAM_STR);
$checkQuery->bindParam(':accno', $accno, PDO::PARAM_INT);
$checkQuery->bindParam(':refno', $refno, PDO::PARAM_STR);
$checkQuery->bindParam(':time', $time, PDO::PARAM_STR);
$checkQuery->bindParam(':receipt', $receipt, PDO::PARAM_LOB);
$checkQuery->execute();

echo json_decode(['message' => 'done']);

// Close the database connection
$pdo = null;
?>
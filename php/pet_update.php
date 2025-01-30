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

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['name']) && isset($data['petno'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("UPDATE pets SET name = :newName WHERE petno = :petno");
    $checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    $checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    $checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE history SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE appointments SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    echo json_encode(['message' => 'Successfully updated.']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['type']) && isset($data['petno'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("UPDATE pets SET type = :newType WHERE petno = :petno");
    $checkQuery->bindParam(':newType', $data['type'], PDO::PARAM_STR);
    $checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    $checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE history SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE appointments SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    echo json_encode(['message' => 'Successfully updated.']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['age']) && isset($data['petno'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("UPDATE pets SET age = :newAge WHERE petno = :petno");
    $checkQuery->bindParam(':newAge', $data['age'], PDO::PARAM_INT);
    $checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    $checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE history SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE appointments SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    echo json_encode(['message' => 'Successfully updated.']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['photo']) && isset($data['petno'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("UPDATE pets SET photo = :newPhoto WHERE petno = :petno");
    $checkQuery->bindParam(':newPhoto', base64_decode($data['photo']), PDO::PARAM_LOB);
    $checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    $checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE history SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    //$checkQuery = $pdo->prepare("UPDATE appointments SET name = :newName WHERE petno = :petno");
    //$checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    //$checkQuery->bindParam(':petno', $data['petno'], PDO::PARAM_STR);
    //$checkQuery->execute();

    echo json_encode(['message' => 'Successfully updated.']);
}

// Close the database connection
$pdo = null;
?>
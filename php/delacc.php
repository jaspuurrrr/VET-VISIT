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

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['password'])) {
    //echo json_encode(['message' => 'OKAY']);
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("SELECT * FROM logintb WHERE email = :clientEmail AND password = :password");
    $checkQuery->bindParam(':clientEmail', $email, PDO::PARAM_STR);
    $checkQuery->bindParam(':password', $data['password'], PDO::PARAM_STR);
    $checkQuery->execute();
    $list = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if($list){
        $checkQuery1 = $pdo->prepare("DELETE FROM logintb WHERE email = :email");
        $checkQuery1->bindParam(':email', $email, PDO::PARAM_STR);
        $checkQuery1->execute();

        $checkQuery2 = $pdo->prepare("DELETE FROM clients WHERE email = :email");
        $checkQuery2->bindParam(':email', $email, PDO::PARAM_STR);
        $checkQuery2->execute();

        //$checkQuery2 = $pdo->prepare("DELETE FROM pets WHERE email = :email");
        //$checkQuery2->bindParam(':email', $email, PDO::PARAM_STR);
        //$checkQuery2->execute();

        //$checkQuery2 = $pdo->prepare("DELETE FROM history WHERE email = :email");
        //$checkQuery2->bindParam(':email', $email, PDO::PARAM_STR);
        //$checkQuery2->execute();

        //$checkQuery2 = $pdo->prepare("DELETE FROM appointments WHERE email = :email");
        //$checkQuery2->bindParam(':email', $email, PDO::PARAM_STR);
        //$checkQuery2->execute();

        echo json_encode(['error' => false]);
    } else {
        echo json_encode(['error' => true]);
    }
}

// Close the database connection
$pdo = null;
?>
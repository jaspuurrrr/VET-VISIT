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

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['name'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("UPDATE clients SET name = :newName WHERE email = :clientEmail");
    $checkQuery->bindParam(':newName', $data['name'], PDO::PARAM_STR);
    $checkQuery->bindParam(':clientEmail', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    echo json_encode(['message' => 'Successfully updated.']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['email'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("SELECT * FROM clients WHERE email = :email");
    $checkQuery->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $checkQuery->execute();
    $list = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if ($list){
        echo json_encode(['message' => 'Already used. Please choose another.']);
    } else {
        $checkQuery2 = $pdo->prepare("UPDATE clients SET email = :newEmail WHERE email = :clientEmail");
        $checkQuery2->bindParam(':newEmail', $data['email'], PDO::PARAM_STR);
        $checkQuery2->bindParam(':clientEmail', $email, PDO::PARAM_STR);
        $checkQuery2->execute();

        $checkQuery3 = $pdo->prepare("UPDATE logintb SET email = :newEmail WHERE email = :clientEmail");
        $checkQuery3->bindParam(':newEmail', $data['email'], PDO::PARAM_STR);
        $checkQuery3->bindParam(':clientEmail', $email, PDO::PARAM_STR);
        $checkQuery3->execute();

        //$checkQuery4 = $pdo->prepare("UPDATE pets SET email = :newEmail WHERE email = :clientEmail");
        //$checkQuery4->bindParam(':newEmail', $data['email'], PDO::PARAM_STR);
        //$checkQuery4->bindParam(':clientEmail', $email, PDO::PARAM_STR);
        //$checkQuery4->execute();

        //$checkQuery5 = $pdo->prepare("UPDATE history SET email = :newEmail WHERE email = :clientEmail");
        //$checkQuery5->bindParam(':newEmail', $data['email'], PDO::PARAM_STR);
        //$checkQuery5->bindParam(':clientEmail', $email, PDO::PARAM_STR);
        //$checkQuery5->execute();

        //$checkQuery6 = $pdo->prepare("UPDATE appointments SET email = :newEmail WHERE email = :clientEmail");
        //$checkQuery6->bindParam(':newEmail', $data['email'], PDO::PARAM_STR);
        //$checkQuery6->bindParam(':clientEmail', $email, PDO::PARAM_STR);
        //$checkQuery6->execute();

        $_SESSION['email'] = $data['email'];
        echo json_encode(['message' => 'Successfully updated.']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['contact'])) {
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("UPDATE clients SET contact_number = :newContact WHERE email = :clientEmail");
    $checkQuery->bindParam(':newContact', $data['contact'], PDO::PARAM_STR);
    $checkQuery->bindParam(':clientEmail', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    echo json_encode(['message' => 'Successfully updated.']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['pfp'])) {
    $email = $_SESSION['email'];
    $pfp = base64_decode($data['pfp']);

    $checkQuery = $pdo->prepare("UPDATE clients SET profile_picture = :newpfp WHERE email = :clientEmail");
    $checkQuery->bindParam(':newpfp', $pfp, PDO::PARAM_STR);
    $checkQuery->bindParam(':clientEmail', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    echo json_encode(['message' => 'Successfully updated.']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['oldpassword']) && isset($data['newpassword'])) {
    //echo json_encode(['message' => 'OKAY']);
    $email = $_SESSION['email'];

    $checkQuery = $pdo->prepare("SELECT * FROM logintb WHERE email = :clientEmail AND password = :oldpassword");
    $checkQuery->bindParam(':clientEmail', $email, PDO::PARAM_STR);
    $checkQuery->bindParam(':oldpassword', $data['oldpassword'], PDO::PARAM_STR);
    $checkQuery->execute();
    $list = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if($list){
        $checkQuery1 = $pdo->prepare("UPDATE logintb SET password = :newpassword WHERE email = :clientEmail");
        $checkQuery1->bindParam(':newpassword', $data['newpassword'], PDO::PARAM_STR);
        $checkQuery1->bindParam(':clientEmail', $email, PDO::PARAM_STR);
        $checkQuery1->execute();

        echo json_encode(['message' => 'Successfully updated.']);
    } else {
        echo json_encode(['message' => 'Old password is incorrect.']);
    }
}

// Close the database connection
$pdo = null;
?>
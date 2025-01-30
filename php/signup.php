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

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['email']) && isset($data['password']) && isset($data['acctype'])) {
    $email = $data['email'];
    $password = $data['password'];
    $acctype = $data['acctype'];

    session_start();
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    $_SESSION['acctype'] = $acctype;

    // Return a success message
    echo json_encode(['error' => 0]);
    exit();
}

else if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['email'])) {
    $email = $data['email'];

    // Perform a query to check if the email already exists in the database
    $checkQuery = $pdo->prepare("SELECT * FROM logintb WHERE email = :email");
    $checkQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    $existingUser = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        // Return an error message if the email is already in use
        echo json_encode(['error' => 'Email is already in use']);
        exit();
    }
    else{
        echo json_encode(['error' => 0]);
    }
}

// Close the database connection
$pdo = null;
?>
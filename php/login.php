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

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

     // Perform a query to check if the user exists in the database
    $query = $pdo->prepare("SELECT * FROM logintb WHERE email = :email AND password = :password");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();

    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Redirect to main.html if the user exists
        //header("Location: main.html");
        echo json_encode(['acctype' => $user['acctype']]);

        session_start();
        $_SESSION['email'] = $email;

        $pdo = null;
        exit();
        
    } else {

        $query = $pdo->prepare("SELECT * FROM logintb WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['error' => 'Incorrect password.']);
            $pdo = null;
            exit();

        } else {

            // Display an error message if the user doesn't exist
            echo json_encode(['error' => 'Account does not exist.']);
            $pdo = null;
            exit();
        }
    }
}

// Close the database connection
$pdo = null;
?>
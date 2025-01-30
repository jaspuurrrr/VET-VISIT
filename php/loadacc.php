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

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data && isset($data['email'])) {
    $email = $data['email'];
    //$password = $data['password'];
    //$acctype = $data['acctype'];

    // Perform a query to check if the email already exists in the database
    $checkQuery = $pdo->prepare("SELECT * FROM clients WHERE email = :email");
    $checkQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $checkQuery->execute();

    $profiledata = $checkQuery->fetch(PDO::FETCH_ASSOC);

    file_put_contents('profilelog.txt', print_r($profiledata, true), FILE_APPEND);

    $blobdata = $profiledata['profile_picture'];
    $pfp = base64_encode($blobdata);
    //$email = $profiledata['email'];
    $pname = $profiledata['name'];
    $contact = $profiledata['contact_number'];

    echo json_encode(['name' => $pname, 'email' => $email, 'contact' => $contact, 'pfp' => $pfp]);


    //if ($existingUser) {
        // Return an error message if the email is already in use
        //header("Content-Type: application/json");
        //echo json_encode(['error' => 'Email is already in use']);
        //exit();
    //}

    // Return a success message
    //header("Content-Type: application/json");
    //echo json_encode(['error' => 0]);
    //exit();
}


// Close the database connection
$pdo = null;
?>
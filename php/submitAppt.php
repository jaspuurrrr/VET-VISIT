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


$date = $_SESSION['date'];
$time = $_SESSION['time'];
$vet = $_SESSION['vet'];
$des = $_SESSION['des'];
$pet = $_SESSION['pet']; 
$status = $_SESSION['status'];
$remarks = $_SESSION['remarks'];


//$data = json_decode(file_get_contents('php://input'), true);

    $email = $_SESSION['email'];
/**
    if($vet == 'Auto-assign.'){
        $sql = "SELECT vet FROM appointments ORDER BY date ASC";
        $checkQuery = $pdo->prepare($sql);
        $checkQuery->execute();
        $result1 = $checkQuery->fetch(PDO::FETCH_ASSOC);
        if ($result1 == 0){
            $sql = "SELECT id FROM vets ORDER BY name ASC";
            $checkQuery = $pdo->prepare($sql);
            $checkQuery->execute();
            $result1 = $checkQuery->fetch(PDO::FETCH_ASSOC);
        }
        $vet = strval($result1['vet']);
    }**/

    $sql = "
        SELECT COUNT(*) AS count_entries
        FROM appointments
        WHERE date = :dateToCount AND time = :timeToCount
    ";
    $checkQuery = $pdo->prepare($sql);
    $checkQuery->bindParam(':dateToCount', $date, PDO::PARAM_STR);
    $checkQuery->bindParam(':timeToCount', $time, PDO::PARAM_STR);
    $checkQuery->execute();
    $result2 = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if ($result2['count_entries'] >= 3) {
        echo json_encode(['message' => 'Slot is full. Please select another time slot.']);
    } else{
        $sql = "
            SELECT COUNT(*) AS count_entries
            FROM appointments";
        $checkQuery = $pdo->prepare($sql);
        //$checkQuery->bindParam(':dateToCount', $date, PDO::PARAM_STR);
        $checkQuery->execute();
        $result3 = $checkQuery->fetch(PDO::FETCH_ASSOC);

        $dateToday = date("Ymd");
        $integerX = $result3['count_entries'];
        $concat = $dateToday . sprintf('%02d', $integerX);

        // Convert the concatenated number to hex
        $id = dechex($concat);
        $sql = "
            INSERT INTO appointments
            (email, id, date, pet, time, vet, status, remarks, des)
            VALUES (:email, :id, :date, :pet, :time, :vet, :status, :remarks, :des)";
        $checkQuery = $pdo->prepare($sql);
        $checkQuery->bindParam(':email', $email, PDO::PARAM_STR);
        $checkQuery->bindParam(':id', $id, PDO::PARAM_STR);
        $checkQuery->bindParam(':date', $date, PDO::PARAM_STR);
        $checkQuery->bindParam(':pet', $pet, PDO::PARAM_STR);
        $checkQuery->bindParam(':time', $time, PDO::PARAM_STR);
        $checkQuery->bindParam(':vet', $vet, PDO::PARAM_STR);
        $checkQuery->bindParam(':status', $status, PDO::PARAM_STR);
        $checkQuery->bindParam(':remarks', $remarks, PDO::PARAM_STR);
        $checkQuery->bindParam(':des', $des, PDO::PARAM_STR);
        $checkQuery->execute();

        echo json_encode(["message" => "Appointment posted.", "id" => $id, 'success' => true]);

        unset($_SESSION['date']);
        unset($_SESSION['time']);
        unset($_SESSION['vet']);
        unset($_SESSION['des']);
        unset($_SESSION['pet']);
        unset($_SESSION['status']);
        unset($_SESSION['remarks']);

    }

// Close the database connection
$pdo = null;
?>
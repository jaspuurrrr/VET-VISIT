<?php
// Retrieve the JSON data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Start the session
session_start();

// Store the entire JSON object in $_SESSION['appt']
$_SESSION['date'] = $data['date'];
$_SESSION['time'] = $data['time'];
$_SESSION['vet'] = $data['vet'];
$_SESSION['des'] = $data['des'];
$_SESSION['pet'] = $data['pet'];
$_SESSION['status'] = $data['status'];
$_SESSION['remarks'] = $data['remarks'];

// Send a response back to JavaScript
echo $_SESSION['date'].$_SESSION['time'].$_SESSION['vet'].$_SESSION['des'].$_SESSION['pet'].$_SESSION['status'].$_SESSION['remarks'];
?>
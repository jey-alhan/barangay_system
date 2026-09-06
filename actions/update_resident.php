<?php

session_start();

require_once __DIR__ . '/../config/database.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/residents.php");
    exit;

}


$id = intval($_POST['id'] ?? 0);

$firstName = trim($_POST['first_name'] ?? '');

$middleName = trim($_POST['middle_name'] ?? '');

$lastName = trim($_POST['last_name'] ?? '');

$gender = trim($_POST['gender'] ?? '');

$birthDate = $_POST['birth_date'] ?? '';

$civilStatus = trim($_POST['civil_status'] ?? '');

$contactNumber = trim($_POST['contact_number'] ?? '');

$address = trim($_POST['address'] ?? '');


if ($id <= 0) {

    $_SESSION['error'] =
        "Invalid resident.";

    header("Location: ../pages/residents.php");
    exit;

}


if (empty($firstName) || empty($lastName)) {

    $_SESSION['error'] =
        "First name and last name are required.";

    header("Location: ../pages/residents.php");
    exit;

}


$sql = "

    UPDATE residents

    SET

        first_name = ?,

        middle_name = ?,

        last_name = ?,

        gender = ?,

        birth_date = NULLIF(?, ''),

        civil_status = ?,

        contact_number = ?,

        address = ?

    WHERE id = ?

";


$stmt = $conn->prepare($sql);


$stmt->bind_param(

    "ssssssssi",

    $firstName,
    $middleName,
    $lastName,
    $gender,
    $birthDate,
    $civilStatus,
    $contactNumber,
    $address,
    $id

);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Resident updated successfully.";

} else {

    $_SESSION['error'] =
        "Failed to update resident.";

}


$stmt->close();

header("Location: ../pages/residents.php");

exit;

?>
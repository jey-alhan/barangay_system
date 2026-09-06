<?php

session_start();

require_once __DIR__ . '/../config/database.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/households.php");
    exit;

}


$id = (int)(
    $_POST['id'] ?? 0
);

$householdNumber = trim(
    $_POST['household_number'] ?? ''
);

$headName = trim(
    $_POST['head_name'] ?? ''
);

$address = trim(
    $_POST['address'] ?? ''
);

$members = (int)(
    $_POST['members'] ?? 0
);


/* VALIDATION */

if (

    $id <= 0

    || empty($householdNumber)

    || empty($headName)

    || empty($address)

) {

    $_SESSION['error'] =
        "Please fill in all required fields.";

    header("Location: ../pages/households.php");
    exit;

}


/* UPDATE */

$sql = "

    UPDATE households

    SET

        household_number = ?,

        head_name = ?,

        address = ?,

        members = ?

    WHERE id = ?

";


$stmt = $conn->prepare($sql);


$stmt->bind_param(

    "sssii",

    $householdNumber,
    $headName,
    $address,
    $members,
    $id

);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Household updated successfully.";

} else {

    $_SESSION['error'] =
        "Unable to update household.";

}


$stmt->close();


header("Location: ../pages/households.php");
exit;

?>
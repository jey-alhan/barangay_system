<?php

session_start();

require_once __DIR__ . '/../config/database.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/households.php");
    exit;

}


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

    empty($householdNumber)

    || empty($headName)

    || empty($address)

) {

    $_SESSION['error'] =
        "Please fill in all required fields.";

    header("Location: ../pages/households.php");
    exit;

}


/* INSERT */

$sql = "

    INSERT INTO households (

        household_number,
        head_name,
        address,
        members

    )

    VALUES (?, ?, ?, ?)

";


$stmt = $conn->prepare($sql);


$stmt->bind_param(

    "sssi",

    $householdNumber,
    $headName,
    $address,
    $members

);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Household added successfully.";

} else {

    $_SESSION['error'] =
        "Unable to add household. Household number may already exist.";

}


$stmt->close();


header("Location: ../pages/households.php");
exit;

?>
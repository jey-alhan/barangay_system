<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../pages/login.php");
    exit;

}


require_once __DIR__ . '/../config/database.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header(
        "Location: ../pages/blotter.php"
    );

    exit;

}


/* =========================================
   GET FORM DATA
========================================= */

$id = (int) ($_POST['id'] ?? 0);

$blotterNumber = trim(
    $_POST['blotter_number'] ?? ''
);

$date = $_POST['date'] ?? '';

$complainant = trim(
    $_POST['complainant'] ?? ''
);

$respondent = trim(
    $_POST['respondent'] ?? ''
);

$incidentType = trim(
    $_POST['incident_type'] ?? ''
);

$status = $_POST['status'] ?? 'Pending';

$location = trim(
    $_POST['location'] ?? ''
);

$description = trim(
    $_POST['description'] ?? ''
);


/* =========================================
   VALIDATION
========================================= */

if (
    $id <= 0 ||
    empty($blotterNumber) ||
    empty($date) ||
    empty($complainant) ||
    empty($respondent) ||
    empty($incidentType) ||
    empty($location) ||
    empty($description)
) {

    $_SESSION['error'] =
        "Please fill in all required fields.";

    header(
        "Location: ../actions/edit_blotter.php?id=" . $id
    );

    exit;

}


/* =========================================
   UPDATE RECORD
========================================= */

$sql = "
    UPDATE blotter

    SET

        blotter_number = ?,
        date = ?,
        complainant = ?,
        respondent = ?,
        incident_type = ?,
        status = ?,
        location = ?,
        description = ?

    WHERE id = ?
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    $_SESSION['error'] =
        "Database error: " .
        $conn->error;

    header(
        "Location: ../pages/blotter.php"
    );

    exit;

}


$stmt->bind_param(
    "ssssssssi",
    $blotterNumber,
    $date,
    $complainant,
    $respondent,
    $incidentType,
    $status,
    $location,
    $description,
    $id
);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Blotter record updated successfully.";

} else {

    $_SESSION['error'] =
        "Failed to update blotter record.";

}


$stmt->close();


header(
    "Location: ../pages/blotter.php"
);

exit;

?>
<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../pages/login.php");
    exit;

}


require_once __DIR__ . '/../config/database.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/blotter.php");
    exit;

}


/* =========================================
   GET FORM DATA
========================================= */

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
   VALIDATE
========================================= */

if (
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
        "Location: ../pages/blotter.php"
    );

    exit;

}


/* =========================================
   CHECK DUPLICATE BLOTTER NUMBER
========================================= */

$checkSql = "
    SELECT id
    FROM blotter
    WHERE blotter_number = ?
";


$checkStmt = $conn->prepare($checkSql);


if (!$checkStmt) {

    $_SESSION['error'] =
        "Database error.";

    header(
        "Location: ../pages/blotter.php"
    );

    exit;

}


$checkStmt->bind_param(
    "s",
    $blotterNumber
);


$checkStmt->execute();


$checkResult =
    $checkStmt->get_result();


if ($checkResult->num_rows > 0) {

    $_SESSION['error'] =
        "Blotter number already exists.";

    header(
        "Location: ../pages/blotter.php"
    );

    exit;

}


$checkStmt->close();


/* =========================================
   INSERT BLOTTER
========================================= */

$sql = "
    INSERT INTO blotter (

        blotter_number,
        date,
        complainant,
        respondent,
        incident_type,
        status,
        location,
        description

    )

    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    $_SESSION['error'] =
        "Unable to prepare database query.";

    header(
        "Location: ../pages/blotter.php"
    );

    exit;

}


$stmt->bind_param(
    "ssssssss",
    $blotterNumber,
    $date,
    $complainant,
    $respondent,
    $incidentType,
    $status,
    $location,
    $description
);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Blotter record added successfully.";

} else {

    $_SESSION['error'] =
        "Failed to add blotter record.";

}


$stmt->close();


header(
    "Location: ../pages/blotter.php"
);

exit;

?>
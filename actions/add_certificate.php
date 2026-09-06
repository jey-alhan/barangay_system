<?php

session_start();

require_once __DIR__ . '/../config/database.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/certificates.php");
    exit;

}


/* GET FORM DATA */

$certificateNumber = trim($_POST['certificate_number'] ?? '');
$residentName = trim($_POST['resident_name'] ?? '');
$certificateType = trim($_POST['certificate_type'] ?? '');
$purpose = trim($_POST['purpose'] ?? '');
$dateIssued = trim($_POST['date_issued'] ?? '');
$status = trim($_POST['status'] ?? 'Pending');


/* VALIDATION */

if (
    empty($certificateNumber) ||
    empty($residentName) ||
    empty($certificateType) ||
    empty($dateIssued)
) {

    $_SESSION['error'] =
        "Please fill in all required fields.";

    header("Location: ../pages/certificates.php");
    exit;

}


/* VALIDATE STATUS */

if (
    $status !== "Pending" &&
    $status !== "Released"
) {

    $status = "Pending";

}


/* CHECK DUPLICATE */

$checkSql = "
    SELECT id
    FROM certificates
    WHERE certificate_number = ?
";

$checkStmt = $conn->prepare($checkSql);

if (!$checkStmt) {

    die(
        "Database prepare error: " .
        $conn->error
    );

}


$checkStmt->bind_param(
    "s",
    $certificateNumber
);

$checkStmt->execute();

$checkResult =
    $checkStmt->get_result();


if ($checkResult->num_rows > 0) {

    $_SESSION['error'] =
        "Certificate number already exists.";

    $checkStmt->close();

    header("Location: ../pages/certificates.php");
    exit;

}


$checkStmt->close();


/* INSERT CERTIFICATE */

$sql = "
    INSERT INTO certificates
    (
        certificate_number,
        resident_name,
        certificate_type,
        purpose,
        date_issued,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?)
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    die(
        "Prepare failed: " .
        $conn->error
    );

}


$stmt->bind_param(
    "ssssss",
    $certificateNumber,
    $residentName,
    $certificateType,
    $purpose,
    $dateIssued,
    $status
);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Certificate created successfully!";

    $stmt->close();

    /* IMPORTANT */
    header(
        "Location: ../pages/certificates.php"
    );

    exit;

}


die(
    "Database insert failed: " .
    $stmt->error
);

?>
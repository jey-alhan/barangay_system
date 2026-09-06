<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../pages/login.php");
    exit;

}


require_once __DIR__ . '/../config/database.php';


/* =========================================
   CHECK ID
========================================= */

if (
    !isset($_GET['id']) ||
    empty($_GET['id'])
) {

    $_SESSION['error'] =
        "Invalid blotter record.";

    header(
        "Location: ../pages/blotter.php"
    );

    exit;

}


$id = (int) $_GET['id'];


/* =========================================
   DELETE RECORD
========================================= */

$sql = "
    DELETE FROM blotter
    WHERE id = ?
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    $_SESSION['error'] =
        "Database error.";

    header(
        "Location: ../pages/blotter.php"
    );

    exit;

}


$stmt->bind_param(
    "i",
    $id
);


if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        $_SESSION['success'] =
            "Blotter record deleted successfully.";

    } else {

        $_SESSION['error'] =
            "Blotter record not found.";

    }

} else {

    $_SESSION['error'] =
        "Failed to delete blotter record.";

}


$stmt->close();


header(
    "Location: ../pages/blotter.php"
);

exit;

?>
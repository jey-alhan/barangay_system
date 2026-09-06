<?php

session_start();

require_once __DIR__ . '/../config/database.php';


$id = intval($_GET['id'] ?? 0);


if ($id <= 0) {

    $_SESSION['error'] =
        "Invalid resident ID.";

    header("Location: ../pages/residents.php");
    exit;

}


$sql = "

    DELETE FROM residents

    WHERE id = ?

";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "i",
    $id
);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Resident deleted successfully.";

} else {

    $_SESSION['error'] =
        "Failed to delete resident.";

}


$stmt->close();

header("Location: ../pages/residents.php");

exit;

?>
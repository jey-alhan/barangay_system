<?php

session_start();

require_once __DIR__ . '/../config/database.php';


$id = (int)(
    $_GET['id'] ?? 0
);


if ($id <= 0) {

    $_SESSION['error'] =
        "Invalid household.";

    header("Location: ../pages/households.php");
    exit;

}


/* DELETE */

$sql = "

    DELETE FROM households

    WHERE id = ?

";


$stmt = $conn->prepare($sql);


$stmt->bind_param(

    "i",

    $id

);


if ($stmt->execute()) {

    $_SESSION['success'] =
        "Household deleted successfully.";

} else {

    $_SESSION['error'] =
        "Unable to delete household.";

}


$stmt->close();


header("Location: ../pages/households.php");
exit;

?>
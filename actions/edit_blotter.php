<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../pages/login.php");
    exit;

}


$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($id <= 0) {

    $_SESSION['error'] = "Invalid blotter record.";

    header("Location: ../pages/blotter.php");
    exit;

}


/* =========================================
   REDIRECT TO EDIT PAGE
========================================= */

header(
    "Location: ../pages/edit_blotter.php?id=" . $id
);

exit;

?>
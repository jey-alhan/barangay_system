<?php

session_start();


/* =========================================
   CHECK LOGIN
========================================= */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../pages/login.php");
    exit;

}


/* =========================================
   DATABASE CONNECTION
========================================= */

require_once __DIR__ . '/../config/database.php';


/* =========================================
   ONLY ALLOW POST REQUEST
========================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   GET SESSION USER
========================================= */

$userId = (int) $_SESSION['user_id'];


/* =========================================
   GET FORM DATA
========================================= */

$username = trim($_POST['username'] ?? '');

$currentPassword = $_POST['current_password'] ?? '';

$newPassword = $_POST['new_password'] ?? '';

$confirmPassword = $_POST['confirm_password'] ?? '';


/* =========================================
   VALIDATE USERNAME
========================================= */

if (empty($username)) {

    $_SESSION['error'] =
        "Username cannot be empty.";

    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   GET CURRENT USER
========================================= */

$sql = "
    SELECT
        id,
        username,
        password
    FROM users
    WHERE id = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    $_SESSION['error'] =
        "Database error: Unable to prepare query.";

    header("Location: ../pages/settings.php");
    exit;

}


$stmt->bind_param(
    "i",
    $userId
);


$stmt->execute();


$result = $stmt->get_result();


/* =========================================
   CHECK USER EXISTS
========================================= */

if ($result->num_rows !== 1) {

    $_SESSION['error'] =
        "User account not found.";

    $stmt->close();

    header("Location: ../pages/login.php");
    exit;

}


$user = $result->fetch_assoc();


$stmt->close();


/* =========================================
   CHECK DUPLICATE USERNAME
========================================= */

$sql = "
    SELECT id
    FROM users
    WHERE username = ?
    AND id != ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "si",
    $username,
    $userId
);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows > 0) {

    $_SESSION['error'] =
        "This username is already being used.";

    $stmt->close();

    header("Location: ../pages/settings.php");
    exit;

}


$stmt->close();


/* =========================================
   DETERMINE IF PASSWORD IS BEING CHANGED
========================================= */

$passwordChanging =

    !empty($currentPassword) ||
    !empty($newPassword) ||
    !empty($confirmPassword);


/* =========================================
   PASSWORD VALIDATION
========================================= */

if ($passwordChanging) {


    /* CHECK ALL PASSWORD FIELDS */

    if (

        empty($currentPassword) ||
        empty($newPassword) ||
        empty($confirmPassword)

    ) {

        $_SESSION['error'] =
            "Please complete all password fields.";

        header("Location: ../pages/settings.php");
        exit;

    }


    /* VERIFY CURRENT PASSWORD */

    if (

        !password_verify(
            $currentPassword,
            $user['password']
        )

    ) {

        $_SESSION['error'] =
            "Current password is incorrect.";

        header("Location: ../pages/settings.php");
        exit;

    }


    /* CHECK NEW PASSWORD LENGTH */

    if (strlen($newPassword) < 6) {

        $_SESSION['error'] =
            "New password must be at least 6 characters.";

        header("Location: ../pages/settings.php");
        exit;

    }


    /* CONFIRM PASSWORD */

    if ($newPassword !== $confirmPassword) {

        $_SESSION['error'] =
            "New password and confirmation password do not match.";

        header("Location: ../pages/settings.php");
        exit;

    }


    /* HASH NEW PASSWORD */

    $hashedPassword = password_hash(
        $newPassword,
        PASSWORD_DEFAULT
    );


    /* UPDATE USERNAME + PASSWORD */

    $sql = "
        UPDATE users
        SET
            username = ?,
            password = ?
        WHERE id = ?
    ";


    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        $_SESSION['error'] =
            "Unable to update account.";

        header("Location: ../pages/settings.php");
        exit;

    }


    $stmt->bind_param(
        "ssi",
        $username,
        $hashedPassword,
        $userId
    );


} else {


    /* =========================================
       UPDATE USERNAME ONLY
    ========================================= */

    $sql = "
        UPDATE users
        SET username = ?
        WHERE id = ?
    ";


    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        $_SESSION['error'] =
            "Unable to update username.";

        header("Location: ../pages/settings.php");
        exit;

    }


    $stmt->bind_param(
        "si",
        $username,
        $userId
    );

}


/* =========================================
   EXECUTE UPDATE
========================================= */

if ($stmt->execute()) {


    /* UPDATE SESSION USERNAME */

    $_SESSION['username'] = $username;


    $_SESSION['success'] =
        "Account settings updated successfully.";


    $stmt->close();


    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   UPDATE ERROR
========================================= */

$_SESSION['error'] =
    "Failed to update account settings.";


$stmt->close();


header("Location: ../pages/settings.php");
exit;

?>
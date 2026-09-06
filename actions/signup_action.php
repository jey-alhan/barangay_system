<?php

session_start();

require_once __DIR__ . '/../config/database.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/signup.php");
    exit;

}


$username = trim($_POST['username'] ?? '');

$password = $_POST['password'] ?? '';

$confirmPassword = $_POST['confirm_password'] ?? '';



if (
    empty($username) ||
    empty($password) ||
    empty($confirmPassword)
) {

    $_SESSION['error'] = "Please fill in all fields.";

    header("Location: ../pages/signup.php");
    exit;

}



if ($password !== $confirmPassword) {

    $_SESSION['error'] = "Passwords do not match.";

    header("Location: ../pages/signup.php");
    exit;

}



if (strlen($password) < 6) {

    $_SESSION['error'] = "Password must be at least 6 characters.";

    header("Location: ../pages/signup.php");
    exit;

}



$checkSql = "
    SELECT id
    FROM users
    WHERE username = ?
";


$checkStmt = $conn->prepare($checkSql);

$checkStmt->bind_param(
    "s",
    $username
);

$checkStmt->execute();

$checkResult = $checkStmt->get_result();



if ($checkResult->num_rows > 0) {

    $_SESSION['error'] = "Username already exists.";

    header("Location: ../pages/signup.php");
    exit;

}



$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);



$sql = "
    INSERT INTO users
    (
        username,
        password
    )
    VALUES
    (
        ?,
        ?
    )
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $username,
    $hashedPassword
);



if ($stmt->execute()) {

    $_SESSION['success'] =
        "Account created successfully. Please login.";

    header("Location: ../pages/login.php");
    exit;

}


$_SESSION['error'] =
    "Something went wrong. Please try again.";

header("Location: ../pages/signup.php");

exit;

?>
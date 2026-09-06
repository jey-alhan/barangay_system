<?php

session_start();

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /barangay-system_MySql/pages/login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Please enter your username and password.";

    header("Location: /barangay-system_MySql/pages/login.php");
    exit;
}

$sql = "SELECT id, username, password FROM users WHERE username = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: /barangay-system_MySql/pages/dashboard.php");
        exit;
    }
}

$_SESSION['error'] = "Invalid username or password.";

header("Location: /barangay-system_MySql/pages/login.php");
exit;
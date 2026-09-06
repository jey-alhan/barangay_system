<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';


/* =========================================
   USER INFORMATION
========================================= */

$userId = (int) $_SESSION['user_id'];

$username = $_SESSION['username'] ?? 'Administrator';


/* =========================================
   GET CURRENT USER DATA
========================================= */

$sql = "
    SELECT
        id,
        username,
        created_at
    FROM users
    WHERE id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

$stmt->close();


/* =========================================
   MESSAGES
========================================= */

$success = $_SESSION['success'] ?? '';

$error = $_SESSION['error'] ?? '';

unset($_SESSION['success']);
unset($_SESSION['error']);


/* =========================================
   ORGANIZATIONAL CHART IMAGE
========================================= */

$chartDirectory = __DIR__ . '/../uploads/organizational_charts/';

$chartImage = '';

if (is_dir($chartDirectory)) {

    $files = scandir($chartDirectory);

    foreach ($files as $file) {

        if (
            $file !== '.' &&
            $file !== '..'
        ) {

            $extension = strtolower(
                pathinfo(
                    $file,
                    PATHINFO_EXTENSION
                )
            );

            if (
                in_array(
                    $extension,
                    ['jpg', 'jpeg', 'png', 'webp']
                )
            ) {

                $chartImage =
                    '../uploads/organizational_charts/' .
                    $file;

                break;
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Settings - Barangay Management System
    </title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Font Awesome -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        rel="stylesheet"
    >

    <link
        href="../assets/css/style.css"
        rel="stylesheet"
    >


<style>

/* =========================================
   GLOBAL
========================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


body {

    font-family:
        Arial,
        sans-serif;

    min-height:
        100vh;

    background:
        linear-gradient(
            135deg,
            #071a2b,
            #0b2d3b,
            #123524,
            #071a2b
        );

    background-attachment:
        fixed;

    color:
        white;
}


/* =========================================
   SIDEBAR
========================================= */

.sidebar {

    width:
        260px;

    height:
        100vh;

    position:
        fixed;

    top:
        0;

    left:
        0;

    padding:
        25px 15px;

    background:
        rgba(5, 20, 30, 0.78);

    backdrop-filter:
        blur(20px);

    border-right:
        1px solid
        rgba(255,255,255,.10);

    z-index:
        1000;
}


.brand {

    padding:
        15px;

    font-size:
        20px;

    font-weight:
        700;

    border-bottom:
        1px solid
        rgba(255,255,255,.10);

    margin-bottom:
        25px;
}


.brand i {

    color:
        #20c997;

    margin-right:
        10px;
}


.menu-title {

    font-size:
        11px;

    color:
        #9fb3c8;

    text-transform:
        uppercase;

    letter-spacing:
        1px;

    margin:
        25px
        15px
        10px;
}


.sidebar a {

    display:
        flex;

    align-items:
        center;

    text-decoration:
        none;

    color:
        #d6e4ef;

    padding:
        12px 15px;

    border-radius:
        12px;

    margin-bottom:
        6px;

    transition:
        .3s;
}


.sidebar a i {

    width:
        25px;

    font-size:
        16px;
}


.sidebar a:hover,
.sidebar a.active {

    background:
        rgba(32,201,151,.18);

    color:
        white;

    box-shadow:
        inset
        0
        0
        0
        1px
        rgba(32,201,151,.18);
}


.logout-link {

    margin-top:
        30px;

    background:
        rgba(239,68,68,.10);

    color:
        #ffb4b4 !important;
}


.logout-link:hover {

    background:
        rgba(239,68,68,.22);
}


/* =========================================
   MAIN CONTENT
========================================= */

.main-content {

    margin-left:
        260px;

    min-height:
        100vh;

    padding:
        30px;
}


/* =========================================
   HEADER
========================================= */

.top-header {

    background:
        rgba(255,255,255,.08);

    backdrop-filter:
        blur(20px);

    border:
        1px solid
        rgba(255,255,255,.12);

    border-radius:
        20px;

    padding:
        20px 25px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    margin-bottom:
        30px;

    box-shadow:
        0 15px 35px
        rgba(0,0,0,.15);
}


.page-title h2 {

    font-weight:
        700;

    margin-bottom:
        5px;
}


.page-title p {

    color:
        rgba(255,255,255,.65);

    margin:
        0;

    font-size:
        14px;
}


/* =========================================
   USER PROFILE
========================================= */

.user-profile {

    display:
        flex;

    align-items:
        center;

    gap:
        12px;
}


.user-avatar {

    width:
        45px;

    height:
        45px;

    border-radius:
        50%;

    background:
        linear-gradient(
            135deg,
            #198754,
            #20c997
        );

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    color:
        white;

    font-weight:
        bold;

    font-size:
        18px;
}


/* =========================================
   GLASS CARD
========================================= */

.glass-card {

    background:
        rgba(255,255,255,.08);

    backdrop-filter:
        blur(20px);

    border:
        1px solid
        rgba(255,255,255,.12);

    border-radius:
        20px;

    padding:
        28px;

    height:
        100%;

    box-shadow:
        0 15px 35px
        rgba(0,0,0,.15);
}


/* =========================================
   CARD TITLE
========================================= */

.card-title {

    display:
        flex;

    align-items:
        center;

    gap:
        12px;

    margin-bottom:
        10px;

    font-weight:
        700;
}


.card-title-icon {

    width:
        45px;

    height:
        45px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    border-radius:
        12px;

    background:
        rgba(32,201,151,.15);

    color:
        #20c997;

    font-size:
        18px;
}


.card-description {

    color:
        rgba(255,255,255,.60);

    font-size:
        14px;

    margin-bottom:
        25px;
}


/* =========================================
   FORM
========================================= */

.form-label {

    font-weight:
        600;

    margin-bottom:
        8px;
}


.form-control {

    background:
        rgba(255,255,255,.08);

    border:
        1px solid
        rgba(255,255,255,.15);

    color:
        white;

    padding:
        12px;

    border-radius:
        10px;
}


.form-control:focus {

    background:
        rgba(255,255,255,.12);

    color:
        white;

    border-color:
        #20c997;

    box-shadow:
        0
        0
        0
        .2rem
        rgba(32,201,151,.20);
}


/* =========================================
   BUTTON
========================================= */

.btn-save {

    background:
        linear-gradient(
            135deg,
            #198754,
            #20c997
        );

    border:
        none;

    color:
        white;

    padding:
        11px 22px;

    border-radius:
        10px;

    font-weight:
        600;
}


/* =========================================
   PROFILE
========================================= */

.profile-box {

    text-align:
        center;
}


.large-avatar {

    width:
        100px;

    height:
        100px;

    margin:
        0 auto 20px;

    border-radius:
        50%;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    font-size:
        36px;

    font-weight:
        bold;

    background:
        linear-gradient(
            135deg,
            #198754,
            #20c997
        );
}


.account-info {

    text-align:
        left;

    background:
        rgba(0,0,0,.12);

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius:
        15px;

    padding:
        15px;

    margin-top:
        20px;
}


.account-info small {

    display:
        block;

    color:
        rgba(255,255,255,.50);

    margin-bottom:
        3px;
}


.account-info strong {

    display:
        block;

    margin-bottom:
        15px;
}


/* =========================================
   ORGANIZATIONAL CHART
========================================= */

.chart-container {

    width:
        100%;

    min-height:
        300px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    background:
        rgba(0,0,0,.12);

    border:
        1px dashed
        rgba(32,201,151,.35);

    border-radius:
        15px;

    overflow:
        hidden;

    margin-bottom:
        20px;
}


.chart-image {

    width:
        100%;

    max-height:
        600px;

    object-fit:
        contain;

    display:
        block;
}


.no-chart {

    text-align:
        center;

    padding:
        50px;

    color:
        rgba(255,255,255,.55);
}


.no-chart i {

    font-size:
        50px;

    color:
        #20c997;

    margin-bottom:
        15px;
}


/* =========================================
   DEVELOPER CARD
========================================= */

.developer-card {

    text-align:
        center;

    padding:
        15px;
}


.developer-icon {

    width:
        75px;

    height:
        75px;

    margin:
        0 auto 15px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    border-radius:
        20px;

    font-size:
        30px;

    background:
        rgba(32,201,151,.15);

    color:
        #20c997;
}


.developer-card p {

    color:
        rgba(255,255,255,.60);

    margin-bottom:
        5px;
}


/* =========================================
   INFO ROW
========================================= */

.setting-row {

    display:
        flex;

    justify-content:
        space-between;

    align-items:
        center;

    padding:
        18px 0;

    border-bottom:
        1px solid
        rgba(255,255,255,.08);
}


.setting-row:last-child {

    border-bottom:
        none;
}


.setting-row p {

    margin:
        0;

    color:
        rgba(255,255,255,.55);

    font-size:
        13px;
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 992px) {

    .sidebar {

        width:
            80px;

        padding:
            20px 10px;
    }


    .brand span,
    .sidebar a span,
    .menu-title {

        display:
            none;
    }


    .brand {

        text-align:
            center;

        padding:
            15px 5px;
    }


    .brand i {

        margin:
            0;
    }


    .sidebar a {

        justify-content:
            center;
    }


    .sidebar a i {

        width:
            auto;
    }


    .main-content {

        margin-left:
            80px;
    }

}


@media (max-width: 768px) {

    .sidebar {

        display:
            none;
    }


    .main-content {

        margin-left:
            0;

        padding:
            20px;
    }


    .top-header {

        flex-direction:
            column;

        align-items:
            flex-start;

        gap:
            15px;
    }

}

</style>

</head>


<body>


<!-- =====================================
     SIDEBAR
===================================== -->

<div class="sidebar">

    <div class="brand">

        <i class="fa-solid fa-building"></i>

        <span>Barangay System</span>

    </div>


    <div class="menu-title">
        Main Menu
    </div>


    <a href="dashboard.php">

        <i class="fa-solid fa-chart-pie"></i>

        <span>Dashboard</span>

    </a>


    <a href="residents.php">

        <i class="fa-solid fa-users"></i>

        <span>Residents</span>

    </a>


    <a href="households.php">

        <i class="fa-solid fa-house"></i>

        <span>Households</span>

    </a>


    <a href="certificates.php">

        <i class="fa-solid fa-file-lines"></i>

        <span>Certificates</span>

    </a>


    <a href="blotter.php">

        <i class="fa-solid fa-scale-balanced"></i>

        <span>Blotter Records</span>

    </a>


    <div class="menu-title">
        Account
    </div>


    <a
        href="settings.php"
        class="active"
    >

        <i class="fa-solid fa-gear"></i>

        <span>Settings</span>

    </a>


    <a
        href="../actions/logout.php"
        class="logout-link"
    >

        <i class="fa-solid fa-right-from-bracket"></i>

        <span>Logout</span>

    </a>

</div>


<!-- =====================================
     MAIN CONTENT
===================================== -->

<div class="main-content">


    <!-- HEADER -->

    <div class="top-header">

        <div class="page-title">

            <h2>Settings</h2>

            <p>
                Manage your account and system information.
            </p>

        </div>


        <div class="user-profile">

            <div class="user-avatar">

                <?php
                echo strtoupper(
                    substr($username, 0, 1)
                );
                ?>

            </div>


            <div>

                <strong>

                    <?php
                    echo htmlspecialchars($username);
                    ?>

                </strong>

                <br>

                <small class="text-light opacity-75">

                    System Administrator

                </small>

            </div>

        </div>

    </div>


    <!-- SUCCESS -->

    <?php if (!empty($success)): ?>

        <div class="alert alert-success">

            <i class="fa-solid fa-circle-check me-2"></i>

            <?php
            echo htmlspecialchars($success);
            ?>

        </div>

    <?php endif; ?>


    <!-- ERROR -->

    <?php if (!empty($error)): ?>

        <div class="alert alert-danger">

            <i class="fa-solid fa-circle-exclamation me-2"></i>

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php endif; ?>


    <div class="row g-4">


        <!-- PROFILE -->

        <div class="col-lg-4">

            <div class="glass-card">

                <div class="profile-box">

                    <div class="large-avatar">

                        <?php
                        echo strtoupper(
                            substr($username, 0, 1)
                        );
                        ?>

                    </div>


                    <h4>

                        <?php
                        echo htmlspecialchars($username);
                        ?>

                    </h4>


                    <p>
                        System Administrator
                    </p>


                    <div class="account-info">

                        <small>
                            Username
                        </small>

                        <strong>

                            <?php
                            echo htmlspecialchars(
                                $user['username']
                                ?? $username
                            );
                            ?>

                        </strong>


                        <small>
                            Account Created
                        </small>

                        <strong>

                            <?php

                            if (
                                !empty(
                                    $user['created_at']
                                )
                            ) {

                                echo date(
                                    "F d, Y",
                                    strtotime(
                                        $user['created_at']
                                    )
                                );

                            } else {

                                echo "N/A";

                            }

                            ?>

                        </strong>

                    </div>

                </div>

            </div>

        </div>


        <!-- ACCOUNT SETTINGS -->

        <div class="col-lg-8">

            <div class="glass-card">

                <div class="card-title">

                    <div class="card-title-icon">

                        <i class="fa-solid fa-user-gear"></i>

                    </div>

                    <h5 class="mb-0">

                        Account Settings

                    </h5>

                </div>


                <p class="card-description">

                    Update your username and password.

                </p>


                <form
                    action="../actions/update_settings.php"
                    method="POST"
                >

                    <div class="mb-4">

                        <label class="form-label">

                            Username

                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            value="<?php
                                echo htmlspecialchars(
                                    $user['username']
                                    ?? $username
                                );
                            ?>"
                            required
                        >

                    </div>


                    <hr
                        style="
                            border-color:
                            rgba(255,255,255,.10);
                        "
                    >


                    <div class="row g-3 mt-2">


                        <div class="col-md-4">

                            <label class="form-label">

                                Current Password

                            </label>

                            <input
                                type="password"
                                name="current_password"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                New Password

                            </label>

                            <input
                                type="password"
                                name="new_password"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                Confirm Password

                            </label>

                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                            >

                        </div>

                    </div>


                    <div class="mt-4">

                        <button
                            type="submit"
                            class="btn-save"
                        >

                            <i class="fa-solid fa-floppy-disk me-2"></i>

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- ORGANIZATIONAL CHART -->

        <div class="col-12">

            <div class="glass-card">

                <div class="card-title">

                    <div class="card-title-icon">

                        <i class="fa-solid fa-sitemap"></i>

                    </div>

                    <h5 class="mb-0">

                        Barangay Organizational Chart

                    </h5>

                </div>


                <p class="card-description">

                    Upload and manage the official
                    barangay organizational chart.

                </p>


                <div class="chart-container">


                    <?php if (!empty($chartImage)): ?>


                        <img
                            src="<?php
                            echo htmlspecialchars(
                                $chartImage
                            );
                            ?>"
                            class="chart-image"
                            alt="Barangay Organizational Chart"
                        >


                    <?php else: ?>


                        <div class="no-chart">

                            <i class="fa-solid fa-sitemap d-block"></i>

                            <h5>
                                No Organizational Chart
                            </h5>

                            <p>
                                Upload an organizational
                                chart image below.
                            </p>

                        </div>


                    <?php endif; ?>


                </div>


                <!-- UPLOAD FORM -->

                <form
                    action="../actions/upload_organizational_chart.php"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <div class="row align-items-end g-3">

                        <div class="col-md-8">

                            <label class="form-label">

                                Select Organizational Chart Image

                            </label>

                            <input
                                type="file"
                                name="chart_image"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <button
                                type="submit"
                                class="btn-save w-100"
                            >

                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>

                                Upload Chart

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        <!-- DEVELOPER -->

        <div class="col-lg-6">

            <div class="glass-card">

                <div class="card-title">

                    <div class="card-title-icon">

                        <i class="fa-solid fa-code"></i>

                    </div>

                    <h5 class="mb-0">

                        Developer Information

                    </h5>

                </div>


                <div class="developer-card">

                    <div class="developer-icon">

                        <i class="fa-solid fa-laptop-code"></i>

                    </div>


                    <h4>

                        Jey-alhan Soriano

                    </h4>


                    <p>

                        System Developer

                    </p>


                    <p>

                        Barangay Management System

                    </p>


                    <p class="mt-3">

                        Developed using PHP, MySQL,
                        Bootstrap and modern web technologies.

                    </p>

                </div>

            </div>

        </div>


        <!-- SYSTEM INFORMATION -->

        <div class="col-lg-6">

            <div class="glass-card">

                <div class="card-title">

                    <div class="card-title-icon">

                        <i class="fa-solid fa-circle-info"></i>

                    </div>

                    <h5 class="mb-0">

                        System Information

                    </h5>

                </div>


                <div class="setting-row">

                    <div>

                        <h6>
                            System Name
                        </h6>

                        <p>
                            Barangay Management System
                        </p>

                    </div>

                    <i class="fa-solid fa-building fs-4"></i>

                </div>


                <div class="setting-row">

                    <div>

                        <h6>
                            Database
                        </h6>

                        <p>
                            MySQL Database
                        </p>

                    </div>

                    <i class="fa-solid fa-database fs-4"></i>

                </div>


                <div class="setting-row">

                    <div>

                        <h6>
                            Technology
                        </h6>

                        <p>
                            PHP + MySQL + Bootstrap
                        </p>

                    </div>

                    <i class="fa-solid fa-code fs-4"></i>

                </div>


                <div class="setting-row">

                    <div>

                        <h6>
                            Version
                        </h6>

                        <p>
                            Version 1.0
                        </p>

                    </div>

                    <i class="fa-solid fa-code-branch fs-4"></i>

                </div>

            </div>

        </div>


    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
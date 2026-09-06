<?php

session_start();

/* =========================================
   CHECK LOGIN
========================================= */

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}

require_once __DIR__ . '/../config/database.php';


/* =========================================
   SESSION
========================================= */

$username = $_SESSION['username'] ?? 'Guest';


/* =========================================
   DATABASE COUNTS
========================================= */

function getCount($conn, $table)
{
    $result = $conn->query(
        "SELECT COUNT(*) AS total FROM `$table`"
    );

    if ($result) {

        $row = $result->fetch_assoc();

        return $row['total'];
    }

    return 0;
}


$totalUsers = getCount($conn, "users");
$totalResidents = getCount($conn, "residents");
$totalHouseholds = getCount($conn, "households");
$totalCertificates = getCount($conn, "certificates");
$totalBlotters = getCount($conn, "blotter");

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
        Dashboard - Barangay Management System
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

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            min-height: 100vh;

            font-family:
                Arial,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #081c2c,
                    #102a43,
                    #0f5132
                );

            color: white;

        }


        /* =====================================
           SIDEBAR
        ===================================== */

        .sidebar {

            width: 260px;

            min-height: 100vh;

            position: fixed;

            top: 0;

            left: 0;

            padding: 25px 15px;

            background:
                rgba(8,28,44,0.85);

            backdrop-filter:
                blur(20px);

            border-right:
                1px solid
                rgba(255,255,255,0.12);

            z-index: 1000;

        }


        .brand {

            padding: 15px;

            margin-bottom: 25px;

            font-size: 20px;

            font-weight: 700;

            border-bottom:
                1px solid
                rgba(255,255,255,0.12);

        }


        .brand i {

            color: #38bdf8;

            margin-right: 10px;

        }


        .menu-title {

            color: #94a3b8;

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: 1px;

            margin:
                25px 15px 10px;

        }


        .sidebar a {

            display: flex;

            align-items: center;

            gap: 12px;

            text-decoration: none;

            color: #cbd5e1;

            padding: 12px 15px;

            border-radius: 12px;

            margin-bottom: 6px;

            transition: 0.3s;

        }


        .sidebar a:hover {

            background:
                rgba(255,255,255,0.12);

            color: white;

        }


        .sidebar a.active {

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

            color: white;

        }


        .logout-link {

            margin-top: 25px;

            background:
                rgba(220,53,69,0.12);

            color: #ffb3bd !important;

        }


        /* =====================================
           MAIN CONTENT
        ===================================== */

        .main-content {

            margin-left: 260px;

            min-height: 100vh;

            padding: 35px;

        }


        /* =====================================
           HEADER
        ===================================== */

        .top-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;

            padding: 25px;

            margin-bottom: 30px;

            border-radius: 20px;

            background:
                rgba(255,255,255,0.10);

            backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255,255,255,0.15);

            box-shadow:
                0 15px 35px
                rgba(0,0,0,0.20);

        }


        .page-title h2 {

            margin: 0;

            font-weight: 700;

        }


        .page-title p {

            margin: 5px 0 0;

            color: #cbd5e1;

        }


        .user-box {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .user-avatar {

            width: 45px;

            height: 45px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            font-weight: bold;

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

        }


        /* =====================================
           GLASS CARD
        ===================================== */

        .glass-card {

            padding: 25px;

            border-radius: 20px;

            background:
                rgba(255,255,255,0.10);

            backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255,255,255,0.15);

            box-shadow:
                0 15px 35px
                rgba(0,0,0,0.20);

        }


        /* =====================================
           WELCOME CARD
        ===================================== */

        .welcome-card {

            padding: 30px;

            margin-bottom: 30px;

            border-radius: 20px;

            background:
                linear-gradient(
                    135deg,
                    rgba(25,135,84,0.35),
                    rgba(32,201,151,0.18)
                );

            border:
                1px solid
                rgba(255,255,255,0.15);

            box-shadow:
                0 15px 35px
                rgba(0,0,0,0.20);

        }


        .welcome-card h2 {

            font-weight: 700;

            margin-bottom: 10px;

        }


        .welcome-card p {

            margin: 0;

            color: #cbd5e1;

        }


        /* =====================================
           STATISTICS
        ===================================== */

        .stat-card {

            height: 100%;

            padding: 25px;

            border-radius: 20px;

            background:
                rgba(255,255,255,0.10);

            backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255,255,255,0.15);

            box-shadow:
                0 15px 35px
                rgba(0,0,0,0.20);

            transition: 0.3s;

        }


        .stat-card:hover {

            transform:
                translateY(-5px);

            background:
                rgba(255,255,255,0.14);

        }


        .stat-top {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;

        }


        .stat-title {

            color: #cbd5e1;

            font-size: 14px;

        }


        .stat-number {

            font-size: 38px;

            font-weight: 700;

            margin: 0;

        }


        .stat-icon {

            width: 55px;

            height: 55px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 15px;

            font-size: 20px;

        }


        .icon-blue {

            background:
                rgba(13,110,253,0.20);

            color: #75c7ff;

        }


        .icon-green {

            background:
                rgba(25,135,84,0.20);

            color: #20c997;

        }


        .icon-purple {

            background:
                rgba(111,66,193,0.20);

            color: #c084fc;

        }


        .icon-orange {

            background:
                rgba(255,193,7,0.15);

            color: #ffc107;

        }


        .icon-red {

            background:
                rgba(220,53,69,0.15);

            color: #ff8f9b;

        }


        /* =====================================
           SECTION HEADER
        ===================================== */

        .section-header {

            margin:
                40px
                0
                20px;

        }


        .section-header h3 {

            font-size: 22px;

            font-weight: 700;

            margin-bottom: 5px;

        }


        .section-header p {

            margin: 0;

            color: #cbd5e1;

        }


        /* =====================================
           QUICK ACTIONS
        ===================================== */

        .action-card {

            display: flex;

            flex-direction: column;

            justify-content: space-between;

            min-height: 180px;

            padding: 25px;

            text-decoration: none;

            color: white;

            border-radius: 20px;

            background:
                rgba(255,255,255,0.10);

            backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255,255,255,0.15);

            box-shadow:
                0 15px 35px
                rgba(0,0,0,0.20);

            transition: 0.3s;

        }


        .action-card:hover {

            color: white;

            transform:
                translateY(-5px);

            background:
                rgba(255,255,255,0.14);

        }


        .action-icon {

            width: 55px;

            height: 55px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 15px;

            font-size: 20px;

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

        }


        .action-card strong {

            display: block;

            margin-top: 20px;

            font-size: 17px;

        }


        .action-card small {

            color: #cbd5e1;

        }


        /* =====================================
           MOBILE
        ===================================== */

        @media (max-width: 768px) {

            .sidebar {

                position: relative;

                width: 100%;

                min-height: auto;

            }


            .main-content {

                margin-left: 0;

                padding: 20px;

            }


            .top-header {

                flex-direction: column;

                align-items: flex-start;

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

        Barangay System

    </div>


    <div class="menu-title">

        Main Menu

    </div>


    <a
        href="dashboard.php"
        class="active"
    >

        <i class="fa-solid fa-chart-pie"></i>

        Dashboard

    </a>


    <a href="residents.php">

        <i class="fa-solid fa-users"></i>

        Residents

    </a>


    <a href="households.php">

        <i class="fa-solid fa-house"></i>

        Households

    </a>


    <a href="certificates.php">

        <i class="fa-solid fa-file-lines"></i>

        Certificates

    </a>


    <a href="blotter.php">

        <i class="fa-solid fa-scale-balanced"></i>

        Blotter Records

    </a>


    <div class="menu-title">

        Account

    </div>


    <a href="settings.php">

        <i class="fa-solid fa-gear"></i>

        Settings

    </a>


    <a
        href="../actions/logout.php"
        class="logout-link"
    >

        <i class="fa-solid fa-right-from-bracket"></i>

        Logout

    </a>


</div>


<!-- =====================================
     MAIN CONTENT
===================================== -->

<div class="main-content">


    <!-- HEADER -->

    <div class="top-header">


        <div class="page-title">

            <h2>

                Dashboard Overview

            </h2>

            <p>

                Overview of your Barangay Management System.

            </p>

        </div>


        <div class="user-box">


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

                    Administrator

                </small>

            </div>


        </div>


    </div>


    <!-- WELCOME CARD -->

    <div class="welcome-card">

        <h2>

            Welcome back,
            <?php echo htmlspecialchars($username); ?>!

        </h2>


        <p>

            Manage residents, households, certificates,
            blotter records, and other barangay information
            from one centralized system.

        </p>

    </div>


    <!-- =====================================
         PRIMARY STATISTICS
    ===================================== -->

    <div class="row g-4">


        <!-- RESIDENTS -->

        <div class="col-md-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">

                    <span class="stat-title">

                        Total Residents

                    </span>


                    <div class="stat-icon icon-green">

                        <i class="fa-solid fa-users"></i>

                    </div>

                </div>


                <h2 class="stat-number">

                    <?php echo $totalResidents; ?>

                </h2>

            </div>

        </div>


        <!-- HOUSEHOLDS -->

        <div class="col-md-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">

                    <span class="stat-title">

                        Total Households

                    </span>


                    <div class="stat-icon icon-purple">

                        <i class="fa-solid fa-house"></i>

                    </div>

                </div>


                <h2 class="stat-number">

                    <?php echo $totalHouseholds; ?>

                </h2>

            </div>

        </div>


        <!-- USERS -->

        <div class="col-md-12 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">

                    <span class="stat-title">

                        System Users

                    </span>


                    <div class="stat-icon icon-blue">

                        <i class="fa-solid fa-user"></i>

                    </div>

                </div>


                <h2 class="stat-number">

                    <?php echo $totalUsers; ?>

                </h2>

            </div>

        </div>


    </div>


    <!-- =====================================
         SECONDARY STATISTICS
    ===================================== -->

    <div class="row g-4 mt-1">


        <!-- CERTIFICATES -->

        <div class="col-md-6">

            <div class="stat-card">

                <div class="stat-top">

                    <span class="stat-title">

                        Total Certificates

                    </span>


                    <div class="stat-icon icon-orange">

                        <i class="fa-solid fa-file-lines"></i>

                    </div>

                </div>


                <h2 class="stat-number">

                    <?php echo $totalCertificates; ?>

                </h2>

            </div>

        </div>


        <!-- BLOTTERS -->

        <div class="col-md-6">

            <div class="stat-card">

                <div class="stat-top">

                    <span class="stat-title">

                        Blotter Records

                    </span>


                    <div class="stat-icon icon-red">

                        <i class="fa-solid fa-scale-balanced"></i>

                    </div>

                </div>


                <h2 class="stat-number">

                    <?php echo $totalBlotters; ?>

                </h2>

            </div>

        </div>


    </div>


    <!-- =====================================
         QUICK ACTIONS
    ===================================== -->

    <div class="section-header">


        <h3>

            Quick Actions

        </h3>


        <p>

            Quickly access important barangay features.

        </p>


    </div>


    <div class="row g-4">


        <!-- RESIDENTS -->

        <div class="col-md-6 col-xl-3">

            <a
                href="residents.php"
                class="action-card"
            >

                <div class="action-icon">

                    <i class="fa-solid fa-users"></i>

                </div>


                <div>

                    <strong>

                        Residents

                    </strong>


                    <small>

                        Manage resident records

                    </small>

                </div>

            </a>

        </div>


        <!-- HOUSEHOLDS -->

        <div class="col-md-6 col-xl-3">

            <a
                href="households.php"
                class="action-card"
            >

                <div class="action-icon">

                    <i class="fa-solid fa-house"></i>

                </div>


                <div>

                    <strong>

                        Households

                    </strong>


                    <small>

                        Manage household records

                    </small>

                </div>

            </a>

        </div>


        <!-- CERTIFICATES -->

        <div class="col-md-6 col-xl-3">

            <a
                href="certificates.php"
                class="action-card"
            >

                <div class="action-icon">

                    <i class="fa-solid fa-file-circle-plus"></i>

                </div>


                <div>

                    <strong>

                        Certificates

                    </strong>


                    <small>

                        Issue barangay certificates

                    </small>

                </div>

            </a>

        </div>


        <!-- BLOTTER -->

        <div class="col-md-6 col-xl-3">

            <a
                href="blotter.php"
                class="action-card"
            >

                <div class="action-icon">

                    <i class="fa-solid fa-scale-balanced"></i>

                </div>


                <div>

                    <strong>

                        Blotter

                    </strong>


                    <small>

                        Manage barangay cases

                    </small>

                </div>

            </a>

        </div>


    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
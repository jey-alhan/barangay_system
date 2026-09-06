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
   CHECK CERTIFICATE ID
========================================= */

if (!isset($_GET['id']) || empty($_GET['id'])) {

    $_SESSION['error'] =
        "Certificate not found.";

    header("Location: certificates.php");
    exit;

}


$id = (int) $_GET['id'];


/* =========================================
   GET CERTIFICATE
========================================= */

$sql = "
    SELECT *
    FROM certificates
    WHERE id = ?
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    $_SESSION['error'] =
        "Database error: " . $conn->error;

    header("Location: certificates.php");
    exit;

}


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$result = $stmt->get_result();


/* =========================================
   CHECK IF CERTIFICATE EXISTS
========================================= */

if ($result->num_rows !== 1) {

    $_SESSION['error'] =
        "Certificate not found.";

    header("Location: certificates.php");
    exit;

}


$certificate =
    $result->fetch_assoc();


$stmt->close();

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
        View Certificate - Barangay Management System
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
                rgba(8, 28, 44, 0.85);

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

            font-weight: bold;

            border-bottom:
                1px solid
                rgba(255,255,255,0.12);

        }


        .brand i {

            margin-right: 10px;

            color: #38bdf8;

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

            padding: 35px;

            min-height: 100vh;

        }


        /* =====================================
           GLASS HEADER
        ===================================== */

        .top-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

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


        /* =====================================
           CERTIFICATE CARD
        ===================================== */

        .certificate-card {

            max-width: 1000px;

            margin: auto;

            padding: 40px;

            border-radius: 25px;

            background:
                rgba(255,255,255,0.10);

            backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255,255,255,0.18);

            box-shadow:
                0 20px 50px
                rgba(0,0,0,0.25);

        }


        .certificate-header {

            text-align: center;

            padding-bottom: 25px;

            margin-bottom: 30px;

            border-bottom:
                1px solid
                rgba(255,255,255,0.15);

        }


        .certificate-icon {

            width: 80px;

            height: 80px;

            margin:
                0 auto 15px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            font-size: 32px;

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

            box-shadow:
                0 10px 30px
                rgba(25,135,84,0.30);

        }


        .certificate-header h2 {

            font-weight: 700;

            margin-bottom: 8px;

        }


        .certificate-number {

            color: #94a3b8;

            font-size: 15px;

        }


        /* =====================================
           INFORMATION BOX
        ===================================== */

        .info-box {

            height: 100%;

            padding: 20px;

            border-radius: 15px;

            background:
                rgba(255,255,255,0.08);

            border:
                1px solid
                rgba(255,255,255,0.10);

        }


        .info-label {

            display: block;

            margin-bottom: 8px;

            color: #94a3b8;

            font-size: 13px;

            text-transform: uppercase;

            letter-spacing: 1px;

        }


        .info-value {

            font-size: 17px;

            font-weight: 600;

            color: white;

            word-break: break-word;

        }


        /* =====================================
           STATUS
        ===================================== */

        .status-badge {

            display: inline-block;

            padding:
                7px 14px;

            border-radius: 50px;

            font-size: 13px;

            font-weight: 600;

        }


        .status-pending {

            background:
                rgba(255,193,7,0.20);

            color:
                #ffc107;

            border:
                1px solid
                rgba(255,193,7,0.40);

        }


        .status-released {

            background:
                rgba(25,135,84,0.20);

            color:
                #20c997;

            border:
                1px solid
                rgba(32,201,151,0.40);

        }


        /* =====================================
           BUTTONS
        ===================================== */

        .btn-glass {

            border:
                1px solid
                rgba(255,255,255,0.20);

            background:
                rgba(255,255,255,0.10);

            color: white;

            border-radius: 10px;

            padding:
                10px 18px;

            transition: 0.3s;

        }


        .btn-glass:hover {

            background:
                rgba(255,255,255,0.20);

            color: white;

            transform:
                translateY(-2px);

        }


        .btn-print {

            border: none;

            color: white;

            border-radius: 10px;

            padding:
                10px 18px;

            background:
                linear-gradient(
                    135deg,
                    #0d6efd,
                    #38bdf8
                );

        }


        .btn-download {

            border: none;

            color: white;

            border-radius: 10px;

            padding:
                10px 18px;

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

        }


        /* =====================================
           ACTION AREA
        ===================================== */

        .action-area {

            display: flex;

            flex-wrap: wrap;

            gap: 10px;

            justify-content: center;

            margin-top: 35px;

            padding-top: 25px;

            border-top:
                1px solid
                rgba(255,255,255,0.15);

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

                gap: 15px;

            }


            .certificate-card {

                padding: 25px;

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


    <a href="dashboard.php">

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


    <a
        href="certificates.php"
        class="active"
    >

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

                Certificate Details

            </h2>

            <p>

                View complete certificate information.

            </p>

        </div>


        <a
            href="certificates.php"
            class="btn btn-glass"
        >

            <i class="fa-solid fa-arrow-left me-2"></i>

            Back to Certificates

        </a>


    </div>


    <!-- =====================================
         CERTIFICATE DETAILS
    ===================================== -->

    <div class="certificate-card">


        <div class="certificate-header">


            <div class="certificate-icon">

                <i class="fa-solid fa-file-lines"></i>

            </div>


            <h2>

                <?php
                echo htmlspecialchars(
                    $certificate['certificate_type']
                );
                ?>

            </h2>


            <div class="certificate-number">

                Certificate No:

                <strong>

                    <?php
                    echo htmlspecialchars(
                        $certificate['certificate_number']
                    );
                    ?>

                </strong>

            </div>


        </div>


        <!-- =====================================
             CERTIFICATE INFORMATION
        ===================================== -->

        <div class="row g-4">


            <!-- RESIDENT -->

            <div class="col-md-6">


                <div class="info-box">


                    <span class="info-label">

                        Resident Name

                    </span>


                    <div class="info-value">

                        <i class="fa-solid fa-user me-2"></i>

                        <?php
                        echo htmlspecialchars(
                            $certificate['resident_name']
                        );
                        ?>

                    </div>


                </div>


            </div>


            <!-- TYPE -->

            <div class="col-md-6">


                <div class="info-box">


                    <span class="info-label">

                        Certificate Type

                    </span>


                    <div class="info-value">

                        <i class="fa-solid fa-file me-2"></i>

                        <?php
                        echo htmlspecialchars(
                            $certificate['certificate_type']
                        );
                        ?>

                    </div>


                </div>


            </div>


            <!-- PURPOSE -->

            <div class="col-md-6">


                <div class="info-box">


                    <span class="info-label">

                        Purpose

                    </span>


                    <div class="info-value">

                        <i class="fa-solid fa-bullseye me-2"></i>

                        <?php

                        if (
                            !empty(
                                $certificate['purpose']
                            )
                        ) {

                            echo htmlspecialchars(
                                $certificate['purpose']
                            );

                        } else {

                            echo "Not specified";

                        }

                        ?>

                    </div>


                </div>


            </div>


            <!-- DATE -->

            <div class="col-md-6">


                <div class="info-box">


                    <span class="info-label">

                        Date Issued

                    </span>


                    <div class="info-value">

                        <i class="fa-solid fa-calendar me-2"></i>

                        <?php

                        echo date(
                            "F d, Y",
                            strtotime(
                                $certificate['date_issued']
                            )
                        );

                        ?>

                    </div>


                </div>


            </div>


            <!-- STATUS -->

            <div class="col-md-6">


                <div class="info-box">


                    <span class="info-label">

                        Certificate Status

                    </span>


                    <div class="info-value">


                        <?php if ($certificate['status'] === 'Released'): ?>


                            <span
                                class="
                                    status-badge
                                    status-released
                                "
                            >

                                <i
                                    class="
                                        fa-solid
                                        fa-circle-check
                                        me-1
                                    "
                                ></i>

                                Released

                            </span>


                        <?php else: ?>


                            <span
                                class="
                                    status-badge
                                    status-pending
                                "
                            >

                                <i
                                    class="
                                        fa-solid
                                        fa-clock
                                        me-1
                                    "
                                ></i>

                                Pending

                            </span>


                        <?php endif; ?>


                    </div>


                </div>


            </div>


            <!-- CREATED AT -->

            <div class="col-md-6">


                <div class="info-box">


                    <span class="info-label">

                        Record Created

                    </span>


                    <div class="info-value">

                        <i class="fa-solid fa-clock me-2"></i>

                        <?php

                        echo date(
                            "F d, Y h:i A",
                            strtotime(
                                $certificate['created_at']
                            )
                        );

                        ?>

                    </div>


                </div>


            </div>


        </div>


        <!-- =====================================
             ACTION BUTTONS
        ===================================== -->

        <div class="action-area">


            <!-- PRINT -->

            <a
                href="print_certificate.php?id=<?php echo $certificate['id']; ?>"
                target="_blank"
                class="btn btn-print"
            >

                <i class="fa-solid fa-print me-2"></i>

                Print Certificate

            </a>


            <!-- DOWNLOAD -->

            <a
                href="../actions/download_certificate.php?id=<?php echo $certificate['id']; ?>"
                class="btn btn-download"
            >

                <i class="fa-solid fa-download me-2"></i>

                Download Certificate

            </a>


            <!-- BACK -->

            <a
                href="certificates.php"
                class="btn btn-glass"
            >

                <i class="fa-solid fa-arrow-left me-2"></i>

                Back

            </a>


        </div>


    </div>


</div>


<!-- Bootstrap -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
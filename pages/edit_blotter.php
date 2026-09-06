<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';


/* =========================================
   USER
========================================= */

$username = $_SESSION['username'] ?? 'Administrator';


/* =========================================
   GET ID
========================================= */

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($id <= 0) {

    $_SESSION['error'] = "Invalid blotter record.";

    header("Location: blotter.php");
    exit;
}


/* =========================================
   GET BLOTTER DATA
========================================= */

$sql = "
    SELECT *
    FROM blotter
    WHERE id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    $_SESSION['error'] = "Blotter record not found.";

    header("Location: blotter.php");
    exit;
}


$blotter = $result->fetch_assoc();

$stmt->close();


/* =========================================
   ERROR MESSAGE
========================================= */

$error = $_SESSION['error'] ?? '';

unset($_SESSION['error']);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Blotter - Barangay System</title>


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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            min-height: 100vh;

            font-family: Arial, sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #071a2b,
                    #0b2d3b,
                    #123524,
                    #071a2b
                );

            background-attachment: fixed;

            color: white;
        }


        /* =====================================
           SIDEBAR
        ===================================== */

        .sidebar {

            width: 260px;

            height: 100vh;

            position: fixed;

            top: 0;

            left: 0;

            padding: 25px 15px;

            background:
                rgba(5, 20, 30, 0.78);

            backdrop-filter:
                blur(20px);

            border-right:
                1px solid
                rgba(255,255,255,.10);

            z-index: 1000;
        }


        .brand {

            padding: 15px;

            font-size: 20px;

            font-weight: 700;

            border-bottom:
                1px solid
                rgba(255,255,255,.10);

            margin-bottom: 25px;
        }


        .brand i {

            color: #20c997;

            margin-right: 10px;
        }


        .menu-title {

            font-size: 11px;

            color: #9fb3c8;

            text-transform: uppercase;

            letter-spacing: 1px;

            margin:
                25px 15px 10px;
        }


        .sidebar a {

            display: flex;

            align-items: center;

            text-decoration: none;

            color: #d6e4ef;

            padding: 12px 15px;

            border-radius: 12px;

            margin-bottom: 6px;

            transition: 0.3s;
        }


        .sidebar a i {

            width: 25px;
        }


        .sidebar a:hover,
        .sidebar a.active {

            background:
                rgba(32,201,151,.18);

            color: white;

            box-shadow:
                inset 0 0 0 1px
                rgba(32,201,151,.15);
        }


        .logout-link {

            margin-top: 30px;

            background:
                rgba(239,68,68,.10);

            color:
                #ffb4b4 !important;
        }


        .logout-link:hover {

            background:
                rgba(239,68,68,.22);
        }


        /* =====================================
           MAIN
        ===================================== */

        .main-content {

            margin-left: 260px;

            min-height: 100vh;

            padding: 30px;
        }


        /* =====================================
           HEADER
        ===================================== */

        .top-header {

            background:
                rgba(255,255,255,.08);

            backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255,255,255,.12);

            padding: 20px 25px;

            border-radius: 20px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 30px;

            box-shadow:
                0 15px 35px
                rgba(0,0,0,.15);
        }


        .page-title h2 {

            font-weight: 700;

            margin-bottom: 5px;
        }


        .page-title p {

            color:
                rgba(255,255,255,.65);

            margin: 0;

            font-size: 14px;
        }


        /* =====================================
           USER PROFILE
        ===================================== */

        .user-profile {

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .user-avatar {

            width: 45px;

            height: 45px;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            font-weight: bold;

            font-size: 18px;
        }


        .user-info strong {

            display: block;
        }


        .user-info small {

            color:
                rgba(255,255,255,.60);
        }


        /* =====================================
           GLASS FORM CARD
        ===================================== */

        .glass-card {

            background:
                rgba(255,255,255,.08);

            backdrop-filter:
                blur(20px);

            border:
                1px solid
                rgba(255,255,255,.12);

            border-radius: 22px;

            padding: 30px;

            box-shadow:
                0 15px 35px
                rgba(0,0,0,.15);
        }


        .form-header {

            margin-bottom: 30px;

            padding-bottom: 20px;

            border-bottom:
                1px solid
                rgba(255,255,255,.10);
        }


        .form-header h4 {

            font-weight: 700;

            margin-bottom: 5px;
        }


        .form-header p {

            color:
                rgba(255,255,255,.60);

            margin: 0;
        }


        /* =====================================
           FORM
        ===================================== */

        .form-label {

            font-weight: 600;

            margin-bottom: 8px;
        }


        .form-control,
        .form-select {

            background:
                rgba(255,255,255,.08);

            border:
                1px solid
                rgba(255,255,255,.15);

            color: white;

            padding: 12px;

            border-radius: 10px;
        }


        .form-control:focus,
        .form-select:focus {

            background:
                rgba(255,255,255,.12);

            color: white;

            border-color:
                #20c997;

            box-shadow:
                0 0 0 .2rem
                rgba(32,201,151,.20);
        }


        .form-control::placeholder {

            color:
                rgba(255,255,255,.45);
        }


        .form-select option {

            background: #102a43;

            color: white;
        }


        textarea.form-control {

            resize: vertical;

            min-height: 140px;
        }


        /* =====================================
           BUTTONS
        ===================================== */

        .btn-update {

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

            border: none;

            padding:
                12px 25px;

            font-weight: 600;

            color: white;

            border-radius: 10px;

            transition: .3s;
        }


        .btn-update:hover {

            color: white;

            transform:
                translateY(-2px);

            box-shadow:
                0 10px 25px
                rgba(32,201,151,.20);
        }


        .btn-back {

            padding:
                12px 25px;

            border-radius: 10px;

            font-weight: 600;
        }


        /* =====================================
           MOBILE
        ===================================== */

        @media (max-width: 992px) {

            .sidebar {

                width: 80px;

                padding:
                    20px 10px;
            }


            .brand span,
            .sidebar a span,
            .menu-title {

                display: none;
            }


            .brand {

                text-align: center;

                padding:
                    15px 5px;
            }


            .brand i {

                margin: 0;
            }


            .sidebar a {

                justify-content: center;
            }


            .sidebar a i {

                width: auto;
            }


            .main-content {

                margin-left: 80px;
            }

        }


        @media (max-width: 768px) {

            .sidebar {

                display: none;
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


            .glass-card {

                padding: 20px;
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


    <a
        href="blotter.php"
        class="active"
    >

        <i class="fa-solid fa-scale-balanced"></i>

        <span>Blotter Records</span>

    </a>


    <div class="menu-title">

        Account

    </div>


    <a href="settings.php">

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

            <h2>

                Edit Blotter Record

            </h2>

            <p>

                Update barangay incident and complaint information.

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


            <div class="user-info">

                <strong>

                    <?php
                    echo htmlspecialchars($username);
                    ?>

                </strong>


                <small>

                    System Administrator

                </small>

            </div>


        </div>


    </div>


    <!-- ERROR MESSAGE -->

    <?php if (!empty($error)): ?>

        <div
            class="
                alert
                alert-danger
                alert-dismissible
                fade
                show
            "
        >

            <?php
            echo htmlspecialchars($error);
            ?>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- EDIT FORM -->

    <div class="glass-card">


        <div class="form-header">

            <h4>

                <i
                    class="
                        fa-solid
                        fa-pen-to-square
                        me-2
                    "
                ></i>

                Blotter Information

            </h4>


            <p>

                Modify the information below and save your changes.

            </p>

        </div>


        <form
            action="../actions/update_blotter.php"
            method="POST"
        >


            <!-- IMPORTANT RECORD ID -->

            <input
                type="hidden"
                name="id"
                value="<?php echo $blotter['id']; ?>"
            >


            <div class="row g-4">


                <!-- BLOTTER NUMBER -->

                <div class="col-md-6">


                    <label class="form-label">

                        Blotter Number

                    </label>


                    <input
                        type="text"
                        name="blotter_number"
                        class="form-control"
                        value="<?php echo htmlspecialchars($blotter['blotter_number']); ?>"
                        required
                    >


                </div>


                <!-- DATE -->

                <div class="col-md-6">


                    <label class="form-label">

                        Date

                    </label>


                    <input
                        type="date"
                        name="date"
                        class="form-control"
                        value="<?php echo htmlspecialchars($blotter['date']); ?>"
                        required
                    >


                </div>


                <!-- COMPLAINANT -->

                <div class="col-md-6">


                    <label class="form-label">

                        Complainant

                    </label>


                    <input
                        type="text"
                        name="complainant"
                        class="form-control"
                        value="<?php echo htmlspecialchars($blotter['complainant']); ?>"
                        required
                    >


                </div>


                <!-- RESPONDENT -->

                <div class="col-md-6">


                    <label class="form-label">

                        Respondent

                    </label>


                    <input
                        type="text"
                        name="respondent"
                        class="form-control"
                        value="<?php echo htmlspecialchars($blotter['respondent']); ?>"
                        required
                    >


                </div>


                <!-- INCIDENT TYPE -->

                <div class="col-md-6">


                    <label class="form-label">

                        Incident Type

                    </label>


                    <input
                        type="text"
                        name="incident_type"
                        class="form-control"
                        value="<?php echo htmlspecialchars($blotter['incident_type']); ?>"
                        required
                    >


                </div>


                <!-- STATUS -->

                <div class="col-md-6">


                    <label class="form-label">

                        Status

                    </label>


                    <select
                        name="status"
                        class="form-select"
                        required
                    >


                        <option
                            value="Pending"
                            <?php
                            echo $blotter['status'] === 'Pending'
                                ? 'selected'
                                : '';
                            ?>
                        >

                            Pending

                        </option>


                        <option
                            value="Ongoing"
                            <?php
                            echo $blotter['status'] === 'Ongoing'
                                ? 'selected'
                                : '';
                            ?>
                        >

                            Ongoing

                        </option>


                        <option
                            value="Resolved"
                            <?php
                            echo $blotter['status'] === 'Resolved'
                                ? 'selected'
                                : '';
                            ?>
                        >

                            Resolved

                        </option>


                    </select>


                </div>


                <!-- LOCATION -->

                <div class="col-12">


                    <label class="form-label">

                        Location

                    </label>


                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        value="<?php echo htmlspecialchars($blotter['location']); ?>"
                        required
                    >


                </div>


                <!-- DESCRIPTION -->

                <div class="col-12">


                    <label class="form-label">

                        Description

                    </label>


                    <textarea
                        name="description"
                        class="form-control"
                        required
                    ><?php echo htmlspecialchars($blotter['description']); ?></textarea>


                </div>


            </div>


            <!-- BUTTONS -->

            <div
                class="
                    d-flex
                    justify-content-end
                    gap-2
                    mt-4
                "
            >


                <a
                    href="blotter.php"
                    class="
                        btn
                        btn-secondary
                        btn-back
                    "
                >

                    <i
                        class="
                            fa-solid
                            fa-arrow-left
                            me-2
                        "
                    ></i>

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-update"
                >

                    <i
                        class="
                            fa-solid
                            fa-floppy-disk
                            me-2
                        "
                    ></i>

                    Update Blotter

                </button>


            </div>


        </form>


    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
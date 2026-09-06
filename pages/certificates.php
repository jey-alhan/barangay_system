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
   GET USERNAME
========================================= */

$username = $_SESSION['username'] ?? 'Administrator';


/* =========================================
   GET SUCCESS / ERROR MESSAGE
========================================= */

$success = $_SESSION['success'] ?? '';

$error = $_SESSION['error'] ?? '';

unset($_SESSION['success']);
unset($_SESSION['error']);


/* =========================================
   GET CERTIFICATES
========================================= */

$sql = "
    SELECT *
    FROM certificates
    ORDER BY id DESC
";


$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Certificates - Barangay System</title>


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

        font-family:
            Arial,
            sans-serif;

        min-height: 100vh;

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
            rgba(5, 20, 30, 0.75);

        backdrop-filter:
            blur(20px);

        border-right:
            1px solid
            rgba(255,255,255,0.10);

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
            25px
            15px
            10px;

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

        font-size: 16px;

    }


    .sidebar a:hover,
    .sidebar a.active {

        background:
            rgba(32,201,151,0.18);

        color: white;

        box-shadow:
            inset 0 0 0 1px
            rgba(32,201,151,0.20);

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
       MAIN CONTENT
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
            rgba(255,255,255,0.08);

        backdrop-filter:
            blur(20px);

        border:
            1px solid
            rgba(255,255,255,0.12);

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

    }


    .user-info {

        line-height: 1.3;

    }


    .user-info small {

        color:
            rgba(255,255,255,.60);

    }


    /* =====================================
       GLASS CARD
    ===================================== */

    .glass-card {

        background:
            rgba(255,255,255,0.08);

        backdrop-filter:
            blur(20px);

        border:
            1px solid
            rgba(255,255,255,0.12);

        border-radius: 20px;

        padding: 25px;

        box-shadow:
            0 15px 35px
            rgba(0,0,0,.15);

    }


    /* =====================================
       BUTTONS
    ===================================== */

    .btn-add {

        background:
            linear-gradient(
                135deg,
                #198754,
                #20c997
            );

        border: none;

        color: white;

        font-weight: 600;

        padding:
            10px
            20px;

        border-radius: 10px;

    }


    .btn-add:hover {

        color: white;

        transform:
            translateY(-2px);

    }


    .action-btn {

        width: 35px;

        height: 35px;

        border-radius: 8px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        text-decoration: none;

        color: white;

        margin: 2px;

    }


    .btn-view {

        background:
            #0d6efd;

    }


    .btn-print {

        background:
            #6f42c1;

    }


    .btn-download {

        background:
            #198754;

    }


    .btn-delete {

        background:
            #dc3545;

        border: none;

    }


    /* =====================================
       TABLE
    ===================================== */

    .table-responsive {

        border-radius: 15px;

        overflow: hidden;

    }


    .table {

        margin-bottom: 0;

        color: white;

    }


    .table thead {

        background:
            rgba(0,0,0,.20);

    }


    .table thead th {

        color: white;

        border-color:
            rgba(255,255,255,.10);

        padding: 15px;

        font-size: 13px;

        white-space: nowrap;

    }


    .table tbody td {

        color:
            rgba(255,255,255,.85);

        border-color:
            rgba(255,255,255,.08);

        padding: 15px;

        vertical-align: middle;

    }


    .table tbody tr {

        transition: 0.2s;

    }


    .table tbody tr:hover {

        background:
            rgba(255,255,255,.05);

    }


    /* =====================================
       STATUS
    ===================================== */

    .status {

        padding:
            6px
            12px;

        border-radius: 20px;

        font-size: 12px;

        font-weight: 600;

        display: inline-block;

    }


    .status-pending {

        background:
            rgba(255,193,7,.18);

        color:
            #ffd65a;

    }


    .status-released {

        background:
            rgba(25,135,84,.20);

        color:
            #6ee7a8;

    }


    /* =====================================
       MODAL
    ===================================== */

    .modal-content {

        background:
            #102a43;

        color: white;

        border:
            1px solid
            rgba(255,255,255,.12);

        border-radius: 20px;

    }


    .modal-header,
    .modal-footer {

        border-color:
            rgba(255,255,255,.10);

    }


    .form-control,
    .form-select {

        background:
            rgba(255,255,255,.08);

        border:
            1px solid
            rgba(255,255,255,.18);

        color: white;

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


    .form-select option {

        background: #102a43;

        color: white;

    }


    /* =====================================
       MOBILE
    ===================================== */

    @media (max-width: 992px) {

        .sidebar {

            width: 80px;

            padding:
                20px
                10px;

        }


        .brand span,
        .sidebar a span,
        .menu-title {

            display: none;

        }


        .brand {

            text-align: center;

            padding:
                15px
                5px;

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


<a
    href="certificates.php"
    class="active"
>

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

            Certificate Management

        </h2>


        <p>

            Create and manage barangay certificates.

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


<!-- =====================================
     SUCCESS MESSAGE
===================================== -->

<?php if (!empty($success)): ?>

    <div
        class="
            alert
            alert-success
            alert-dismissible
            fade
            show
        "
    >

        <i class="fa-solid fa-circle-check me-2"></i>

        <?php
        echo htmlspecialchars($success);
        ?>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

<?php endif; ?>


<!-- =====================================
     ERROR MESSAGE
===================================== -->

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

        <i class="fa-solid fa-circle-exclamation me-2"></i>

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


<!-- =====================================
     CERTIFICATE CARD
===================================== -->

<div class="glass-card">


    <div
        class="
            d-flex
            justify-content-between
            align-items-center
            flex-wrap
            gap-3
            mb-4
        "
    >


        <div>

            <h4 class="mb-1">

                Certificates

            </h4>


            <small
                style="
                    color:
                    rgba(255,255,255,.60);
                "
            >

                View and manage issued barangay certificates.

            </small>

        </div>


        <button
            class="btn btn-add"
            data-bs-toggle="modal"
            data-bs-target="#addCertificateModal"
        >

            <i
                class="
                    fa-solid
                    fa-plus
                    me-2
                "
            ></i>

            Create Certificate

        </button>


    </div>


    <!-- TABLE -->

    <div class="table-responsive">


        <table class="table">


            <thead>

                <tr>

                    <th>#</th>

                    <th>Certificate No.</th>

                    <th>Resident</th>

                    <th>Type</th>

                    <th>Date Issued</th>

                    <th>Status</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>


                <?php if ($result && $result->num_rows > 0): ?>


                    <?php
                    $number = 1;
                    ?>


                    <?php while ($certificate = $result->fetch_assoc()): ?>


                        <?php

                        $statusClass = '';

                        if (
                            $certificate['status'] === 'Pending'
                        ) {

                            $statusClass =
                                'status-pending';

                        }

                        elseif (
                            $certificate['status'] === 'Released'
                        ) {

                            $statusClass =
                                'status-released';

                        }

                        ?>


                        <tr>


                            <td>

                                <?php
                                echo $number++;
                                ?>

                            </td>


                            <td>

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $certificate['certificate_number']
                                    );

                                    ?>

                                </strong>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $certificate['resident_name']
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $certificate['certificate_type']
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo date(
                                    "M d, Y",
                                    strtotime(
                                        $certificate['date_issued']
                                    )
                                );

                                ?>

                            </td>


                            <td>

                                <span
                                    class="
                                        status
                                        <?php
                                        echo $statusClass;
                                        ?>
                                    "
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        $certificate['status']
                                    );

                                    ?>

                                </span>

                            </td>


                            <td>


                                <!-- VIEW -->

                                <a
                                    href="view_certificate.php?id=<?php echo $certificate['id']; ?>"
                                    class="
                                        action-btn
                                        btn-view
                                    "
                                    title="View"
                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-eye
                                        "
                                    ></i>

                                </a>


                                <!-- PRINT -->

                                <a
                                    href="print_certificate.php?id=<?php echo $certificate['id']; ?>"
                                    target="_blank"
                                    class="
                                        action-btn
                                        btn-print
                                    "
                                    title="Print"
                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-print
                                        "
                                    ></i>

                                </a>


                                <!-- DOWNLOAD -->

                                <a
                                    href="../actions/download_certificate.php?id=<?php echo $certificate['id']; ?>"
                                    class="
                                        action-btn
                                        btn-download
                                    "
                                    title="Download"
                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-download
                                        "
                                    ></i>

                                </a>


                                <!-- DELETE -->

                                <a
                                    href="../actions/delete_certificate.php?id=<?php echo $certificate['id']; ?>"
                                    class="
                                        action-btn
                                        btn-delete
                                    "
                                    title="Delete"
                                    onclick="
                                        return confirm(
                                            'Are you sure you want to delete this certificate?'
                                        );
                                    "
                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-trash
                                        "
                                    ></i>

                                </a>


                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="7"
                            class="
                                text-center
                                py-5
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-file-circle-xmark
                                    fs-2
                                    mb-3
                                "
                            ></i>


                            <br>


                            No certificates found.

                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>


        </table>


    </div>


</div>


</div>

<!-- =====================================
     ADD CERTIFICATE MODAL
===================================== -->

<div
    class="modal fade"
    id="addCertificateModal"
    tabindex="-1"
>


<div class="modal-dialog modal-lg">


    <div class="modal-content">


        <div class="modal-header">


            <h5 class="modal-title">

                <i
                    class="
                        fa-solid
                        fa-file-circle-plus
                        me-2
                    "
                ></i>

                Create Certificate

            </h5>


            <button
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal"
            ></button>


        </div>


        <form
            action="../actions/add_certificate.php"
            method="POST"
        >


            <div class="modal-body">


                <div class="row g-3">


                    <div class="col-md-6">


                        <label class="form-label">

                            Certificate Number

                        </label>


                        <input
                            type="text"
                            name="certificate_number"
                            class="form-control"
                            placeholder="Example: CERT-001"
                            required
                        >


                    </div>


                    <div class="col-md-6">


                        <label class="form-label">

                            Resident Name

                        </label>


                        <input
                            type="text"
                            name="resident_name"
                            class="form-control"
                            placeholder="Enter resident name"
                            required
                        >


                    </div>


                    <div class="col-md-6">


                        <label class="form-label">

                            Certificate Type

                        </label>


                        <select
                            name="certificate_type"
                            class="form-select"
                            required
                        >


                            <option value="">

                                Select certificate type

                            </option>


                            <option value="Barangay Clearance">

                                Barangay Clearance

                            </option>


                            <option value="Certificate of Residency">

                                Certificate of Residency

                            </option>


                            <option value="Certificate of Indigency">

                                Certificate of Indigency

                            </option>


                            <option value="Business Clearance">

                                Business Clearance

                            </option>


                            <option value="Other">

                                Other

                            </option>


                        </select>


                    </div>


                    <div class="col-md-6">


                        <label class="form-label">

                            Date Issued

                        </label>


                        <input
                            type="date"
                            name="date_issued"
                            class="form-control"
                            value="<?php echo date('Y-m-d'); ?>"
                            required
                        >


                    </div>


                    <div class="col-md-6">


                        <label class="form-label">

                            Purpose

                        </label>


                        <input
                            type="text"
                            name="purpose"
                            class="form-control"
                            placeholder="Purpose of certificate"
                        >


                    </div>


                    <div class="col-md-6">


                        <label class="form-label">

                            Status

                        </label>


                        <select
                            name="status"
                            class="form-select"
                            required
                        >


                            <option value="Pending">

                                Pending

                            </option>


                            <option value="Released">

                                Released

                            </option>


                        </select>


                    </div>


                </div>


            </div>


            <div class="modal-footer">


                <button
                    type="button"
                    class="
                        btn
                        btn-secondary
                    "
                    data-bs-dismiss="modal"
                >

                    Cancel

                </button>


                <button
                    type="submit"
                    class="
                        btn
                        btn-success
                    "
                >

                    <i
                        class="
                            fa-solid
                            fa-floppy-disk
                            me-2
                        "
                    ></i>

                    Create Certificate

                </button>


            </div>


        </form>


    </div>


</div>

</div>

<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>

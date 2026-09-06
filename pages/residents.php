<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}

require_once __DIR__ . '/../config/database.php';


/* =========================================
   SESSION
========================================= */

$username = $_SESSION['username'] ?? 'Administrator';


/* =========================================
   SEARCH
========================================= */

$search = trim($_GET['search'] ?? '');

$residents = [];


if (!empty($search)) {

    $sql = "

        SELECT *
        FROM residents

        WHERE

            first_name LIKE ?

            OR middle_name LIKE ?

            OR last_name LIKE ?

            OR contact_number LIKE ?

            OR address LIKE ?

        ORDER BY id DESC

    ";


    $stmt = $conn->prepare($sql);


    $searchValue = "%" . $search . "%";


    $stmt->bind_param(

        "sssss",

        $searchValue,
        $searchValue,
        $searchValue,
        $searchValue,
        $searchValue

    );


    $stmt->execute();

    $result = $stmt->get_result();


} else {

    $sql = "

        SELECT *
        FROM residents
        ORDER BY id DESC

    ";


    $result = $conn->query($sql);

}


/* =========================================
   STORE RESIDENTS
========================================= */

if ($result) {

    while ($row = $result->fetch_assoc()) {

        $residents[] = $row;

    }

}


/* =========================================
   MESSAGES
========================================= */

$success = $_SESSION['success'] ?? '';

$error = $_SESSION['error'] ?? '';


unset($_SESSION['success']);
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

<title>
    Residents - Barangay System
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

        transition: 0.3s;

    }


    .btn-add:hover {

        color: white;

        transform:
            translateY(-2px);

    }


    /* =====================================
       SEARCH
    ===================================== */

    .search-input {

        background:
            rgba(255,255,255,.08);

        border:
            1px solid
            rgba(255,255,255,.18);

        color: white;

    }


    .search-input:focus {

        background:
            rgba(255,255,255,.12);

        color: white;

        border-color:
            #20c997;

        box-shadow:
            0 0 0 .2rem
            rgba(32,201,151,.20);

    }


    .search-input::placeholder {

        color:
            rgba(255,255,255,.50);

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


    .resident-name {

        font-weight: 600;

        color: white;

    }


    .resident-id {

        color:
            #9fb3c8;

    }


    /* =====================================
       ACTION BUTTONS
    ===================================== */

    .btn-action {

        width: 35px;

        height: 35px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 8px;

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
       ALERT
    ===================================== */

    .alert {

        border-radius: 15px;

        border: none;

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


        .user-profile {

            width: 100%;

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


<a
    href="residents.php"
    class="active"
>

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

            Residents Management

        </h2>


        <p>

            Manage all registered barangay residents.

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
     RESIDENTS CARD
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

                Registered Residents

            </h4>


            <small
                style="
                    color:
                    rgba(255,255,255,.60);
                "
            >

                View, search, add, edit, and manage resident information.

            </small>

        </div>


        <button
            type="button"
            class="btn btn-add"
            data-bs-toggle="modal"
            data-bs-target="#addResidentModal"
        >

            <i
                class="
                    fa-solid
                    fa-user-plus
                    me-2
                "
            ></i>

            Add Resident

        </button>


    </div>


    <!-- SEARCH -->

    <form
        method="GET"
        class="mb-4"
    >

        <div class="input-group">


            <input
                type="text"
                name="search"
                class="form-control search-input"
                placeholder="Search by name, contact number, or address..."
                value="<?php echo htmlspecialchars($search); ?>"
            >


            <button
                type="submit"
                class="btn btn-add"
            >

                <i
                    class="
                        fa-solid
                        fa-magnifying-glass
                        me-2
                    "
                ></i>

                Search

            </button>


        </div>


    </form>


    <!-- TABLE -->

    <div class="table-responsive">


        <table class="table">


            <thead>

                <tr>

                    <th>ID</th>

                    <th>Full Name</th>

                    <th>Gender</th>

                    <th>Birth Date</th>

                    <th>Civil Status</th>

                    <th>Contact</th>

                    <th>Address</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>


                <?php if (count($residents) > 0): ?>


                    <?php foreach ($residents as $resident): ?>


                        <tr>


                            <td class="resident-id">

                                #<?php
                                echo htmlspecialchars(
                                    $resident['id']
                                );
                                ?>

                            </td>


                            <td class="resident-name">

                                <?php

                                echo htmlspecialchars(

                                    trim(

                                        ($resident['first_name'] ?? '')
                                        . " "
                                        . ($resident['middle_name'] ?? '')
                                        . " "
                                        . ($resident['last_name'] ?? '')

                                    )

                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $resident['gender'] ?? '-'
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo !empty(
                                    $resident['birth_date']
                                )

                                    ? date(
                                        "M d, Y",
                                        strtotime(
                                            $resident['birth_date']
                                        )
                                    )

                                    : '-';

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $resident['civil_status'] ?? '-'
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $resident['contact_number'] ?? '-'
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $resident['address'] ?? '-'
                                );

                                ?>

                            </td>


                            <td>


                                <!-- EDIT -->

                                <button

                                    type="button"

                                    class="
                                        btn
                                        btn-primary
                                        btn-sm
                                        btn-action
                                    "

                                    data-bs-toggle="modal"

                                    data-bs-target="#editModal<?php echo $resident['id']; ?>"

                                    title="Edit Resident"

                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-pen
                                        "
                                    ></i>

                                </button>


                                <!-- DELETE -->

                                <a

                                    href="../actions/delete_resident.php?id=<?php echo $resident['id']; ?>"

                                    class="
                                        btn
                                        btn-danger
                                        btn-sm
                                        btn-action
                                    "

                                    onclick="return confirm('Are you sure you want to delete this resident?');"

                                    title="Delete Resident"

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


                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="8"
                            class="
                                text-center
                                py-5
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-users
                                    fs-2
                                    mb-3
                                "
                            ></i>


                            <br>


                            No residents found.

                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>


        </table>


    </div>


</div>

</div>

<!-- =====================================
     ADD RESIDENT MODAL
===================================== -->

<div
    class="modal fade"
    id="addResidentModal"
    tabindex="-1"
>

<div class="modal-dialog modal-lg">


    <div class="modal-content">


        <form
            action="../actions/add_resident.php"
            method="POST"
        >


            <div class="modal-header">


                <h5 class="modal-title">

                    <i
                        class="
                            fa-solid
                            fa-user-plus
                            me-2
                        "
                    ></i>

                    Add New Resident

                </h5>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>


            </div>


            <div class="modal-body">


                <div class="row g-3">


                    <div class="col-md-4">


                        <label class="form-label">

                            First Name *

                        </label>


                        <input
                            type="text"
                            name="first_name"
                            class="form-control"
                            required
                        >


                    </div>


                    <div class="col-md-4">


                        <label class="form-label">

                            Middle Name

                        </label>


                        <input
                            type="text"
                            name="middle_name"
                            class="form-control"
                        >


                    </div>


                    <div class="col-md-4">


                        <label class="form-label">

                            Last Name *

                        </label>


                        <input
                            type="text"
                            name="last_name"
                            class="form-control"
                            required
                        >


                    </div>


                    <div class="col-md-4">


                        <label class="form-label">

                            Gender

                        </label>


                        <select
                            name="gender"
                            class="form-select"
                        >

                            <option value="">

                                Select Gender

                            </option>


                            <option value="Male">

                                Male

                            </option>


                            <option value="Female">

                                Female

                            </option>


                        </select>


                    </div>


                    <div class="col-md-4">


                        <label class="form-label">

                            Birth Date

                        </label>


                        <input
                            type="date"
                            name="birth_date"
                            class="form-control"
                        >


                    </div>


                    <div class="col-md-4">


                        <label class="form-label">

                            Civil Status

                        </label>


                        <select
                            name="civil_status"
                            class="form-select"
                        >

                            <option value="">

                                Select Status

                            </option>


                            <option value="Single">

                                Single

                            </option>


                            <option value="Married">

                                Married

                            </option>


                            <option value="Widowed">

                                Widowed

                            </option>


                            <option value="Separated">

                                Separated

                            </option>


                        </select>


                    </div>


                    <div class="col-md-6">


                        <label class="form-label">

                            Contact Number

                        </label>


                        <input
                            type="text"
                            name="contact_number"
                            class="form-control"
                            placeholder="09XXXXXXXXX"
                        >


                    </div>


                    <div class="col-md-6">


                        <label class="form-label">

                            Address

                        </label>


                        <input
                            type="text"
                            name="address"
                            class="form-control"
                        >


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
                    class="btn btn-add"
                >

                    <i
                        class="
                            fa-solid
                            fa-user-plus
                            me-2
                        "
                    ></i>

                    Add Resident

                </button>


            </div>


        </form>


    </div>


</div>

</div>

<!-- =====================================
     EDIT RESIDENT MODALS
===================================== -->

<?php foreach ($residents as $resident): ?>

<div
    class="modal fade"
    id="editModal<?php echo $resident['id']; ?>"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <form
                action="../actions/update_resident.php"
                method="POST"
            >


                <div class="modal-header">


                    <h5 class="modal-title">

                        <i
                            class="
                                fa-solid
                                fa-user-pen
                                me-2
                            "
                        ></i>

                        Edit Resident

                    </h5>


                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                    ></button>


                </div>


                <div class="modal-body">


                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $resident['id']; ?>"
                    >


                    <div class="row g-3">


                        <!-- FIRST NAME -->

                        <div class="col-md-4">


                            <label class="form-label">

                                First Name *

                            </label>


                            <input
                                type="text"
                                name="first_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($resident['first_name'] ?? ''); ?>"
                                required
                            >


                        </div>


                        <!-- MIDDLE NAME -->

                        <div class="col-md-4">


                            <label class="form-label">

                                Middle Name

                            </label>


                            <input
                                type="text"
                                name="middle_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($resident['middle_name'] ?? ''); ?>"
                            >


                        </div>


                        <!-- LAST NAME -->

                        <div class="col-md-4">


                            <label class="form-label">

                                Last Name *

                            </label>


                            <input
                                type="text"
                                name="last_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($resident['last_name'] ?? ''); ?>"
                                required
                            >


                        </div>


                        <!-- GENDER -->

                        <div class="col-md-4">


                            <label class="form-label">

                                Gender

                            </label>


                            <select
                                name="gender"
                                class="form-select"
                            >

                                <option value="">

                                    Select Gender

                                </option>


                                <option
                                    value="Male"
                                    <?php echo ($resident['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>
                                >

                                    Male

                                </option>


                                <option
                                    value="Female"
                                    <?php echo ($resident['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>
                                >

                                    Female

                                </option>


                            </select>


                        </div>


                        <!-- BIRTH DATE -->

                        <div class="col-md-4">


                            <label class="form-label">

                                Birth Date

                            </label>


                            <input
                                type="date"
                                name="birth_date"
                                class="form-control"
                                value="<?php echo htmlspecialchars($resident['birth_date'] ?? ''); ?>"
                            >


                        </div>


                        <!-- CIVIL STATUS -->

                        <div class="col-md-4">


                            <label class="form-label">

                                Civil Status

                            </label>


                            <select
                                name="civil_status"
                                class="form-select"
                            >

                                <option value="">

                                    Select Status

                                </option>


                                <option
                                    value="Single"
                                    <?php echo ($resident['civil_status'] ?? '') === 'Single' ? 'selected' : ''; ?>
                                >

                                    Single

                                </option>


                                <option
                                    value="Married"
                                    <?php echo ($resident['civil_status'] ?? '') === 'Married' ? 'selected' : ''; ?>
                                >

                                    Married

                                </option>


                                <option
                                    value="Widowed"
                                    <?php echo ($resident['civil_status'] ?? '') === 'Widowed' ? 'selected' : ''; ?>
                                >

                                    Widowed

                                </option>


                                <option
                                    value="Separated"
                                    <?php echo ($resident['civil_status'] ?? '') === 'Separated' ? 'selected' : ''; ?>
                                >

                                    Separated

                                </option>


                            </select>


                        </div>


                        <!-- CONTACT -->

                        <div class="col-md-6">


                            <label class="form-label">

                                Contact Number

                            </label>


                            <input
                                type="text"
                                name="contact_number"
                                class="form-control"
                                value="<?php echo htmlspecialchars($resident['contact_number'] ?? ''); ?>"
                            >


                        </div>


                        <!-- ADDRESS -->

                        <div class="col-md-6">


                            <label class="form-label">

                                Address

                            </label>


                            <input
                                type="text"
                                name="address"
                                class="form-control"
                                value="<?php echo htmlspecialchars($resident['address'] ?? ''); ?>"
                            >


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
                        class="btn btn-add"
                    >

                        <i
                            class="
                                fa-solid
                                fa-floppy-disk
                                me-2
                            "
                        ></i>

                        Update Resident

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>

<?php endforeach; ?>

<!-- =====================================
     BOOTSTRAP JAVASCRIPT
===================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>

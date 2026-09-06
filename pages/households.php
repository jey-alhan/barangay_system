<?php

session_start();

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
   GET HOUSEHOLDS
========================================= */

$search = trim($_GET['search'] ?? '');

$households = [];


if (!empty($search)) {

    $sql = "
        SELECT *
        FROM households
        WHERE
            household_number LIKE ?
            OR head_name LIKE ?
            OR address LIKE ?
        ORDER BY id DESC
    ";


    $stmt = $conn->prepare($sql);


    $searchValue = "%" . $search . "%";


    $stmt->bind_param(
        "sss",
        $searchValue,
        $searchValue,
        $searchValue
    );


    $stmt->execute();


    $result = $stmt->get_result();

} else {

    $sql = "
        SELECT *
        FROM households
        ORDER BY id DESC
    ";


    $result = $conn->query($sql);

}


if ($result) {

    while ($row = $result->fetch_assoc()) {

        $households[] = $row;

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

<title>Households - Barangay System</title>


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


    .household-number {

        color:
            #6ee7a8;

        font-weight: 600;

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


    .form-control {

        background:
            rgba(255,255,255,.08);

        border:
            1px solid
            rgba(255,255,255,.18);

        color: white;

    }


    .form-control:focus {

        background:
            rgba(255,255,255,.12);

        color: white;

        border-color:
            #20c997;

        box-shadow:
            0 0 0 .2rem
            rgba(32,201,151,.20);

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


<a
    href="households.php"
    class="active"
>

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

            Households

        </h2>


        <p>

            Manage registered barangay households and family information.

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


<!-- SUCCESS MESSAGE -->

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


<!-- =====================================
     HOUSEHOLD CARD
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

                Household Records

            </h4>


            <small
                style="
                    color:
                    rgba(255,255,255,.60);
                "
            >

                View and manage registered barangay households.

            </small>

        </div>


        <button
            class="btn btn-add"
            data-bs-toggle="modal"
            data-bs-target="#addHouseholdModal"
        >

            <i
                class="
                    fa-solid
                    fa-plus
                    me-2
                "
            ></i>

            Add Household

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
                placeholder="Search household number, head name, or address..."
                value="<?php echo htmlspecialchars($search); ?>"
            >


            <button
                type="submit"
                class="btn btn-add"
            >

                <i class="fa-solid fa-magnifying-glass me-2"></i>

                Search

            </button>


            <?php if (!empty($search)): ?>

                <a
                    href="households.php"
                    class="btn btn-secondary"
                >

                    Clear

                </a>

            <?php endif; ?>


        </div>


    </form>


    <!-- TABLE -->

    <div class="table-responsive">


        <table class="table">


            <thead>

                <tr>

                    <th>#</th>

                    <th>Household No.</th>

                    <th>Head of Household</th>

                    <th>Address</th>

                    <th>Members</th>

                    <th>Date Created</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>


                <?php if (count($households) > 0): ?>


                    <?php
                    $number = 1;
                    ?>


                    <?php foreach ($households as $household): ?>


                        <tr>


                            <td>

                                <?php
                                echo $number++;
                                ?>

                            </td>


                            <td class="household-number">

                                <?php

                                echo htmlspecialchars(
                                    $household['household_number']
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $household['head_name']
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $household['address']
                                );

                                ?>

                            </td>


                            <td>

                                <span class="badge bg-success">

                                    <i class="fa-solid fa-users me-1"></i>

                                    <?php

                                    echo (int)
                                        $household['members'];

                                    ?>

                                </span>

                            </td>


                            <td>

                                <?php

                                if (
                                    !empty(
                                        $household['created_at']
                                    )
                                ) {

                                    echo date(
                                        "M d, Y",
                                        strtotime(
                                            $household['created_at']
                                        )
                                    );

                                }

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
                                    "
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal<?php echo $household['id']; ?>"
                                    title="Edit"
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
                                    href="../actions/delete_household.php?id=<?php echo $household['id']; ?>"
                                    class="
                                        btn
                                        btn-danger
                                        btn-sm
                                    "
                                    title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this household? This action cannot be undone.');"
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
                            colspan="7"
                            class="
                                text-center
                                py-5
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-house
                                    fs-2
                                    mb-3
                                "
                            ></i>


                            <br>


                            No households found.

                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>


        </table>


    </div>


</div>


</div>

<!-- =====================================
     ADD HOUSEHOLD MODAL
===================================== -->

<div
    class="modal fade"
    id="addHouseholdModal"
    tabindex="-1"
>

<div class="modal-dialog modal-lg">


    <div class="modal-content">


        <div class="modal-header">


            <h5 class="modal-title">

                <i
                    class="
                        fa-solid
                        fa-house-circle-check
                        me-2
                    "
                ></i>

                Add Household

            </h5>


            <button
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal"
            ></button>


        </div>


        <form
            action="../actions/add_household.php"
            method="POST"
        >


            <div class="modal-body">


                <div class="row g-3">


                    <!-- HOUSEHOLD NUMBER -->

                    <div class="col-md-6">


                        <label class="form-label">

                            Household Number

                        </label>


                        <input
                            type="text"
                            name="household_number"
                            class="form-control"
                            placeholder="Example: HH-001"
                            required
                        >


                    </div>


                    <!-- HEAD OF HOUSEHOLD -->

                    <div class="col-md-6">


                        <label class="form-label">

                            Head of Household

                        </label>


                        <input
                            type="text"
                            name="head_name"
                            class="form-control"
                            placeholder="Enter full name"
                            required
                        >


                    </div>


                    <!-- MEMBERS -->

                    <div class="col-md-6">


                        <label class="form-label">

                            Number of Members

                        </label>


                        <input
                            type="number"
                            name="members"
                            class="form-control"
                            value="0"
                            min="0"
                            required
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
                            placeholder="Enter household address"
                            required
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

                    Save Household

                </button>


            </div>


        </form>


    </div>


</div>

</div>

<!-- =====================================
     EDIT HOUSEHOLD MODALS
===================================== -->

<?php foreach ($households as $household): ?>

<div
    class="modal fade"
    id="editModal<?php echo $household['id']; ?>"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <div class="modal-header">


                <h5 class="modal-title">

                    <i
                        class="
                            fa-solid
                            fa-pen-to-square
                            me-2
                        "
                    ></i>

                    Edit Household

                </h5>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>


            </div>


            <form
                action="../actions/update_household.php"
                method="POST"
            >


                <div class="modal-body">


                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $household['id']; ?>"
                    >


                    <div class="row g-3">


                        <!-- HOUSEHOLD NUMBER -->

                        <div class="col-md-6">


                            <label class="form-label">

                                Household Number

                            </label>


                            <input
                                type="text"
                                name="household_number"
                                class="form-control"
                                value="<?php echo htmlspecialchars($household['household_number']); ?>"
                                required
                            >


                        </div>


                        <!-- HEAD OF HOUSEHOLD -->

                        <div class="col-md-6">


                            <label class="form-label">

                                Head of Household

                            </label>


                            <input
                                type="text"
                                name="head_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($household['head_name']); ?>"
                                required
                            >


                        </div>


                        <!-- MEMBERS -->

                        <div class="col-md-6">


                            <label class="form-label">

                                Number of Members

                            </label>


                            <input
                                type="number"
                                name="members"
                                class="form-control"
                                value="<?php echo (int)$household['members']; ?>"
                                min="0"
                                required
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
                                value="<?php echo htmlspecialchars($household['address']); ?>"
                                required
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

                        Update Household

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>

<?php endforeach; ?>

<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>

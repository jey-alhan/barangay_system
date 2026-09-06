<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../pages/login.php");
    exit;

}


require_once __DIR__ . '/../config/database.php';


/* =========================================
   CHECK CERTIFICATE ID
========================================= */

if (!isset($_GET['id']) || empty($_GET['id'])) {

    die("Certificate ID is required.");

}


$certificateId = (int) $_GET['id'];


/* =========================================
   GET CERTIFICATE
========================================= */

$sql = "
    SELECT *
    FROM certificates
    WHERE id = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    die(
        "Database error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "i",
    $certificateId
);


$stmt->execute();


$result = $stmt->get_result();


/* =========================================
   CHECK CERTIFICATE
========================================= */

if ($result->num_rows !== 1) {

    die("Certificate not found.");

}


$certificate = $result->fetch_assoc();


$stmt->close();


/* =========================================
   CREATE DOWNLOAD FILE NAME
========================================= */

$fileName =
    "Certificate_" .
    preg_replace(
        "/[^A-Za-z0-9_-]/",
        "_",
        $certificate['certificate_number']
    ) .
    ".html";


/* =========================================
   FORCE DOWNLOAD
========================================= */

header("Content-Type: text/html; charset=UTF-8");

header(
    "Content-Disposition: attachment; filename=\"$fileName\""
);

header("Content-Transfer-Encoding: binary");

header("Cache-Control: private");


/* =========================================
   DOWNLOAD CONTENT
========================================= */

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>

        Barangay Certificate

    </title>


    <style>

        body {

            font-family:
                Arial,
                sans-serif;

            padding: 50px;

            color: #111;

        }


        .certificate {

            max-width: 850px;

            margin: auto;

            border: 3px solid #198754;

            padding: 50px;

        }


        .header {

            text-align: center;

            border-bottom:
                2px solid #198754;

            padding-bottom: 20px;

            margin-bottom: 35px;

        }


        .header h1 {

            margin: 0;

            color: #198754;

            font-size: 32px;

        }


        .header h2 {

            margin: 10px 0;

        }


        .certificate-title {

            text-align: center;

            font-size: 25px;

            font-weight: bold;

            text-transform: uppercase;

            margin:
                35px 0;

        }


        .content {

            font-size: 18px;

            line-height: 1.8;

        }


        .content p {

            margin-bottom: 20px;

        }


        .info-table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 30px;

        }


        .info-table td {

            padding: 12px;

            border-bottom:
                1px solid #ddd;

        }


        .label {

            width: 35%;

            font-weight: bold;

        }


        .footer {

            margin-top: 80px;

            display: flex;

            justify-content: space-between;

        }


        .signature {

            width: 250px;

            text-align: center;

            border-top:
                1px solid black;

            padding-top: 10px;

        }

    </style>

</head>


<body>


<div class="certificate">


    <!-- HEADER -->

    <div class="header">


        <p>

            Republic of the Philippines

        </p>


        <h2>

            BARANGAY MANAGEMENT SYSTEM

        </h2>


        <h1>

            BARANGAY CERTIFICATE

        </h1>


    </div>


    <!-- CERTIFICATE TITLE -->

    <div class="certificate-title">


        <?php
        echo htmlspecialchars(
            $certificate['certificate_type']
        );
        ?>


    </div>


    <!-- CERTIFICATE CONTENT -->

    <div class="content">


        <p>

            This is to certify that

            <strong>

                <?php
                echo htmlspecialchars(
                    $certificate['resident_name']
                );
                ?>

            </strong>

            is a resident of this barangay.

        </p>


        <p>

            This certificate is issued for the following purpose:

            <strong>

                <?php

                echo htmlspecialchars(
                    $certificate['purpose']
                );

                ?>

            </strong>

        </p>


        <p>

            Issued this

            <strong>

                <?php

                echo date(
                    "dS",
                    strtotime(
                        $certificate['date_issued']
                    )
                );

                ?>

            </strong>

            day of

            <strong>

                <?php

                echo date(
                    "F Y",
                    strtotime(
                        $certificate['date_issued']
                    )
                );

                ?>

            </strong>.

        </p>


    </div>


    <!-- CERTIFICATE INFORMATION -->

    <table class="info-table">


        <tr>

            <td class="label">

                Certificate Number

            </td>


            <td>

                <?php

                echo htmlspecialchars(
                    $certificate['certificate_number']
                );

                ?>

            </td>

        </tr>


        <tr>

            <td class="label">

                Certificate Type

            </td>


            <td>

                <?php

                echo htmlspecialchars(
                    $certificate['certificate_type']
                );

                ?>

            </td>

        </tr>


        <tr>

            <td class="label">

                Date Issued

            </td>


            <td>

                <?php

                echo date(
                    "F d, Y",
                    strtotime(
                        $certificate['date_issued']
                    )
                );

                ?>

            </td>

        </tr>


        <tr>

            <td class="label">

                Status

            </td>


            <td>

                <?php

                echo htmlspecialchars(
                    $certificate['status']
                );

                ?>

            </td>

        </tr>


    </table>


    <!-- SIGNATURE -->

    <div class="footer">


        <div>

        </div>


        <div class="signature">


            Barangay Captain

            <br>

            <small>

                Authorized Signature

            </small>


        </div>


    </div>


</div>


</body>

</html>
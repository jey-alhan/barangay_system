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

    die("Database error: " . $conn->error);

}


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $stmt->close();

    die("Certificate not found.");

}


$certificate = $result->fetch_assoc();


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
        Print Certificate
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


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

            padding: 30px;

            background: #e5e7eb;

            font-family:
                "Times New Roman",
                serif;

        }


        /* =====================================
           PRINT BUTTON AREA
        ===================================== */

        .print-actions {

            max-width: 900px;

            margin:
                0 auto 25px;

            display: flex;

            justify-content: space-between;

            font-family:
                Arial,
                sans-serif;

        }


        /* =====================================
           PAPER
        ===================================== */

        .certificate-paper {

            width: 210mm;

            min-height: 297mm;

            margin: auto;

            padding:
                35mm
                28mm;

            background: white;

            color: #111;

            box-shadow:
                0 5px 25px
                rgba(0,0,0,0.20);

        }


        /* =====================================
           HEADER
        ===================================== */

        .certificate-header {

            text-align: center;

            border-bottom:
                3px solid #198754;

            padding-bottom: 20px;

            margin-bottom: 35px;

        }


        .republic {

            font-size: 15px;

            margin-bottom: 5px;

        }


        .province {

            font-size: 17px;

            font-weight: bold;

        }


        .barangay {

            font-size: 24px;

            font-weight: bold;

            color: #198754;

            margin-top: 8px;

        }


        /* =====================================
           TITLE
        ===================================== */

        .certificate-title {

            text-align: center;

            margin:
                45px 0;

        }


        .certificate-title h1 {

            font-size: 32px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: 2px;

            text-decoration: underline;

        }


        /* =====================================
           BODY
        ===================================== */

        .certificate-body {

            font-size: 19px;

            line-height: 1.9;

            text-align: justify;

        }


        .certificate-body p {

            margin-bottom: 25px;

        }


        .resident-name {

            font-weight: bold;

            text-transform: uppercase;

            border-bottom:
                1px solid #111;

        }


        /* =====================================
           DETAILS
        ===================================== */

        .details-box {

            margin:
                35px 0;

            padding: 20px;

            border:
                1px solid #333;

        }


        .detail-row {

            display: flex;

            margin-bottom: 12px;

        }


        .detail-label {

            width: 180px;

            font-weight: bold;

        }


        /* =====================================
           SIGNATURE
        ===================================== */

        .signature-area {

            margin-top: 80px;

            display: flex;

            justify-content: flex-end;

        }


        .signature-box {

            width: 300px;

            text-align: center;

        }


        .signature-line {

            border-top:
                1px solid #000;

            margin-bottom: 8px;

        }


        .signature-name {

            font-weight: bold;

            text-transform: uppercase;

        }


        .signature-position {

            font-size: 15px;

        }


        /* =====================================
           FOOTER
        ===================================== */

        .certificate-footer {

            margin-top: 70px;

            padding-top: 15px;

            border-top:
                1px solid #999;

            text-align: center;

            font-size: 12px;

            color: #555;

        }


        /* =====================================
           PRINT SETTINGS
        ===================================== */

        @media print {

            body {

                padding: 0;

                background: white;

            }


            .print-actions {

                display: none;

            }


            .certificate-paper {

                width: 100%;

                min-height: auto;

                margin: 0;

                padding:
                    25mm
                    25mm;

                box-shadow: none;

            }

        }


        @page {

            size: A4;

            margin: 0;

        }

    </style>

</head>


<body>


<!-- =====================================
     PRINT ACTIONS
===================================== -->

<div class="print-actions">


    <a
        href="view_certificate.php?id=<?php echo $certificate['id']; ?>"
        class="btn btn-secondary"
    >

        <i class="fa-solid fa-arrow-left me-2"></i>

        Back

    </a>


    <button
        onclick="window.print()"
        class="btn btn-success"
    >

        <i class="fa-solid fa-print me-2"></i>

        Print Certificate

    </button>


</div>


<!-- =====================================
     CERTIFICATE PAPER
===================================== -->

<div class="certificate-paper">


    <!-- HEADER -->

    <div class="certificate-header">


        <div class="republic">

            Republic of the Philippines

        </div>


        <div class="province">

            Province / Municipality

        </div>


        <div class="barangay">

            BARANGAY NAME

        </div>


    </div>


    <!-- TITLE -->

    <div class="certificate-title">


        <h1>

            <?php
            echo htmlspecialchars(
                $certificate['certificate_type']
            );
            ?>

        </h1>


    </div>


    <!-- BODY -->

    <div class="certificate-body">


        <p>

            TO WHOM IT MAY CONCERN:

        </p>


        <p>

            This is to certify that

            <span class="resident-name">

                <?php
                echo htmlspecialchars(
                    $certificate['resident_name']
                );
                ?>

            </span>

            is a resident of this Barangay.

        </p>


        <p>

            This certificate is issued upon the request
            of the above-mentioned person for the purpose
            stated below.

        </p>


        <!-- DETAILS -->

        <div class="details-box">


            <div class="detail-row">


                <div class="detail-label">

                    Certificate No:

                </div>


                <div>

                    <?php
                    echo htmlspecialchars(
                        $certificate['certificate_number']
                    );
                    ?>

                </div>


            </div>


            <div class="detail-row">


                <div class="detail-label">

                    Certificate Type:

                </div>


                <div>

                    <?php
                    echo htmlspecialchars(
                        $certificate['certificate_type']
                    );
                    ?>

                </div>


            </div>


            <div class="detail-row">


                <div class="detail-label">

                    Purpose:

                </div>


                <div>

                    <?php

                    echo htmlspecialchars(
                        $certificate['purpose']
                        ?: 'Not specified'
                    );

                    ?>

                </div>


            </div>


            <div class="detail-row">


                <div class="detail-label">

                    Date Issued:

                </div>


                <div>

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


    <!-- SIGNATURE -->

    <div class="signature-area">


        <div class="signature-box">


            <br>
            <br>
            <br>


            <div class="signature-line"></div>


            <div class="signature-name">

                BARANGAY CAPTAIN

            </div>


            <div class="signature-position">

                Punong Barangay

            </div>


        </div>


    </div>


    <!-- FOOTER -->

    <div class="certificate-footer">


        This document was generated by the
        Barangay Management System.

        <br>

        Certificate Number:

        <?php
        echo htmlspecialchars(
            $certificate['certificate_number']
        );
        ?>


    </div>


</div>


<script>

    /*
    Uncomment this if you want the
    print dialog to automatically open.
    */

    // window.onload = function () {
    //     window.print();
    // };

</script>


</body>

</html>
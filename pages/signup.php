<?php

session_start();

if (isset($_SESSION['user_id'])) {

    header("Location: dashboard.php");
    exit;

}

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

    <title>
        Sign Up - Barangay Management System
    </title>


    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- FONT AWESOME -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        rel="stylesheet"
    >

    <link
        href="../assets/css/style.css"
        rel="stylesheet"
    >

    <style>

        /* =====================================
           GLOBAL
        ===================================== */

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }


        body {

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            overflow-x: hidden;

            font-family:
                Arial,
                sans-serif;

            color: white;

            background:

                radial-gradient(
                    circle at 15% 10%,
                    rgba(25,135,84,.25),
                    transparent 30%
                ),

                radial-gradient(
                    circle at 90% 20%,
                    rgba(32,201,151,.15),
                    transparent 25%
                ),

                radial-gradient(
                    circle at 80% 90%,
                    rgba(15,81,50,.35),
                    transparent 30%
                ),

                linear-gradient(
                    135deg,
                    #081c2c,
                    #102a43,
                    #0f5132
                );

            background-attachment:
                fixed;

            position:
                relative;

            padding:
                30px
                15px;

        }


        /* =====================================
           BACKGROUND EFFECT
        ===================================== */

        body::before {

            content: "";

            position:
                fixed;

            width:
                450px;

            height:
                450px;

            top:
                -180px;

            left:
                -150px;

            border-radius:
                50%;

            background:

                radial-gradient(
                    circle,
                    rgba(25,135,84,.30),
                    transparent 70%
                );

            filter:
                blur(50px);

            pointer-events:
                none;

        }


        body::after {

            content: "";

            position:
                fixed;

            width:
                500px;

            height:
                500px;

            bottom:
                -220px;

            right:
                -180px;

            border-radius:
                50%;

            background:

                radial-gradient(
                    circle,
                    rgba(32,201,151,.20),
                    transparent 70%
                );

            filter:
                blur(60px);

            pointer-events:
                none;

        }


        /* =====================================
           SIGNUP WRAPPER
        ===================================== */

        .signup-wrapper {

            position:
                relative;

            z-index:
                2;

            width:
                100%;

        }


        /* =====================================
           SIGNUP CARD
        ===================================== */

        .signup-card {

            position:
                relative;

            width:
                100%;

            max-width:
                430px;

            margin:
                auto;

            border-radius:
                20px;

            background:
                rgba(255,255,255,0.10);

            border:

                1px solid
                rgba(255,255,255,0.15);

            backdrop-filter:
                blur(20px);

            -webkit-backdrop-filter:
                blur(20px);

            box-shadow:

                0 15px 35px
                rgba(0,0,0,0.25);

            overflow:
                hidden;

            color:
                white;

            animation:
                fadeUp
                0.8s
                ease;

        }


        /* =====================================
           CARD GLOW
        ===================================== */

        .signup-card::before {

            content: "";

            position:
                absolute;

            width:
                250px;

            height:
                250px;

            top:
                -100px;

            right:
                -100px;

            border-radius:
                50%;

            background:
                rgba(32,201,151,0.12);

            filter:
                blur(45px);

            pointer-events:
                none;

        }


        /* =====================================
           HEADER
        ===================================== */

        .signup-header {

            position:
                relative;

            z-index:
                1;

            text-align:
                center;

            padding:

                32px
                30px
                10px;

        }


        /* =====================================
           SIGNUP ICON
        ===================================== */

        .signup-icon {

            width:
                50px;

            height:
                50px;

            margin:

                0
                auto
                15px;

            border-radius:
                14px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            font-size:
                20px;

            color:
                white;

            background:

                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

            box-shadow:

                0 10px 25px
                rgba(25,135,84,0.35);

        }


        .signup-header h2 {

            color:
                white;

            font-size:
                25px;

            font-weight:
                700;

            margin-bottom:
                5px;

        }


        .signup-header p {

            color:
                #cbd5e1;

            font-size:
                12px;

            margin-bottom:
                0;

        }


        /* =====================================
           FORM BODY
        ===================================== */

        .signup-body {

            position:
                relative;

            z-index:
                1;

            padding:

                20px
                38px
                32px;

        }


        /* =====================================
           FORM LABEL
        ===================================== */

        .form-label {

            color:
                rgba(255,255,255,0.75);

            font-size:
                11px;

            font-weight:
                600;

            margin-bottom:
                7px;

        }


        /* =====================================
           INPUT GROUP
        ===================================== */

        .input-group {

            border-radius:
                10px;

            overflow:
                hidden;

            background:
                rgba(255,255,255,0.07);

            border:

                1px solid
                rgba(255,255,255,0.12);

            transition:
                0.3s;

        }


        .input-group:focus-within {

            border-color:
                rgba(32,201,151,0.75);

            box-shadow:

                0 0 0 3px
                rgba(32,201,151,0.10);

        }


        .input-group-text {

            background:
                transparent;

            border:
                none;

            color:
                rgba(255,255,255,0.55);

            padding-left:
                14px;

            padding-right:
                8px;

            font-size:
                13px;

        }


        .form-control {

            background:
                transparent;

            border:
                none;

            color:
                white;

            padding:

                12px
                12px;

            font-size:
                13px;

            box-shadow:
                none !important;

        }


        .form-control:focus {

            background:
                transparent;

            color:
                white;

        }


        .form-control::placeholder {

            color:
                rgba(255,255,255,0.38);

        }


        /* =====================================
           SIGNUP BUTTON
        ===================================== */

        .btn-signup {

            width:
                100%;

            padding:
                13px;

            border:
                none;

            border-radius:
                10px;

            font-weight:
                600;

            font-size:
                13px;

            color:
                white;

            background:

                linear-gradient(
                    135deg,
                    #198754,
                    #20c997
                );

            box-shadow:

                0 10px 25px
                rgba(25,135,84,0.30);

            transition:
                all
                0.3s
                ease;

        }


        .btn-signup:hover {

            transform:
                translateY(-2px);

            color:
                white;

            background:

                linear-gradient(
                    135deg,
                    #157347,
                    #1aa179
                );

            box-shadow:

                0 15px 30px
                rgba(25,135,84,0.45);

        }


        .btn-signup:active {

            transform:
                translateY(0);

        }


        /* =====================================
           ERROR ALERT
        ===================================== */

        .alert-danger {

            background:
                rgba(220,53,69,0.12);

            border:

                1px solid
                rgba(255,100,110,0.22);

            color:
                #ffb3ba;

            border-radius:
                10px;

            font-size:
                12px;

        }


        /* =====================================
           LOGIN TEXT
        ===================================== */

        .login-text {

            text-align:
                center;

            color:
                rgba(255,255,255,0.55);

            font-size:
                12px;

            margin-top:
                18px;

            margin-bottom:
                0;

        }


        .login-text a {

            color:
                #20c997;

            font-weight:
                600;

            text-decoration:
                none;

            margin-left:
                3px;

        }


        .login-text a:hover {

            color:
                white;

        }


        /* =====================================
           ANIMATION
        ===================================== */

        @keyframes fadeUp {

            from {

                opacity:
                    0;

                transform:
                    translateY(30px);

            }


            to {

                opacity:
                    1;

                transform:
                    translateY(0);

            }

        }


        /* =====================================
           MOBILE
        ===================================== */

        @media (max-width: 576px) {

            body {

                padding:
                    20px
                    15px;

            }


            .signup-card {

                max-width:
                    100%;

            }


            .signup-header {

                padding:

                    30px
                    20px
                    10px;

            }


            .signup-body {

                padding:

                    20px
                    25px
                    30px;

            }


            .signup-header h2 {

                font-size:
                    23px;

            }

        }

    </style>

</head>


<body>


<!-- =====================================
     SIGNUP WRAPPER
===================================== -->

<div class="container signup-wrapper">


    <div class="row justify-content-center">


        <div class="col-md-8 col-lg-5">


            <!-- SIGNUP CARD -->

            <div class="signup-card">


                <!-- HEADER -->

                <div class="signup-header">


                    <div class="signup-icon">

                        <i class="fa-solid fa-user-plus"></i>

                    </div>


                    <h2>

                        Create Account

                    </h2>


                    <p>

                        Barangay Management System

                    </p>


                </div>



                <!-- FORM BODY -->

                <div class="signup-body">


                    <!-- ERROR -->

                    <?php if (!empty($error)): ?>


                        <div class="alert alert-danger">


                            <i
                                class="
                                    fa-solid
                                    fa-circle-exclamation
                                    me-2
                                "
                            ></i>


                            <?php
                            echo htmlspecialchars($error);
                            ?>


                        </div>


                    <?php endif; ?>



                    <!-- SIGNUP FORM -->

                    <form
                        action="../actions/signup_action.php"
                        method="POST"
                    >


                        <!-- USERNAME -->

                        <div class="mb-3">


                            <label class="form-label">

                                Username

                            </label>


                            <div class="input-group">


                                <span class="input-group-text">

                                    <i class="fa-solid fa-user"></i>

                                </span>


                                <input

                                    type="text"

                                    name="username"

                                    class="form-control"

                                    placeholder="Choose a username"

                                    required

                                    autocomplete="username"

                                >


                            </div>


                        </div>



                        <!-- PASSWORD -->

                        <div class="mb-3">


                            <label class="form-label">

                                Password

                            </label>


                            <div class="input-group">


                                <span class="input-group-text">

                                    <i class="fa-solid fa-lock"></i>

                                </span>


                                <input

                                    type="password"

                                    name="password"

                                    class="form-control"

                                    placeholder="Create a password"

                                    required

                                    autocomplete="new-password"

                                >


                            </div>


                        </div>



                        <!-- CONFIRM PASSWORD -->

                        <div class="mb-4">


                            <label class="form-label">

                                Confirm Password

                            </label>


                            <div class="input-group">


                                <span class="input-group-text">

                                    <i
                                        class="
                                            fa-solid
                                            fa-shield-halved
                                        "
                                    ></i>

                                </span>


                                <input

                                    type="password"

                                    name="confirm_password"

                                    class="form-control"

                                    placeholder="Confirm your password"

                                    required

                                    autocomplete="new-password"

                                >


                            </div>


                        </div>



                        <!-- CREATE ACCOUNT BUTTON -->

                        <button

                            type="submit"

                            class="btn-signup"

                        >


                            <i
                                class="
                                    fa-solid
                                    fa-user-plus
                                    me-2
                                "
                            ></i>


                            Create Account


                        </button>


                    </form>



                    <!-- LOGIN LINK -->

                    <p class="login-text">


                        Already have an account?


                        <a href="login.php">

                            Sign In

                        </a>


                    </p>


                </div>


            </div>


        </div>


    </div>


</div>



<!-- BOOTSTRAP JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
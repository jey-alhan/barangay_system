<?php

session_start();

/* =========================================
   CHECK IF ALREADY LOGGED IN
========================================= */

if (isset($_SESSION['user_id'])) {

    header("Location: dashboard.php");
    exit;

}


/* =========================================
   GET ERROR MESSAGE
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

    <title>
        Login - Barangay Management System
    </title>


    <!-- =====================================
         BOOTSTRAP
    ===================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =====================================
         FONT AWESOME
    ===================================== -->

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

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

    overflow: hidden;

    font-family:
        Arial,
        sans-serif;

    color:
        white;

    background:

        radial-gradient(
            circle at 15% 15%,
            rgba(25,135,84,0.30),
            transparent 28%
        ),

        radial-gradient(
            circle at 80% 15%,
            rgba(32,201,151,0.18),
            transparent 25%
        ),

        radial-gradient(
            circle at 80% 85%,
            rgba(15,81,50,0.35),
            transparent 30%
        ),

        linear-gradient(
            135deg,
            #081c2c,
            #102a43,
            #0f5132
        );

    position:
        relative;

}


/* =========================================
   BACKGROUND GLOW
========================================= */

body::before {

    content:

        "";

    position:

        absolute;

    width:

        400px;

    height:

        400px;

    top:

        -150px;

    left:

        -100px;

    border-radius:

        50%;

    background:

        radial-gradient(
            circle,
            rgba(25,135,84,0.30),
            transparent 70%
        );

    filter:

        blur(50px);

    pointer-events:

        none;

}


body::after {

    content:

        "";

    position:

        absolute;

    width:

        450px;

    height:

        450px;

    bottom:

        -180px;

    right:

        -120px;

    border-radius:

        50%;

    background:

        radial-gradient(
            circle,
            rgba(32,201,151,0.20),
            transparent 70%
        );

    filter:

        blur(60px);

    pointer-events:

        none;

}


/* =========================================
   LOGIN WRAPPER
========================================= */

.login-wrapper {

    position:

        relative;

    z-index:

        2;

    width:

        100%;

}


/* =========================================
   LOGIN CARD
========================================= */

.login-card {

    width:

        100%;

    max-width:

        430px;

    margin:

        auto;

    border-radius:

        22px;

    position:

        relative;

    overflow:

        hidden;

    background:

        rgba(255,255,255,0.10);

    backdrop-filter:

        blur(20px);

    -webkit-backdrop-filter:

        blur(20px);

    border:

        1px solid
        rgba(255,255,255,0.15);

    box-shadow:

        0 15px 35px
        rgba(0,0,0,0.30);

    animation:

        fadeUp
        0.8s
        ease;

}


/* =========================================
   CARD DECORATION
========================================= */

.login-card::before {

    content:

        "";

    position:

        absolute;

    width:

        250px;

    height:

        250px;

    top:

        -120px;

    right:

        -120px;

    border-radius:

        50%;

    background:

        rgba(32,201,151,0.12);

    filter:

        blur(45px);

    pointer-events:

        none;

}


/* =========================================
   LOGIN HEADER
========================================= */

.login-header {

    text-align:

        center;

    padding:

        38px
        35px
        15px;

    position:

        relative;

    z-index:

        1;

}


/* =========================================
   LOGIN ICON
========================================= */

.login-icon {

    width:

        60px;

    height:

        60px;

    margin:

        0
        auto
        18px;

    border-radius:

        17px;

    display:

        flex;

    align-items:

        center;

    justify-content:

        center;

    font-size:

        24px;

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


/* =========================================
   LOGIN TITLE
========================================= */

.login-header h3 {

    color:

        white;

    font-size:

        26px;

    font-weight:

        700;

    margin-bottom:

        7px;

}


.login-header p {

    color:

        #cbd5e1;

    font-size:

        13px;

    margin:

        0;

}


/* =========================================
   LOGIN BODY
========================================= */

.login-body {

    padding:

        25px
        38px
        35px;

    position:

        relative;

    z-index:

        1;

}


/* =========================================
   FORM LABEL
========================================= */

.form-label {

    color:

        #cbd5e1;

    font-size:

        12px;

    font-weight:

        600;

    margin-bottom:

        8px;

}


/* =========================================
   INPUT GROUP
========================================= */

.input-group {

    border-radius:

        12px;

    overflow:

        hidden;

    background:

        rgba(255,255,255,0.08);

    border:

        1px solid
        rgba(255,255,255,0.12);

    transition:

        0.3s;

}


.input-group:focus-within {

    border-color:

        rgba(32,201,151,0.70);

    box-shadow:

        0 0 0 3px
        rgba(32,201,151,0.10);

}


/* =========================================
   INPUT ICON
========================================= */

.input-group-text {

    background:

        transparent;

    border:

        none;

    color:

        rgba(255,255,255,0.60);

    padding-left:

        15px;

    padding-right:

        8px;

}


/* =========================================
   FORM INPUT
========================================= */

.form-control {

    background:

        transparent;

    border:

        none;

    color:

        white;

    padding:

        13px;

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

        rgba(255,255,255,0.40);

}


/* =========================================
   LOGIN BUTTON
========================================= */

.btn-login {

    width:

        100%;

    padding:

        14px;

    border:

        none;

    border-radius:

        12px;

    font-weight:

        600;

    font-size:

        14px;

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


.btn-login:hover {

    color:

        white;

    transform:

        translateY(-2px);

    background:

        linear-gradient(
            135deg,
            #157347,
            #1bbf8a
        );

    box-shadow:

        0 15px 30px
        rgba(25,135,84,0.45);

}


.btn-login:active {

    transform:

        translateY(0);

}


/* =========================================
   ERROR ALERT
========================================= */

.alert-danger {

    background:

        rgba(220,53,69,0.15);

    border:

        1px solid
        rgba(255,100,110,0.25);

    color:

        #ffb3bd;

    border-radius:

        12px;

    font-size:

        13px;

}


/* =========================================
   SIGNUP
========================================= */

.signup-text {

    text-align:

        center;

    color:

        #cbd5e1;

    font-size:

        12px;

    margin-top:

        20px;

}


.signup-text a {

    color:

        #20c997;

    font-weight:

        600;

    text-decoration:

        none;

    margin-left:

        4px;

}


.signup-text a:hover {

    color:

        white;

}


/* =========================================
   FOOTER TEXT
========================================= */

.login-footer {

    text-align:

        center;

    margin-top:

        20px;

    color:

        rgba(255,255,255,0.40);

    font-size:

        11px;

}


/* =========================================
   ANIMATION
========================================= */

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


/* =========================================
   MOBILE
========================================= */

@media (max-width: 576px) {

    body {

        padding:

            20px;

        overflow-y:

            auto;

    }


    .login-card {

        max-width:

            100%;

    }


    .login-header {

        padding:

            30px
            25px
            15px;

    }


    .login-body {

        padding:

            20px
            25px
            30px;

    }


    .login-header h3 {

        font-size:

            23px;

    }

}


</style>

</head>


<body>


<!-- =========================================
     LOGIN WRAPPER
========================================= -->

<div class="container login-wrapper">


    <div class="row justify-content-center">


        <div class="col-md-8 col-lg-5">


            <!-- =====================================
                 LOGIN CARD
            ====================================== -->

            <div class="login-card">


                <!-- =====================================
                     HEADER
                ====================================== -->

                <div class="login-header">


                    <div class="login-icon">

                        <i class="fa-solid fa-building"></i>

                    </div>


                    <h3>

                        Barangay System

                    </h3>


                    <p>

                        Secure access to the management system

                    </p>


                </div>



                <!-- =====================================
                     LOGIN FORM
                ====================================== -->

                <div class="login-body">


                    <!-- ERROR MESSAGE -->

                    <?php if (!empty($error)): ?>


                        <div class="alert alert-danger">

                            <i class="fa-solid fa-circle-exclamation me-2"></i>

                            <?php echo htmlspecialchars($error); ?>

                        </div>


                    <?php endif; ?>



                    <!-- LOGIN FORM -->

                    <form
                        action="../actions/login.php"
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

                                    placeholder="Enter your username"

                                    required

                                    autocomplete="username"

                                >


                            </div>


                        </div>



                        <!-- PASSWORD -->

                        <div class="mb-4">


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

                                    placeholder="Enter your password"

                                    required

                                    autocomplete="current-password"

                                >


                            </div>


                        </div>



                        <!-- LOGIN BUTTON -->

                        <button

                            type="submit"

                            class="btn-login"

                        >


                            <i
                                class="
                                    fa-solid
                                    fa-right-to-bracket
                                    me-2
                                "
                            ></i>

                            Sign In


                        </button>


                    </form>



                    <!-- SIGNUP -->

                    <div class="signup-text">


                        Don't have an account?


                        <a href="signup.php">

                            Create Account

                        </a>


                    </div>



                    <!-- FOOTER -->

                    <div class="login-footer">


                        Barangay Management System

                        <br>

                        Secure Administration Portal


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


<!-- =========================================
     BOOTSTRAP JAVASCRIPT
========================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
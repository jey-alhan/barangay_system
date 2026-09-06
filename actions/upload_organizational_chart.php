<?php

session_start();


/* =========================================
   CHECK LOGIN
========================================= */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../pages/login.php");
    exit;

}


/* =========================================
   CHECK REQUEST METHOD
========================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   CHECK FILE
========================================= */

if (
    !isset($_FILES['chart_image']) ||
    $_FILES['chart_image']['error'] !== UPLOAD_ERR_OK
) {

    $_SESSION['error'] =
        "Please select an image to upload.";

    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   FILE INFORMATION
========================================= */

$file = $_FILES['chart_image'];

$fileName = $file['name'];

$fileTmp = $file['tmp_name'];

$fileSize = $file['size'];


/* =========================================
   FILE EXTENSION
========================================= */

$fileExtension = strtolower(
    pathinfo(
        $fileName,
        PATHINFO_EXTENSION
    )
);


/* =========================================
   ALLOWED EXTENSIONS
========================================= */

$allowedExtensions = [
    'jpg',
    'jpeg',
    'png',
    'webp'
];


if (
    !in_array(
        $fileExtension,
        $allowedExtensions
    )
) {

    $_SESSION['error'] =
        "Only JPG, JPEG, PNG and WEBP images are allowed.";

    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   MAXIMUM FILE SIZE
   5 MB
========================================= */

$maxFileSize =
    5 * 1024 * 1024;


if ($fileSize > $maxFileSize) {

    $_SESSION['error'] =
        "Image size must not exceed 5MB.";

    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   VERIFY IMAGE
========================================= */

$imageInfo = getimagesize($fileTmp);


if ($imageInfo === false) {

    $_SESSION['error'] =
        "The uploaded file is not a valid image.";

    header("Location: ../pages/settings.php");
    exit;

}


/* =========================================
   UPLOAD DIRECTORY
========================================= */

$uploadDirectory =
    __DIR__ .
    '/../uploads/organizational_charts/';


/* CREATE DIRECTORY */

if (!is_dir($uploadDirectory)) {

    mkdir(
        $uploadDirectory,
        0755,
        true
    );

}


/* =========================================
   DELETE OLD IMAGES
========================================= */

$oldFiles = scandir(
    $uploadDirectory
);


foreach ($oldFiles as $oldFile) {

    if (
        $oldFile === '.' ||
        $oldFile === '..'
    ) {

        continue;

    }


    $oldPath =
        $uploadDirectory .
        $oldFile;


    if (is_file($oldPath)) {

        $oldExtension = strtolower(
            pathinfo(
                $oldFile,
                PATHINFO_EXTENSION
            )
        );


        if (
            in_array(
                $oldExtension,
                $allowedExtensions
            )
        ) {

            unlink($oldPath);

        }

    }

}


/* =========================================
   CREATE UNIQUE FILE NAME
========================================= */

$newFileName =
    'organizational_chart_' .
    time() .
    '.' .
    $fileExtension;


$destination =
    $uploadDirectory .
    $newFileName;


/* =========================================
   UPLOAD IMAGE
========================================= */

if (
    move_uploaded_file(
        $fileTmp,
        $destination
    )
) {

    $_SESSION['success'] =
        "Organizational chart uploaded successfully.";

} else {

    $_SESSION['error'] =
        "Failed to upload organizational chart.";

}


/* =========================================
   REDIRECT
========================================= */

header(
    "Location: ../pages/settings.php"
);

exit;

?>
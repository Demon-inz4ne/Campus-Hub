<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"])) {
    echo ("Please login first.");
    exit();
}

$title = $_POST["t"];
$desc = $_POST["d"];
$type = $_POST["type"];
$user_email = $_SESSION["u"]["email"];

if (empty($title)) {
    echo ("Please enter a media title.");
} else if (empty($type)) {
    echo ("Please select a media type.");
} else if (!isset($_FILES["file"])) {
    echo ("Please select a media file to upload.");
} else {
    $file = $_FILES["file"];
    $ext_arr = explode(".", $file["name"]);
    $file_ext = strtolower(end($ext_arr));

    $target_dir = "resources/media_files/";
    $new_filename = $target_dir . uniqid() . "." . $file_ext;

    if (move_uploaded_file($file["tmp_name"], "../" . $new_filename)) {
        $d = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $d->setTimezone($tz);
        $date = $d->format("Y-m-d H:i:s");

        Database::iud("INSERT INTO `media_gallery` (`title`, `description`, `file_path`, `media_type`, `uploaded_by`, `upload_date`) 
        VALUES ('" . $title . "', '" . $desc . "', '" . $new_filename . "', '" . $type . "', '" . $user_email . "', '" . $date . "')");

        echo ("success");
    } else {
        echo ("Failed to save uploaded file.");
    }
}
?>

<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"])) {
    echo ("Please login to your account to submit forms.");
    exit();
}

$user_email = $_SESSION["u"]["email"];
$subject = $_POST["s"];
$category = $_POST["c"];
$message = $_POST["m"];
$attachment_path = null;

if (empty($subject)) {
    echo ("Please enter a subject.");
} else if (empty($category)) {
    echo ("Please select a form category.");
} else if (empty($message)) {
    echo ("Please enter your message details.");
} else {
    if (isset($_FILES["file"])) {
        $file = $_FILES["file"];
        $target_dir = "resources/attachments/";
        $ext_arr = explode(".", $file["name"]);
        $file_ext = strtolower(end($ext_arr));
        $attachment_path = $target_dir . uniqid() . "." . $file_ext;

        move_uploaded_file($file["tmp_name"], "../" . $attachment_path);
    }

    $d = new DateTime();
    $tz = new DateTimeZone("Asia/Colombo");
    $d->setTimezone($tz);
    $date = $d->format("Y-m-d H:i:s");

    Database::iud("INSERT INTO `online_form_submission` (`user_email`, `subject`, `category`, `message`, `attachment`, `submitted_date`, `status`) 
    VALUES ('" . $user_email . "', '" . $subject . "', '" . $category . "', '" . $message . "', '" . $attachment_path . "', '" . $date . "', 'Pending')");

    echo ("success");
}
?>

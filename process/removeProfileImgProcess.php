<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"])) {
    echo ("Please login first.");
    exit();
}

$user_email = $_SESSION["u"]["email"];

$user_rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $user_email . "'");
if ($user_rs->num_rows == 0) {
    echo ("User record not found.");
    exit();
}

$user_data = $user_rs->fetch_assoc();
$current_img = $user_data["profile_img"];

$default_img = "resources/profile_images/default.png";

if ($current_img != $default_img && file_exists("../" . $current_img)) {
    @unlink("../" . $current_img);
}

// Reset user profile_img column to default
Database::iud("UPDATE `user` SET `profile_img`='" . $default_img . "' WHERE `email`='" . $user_email . "'");

// Log removal in user_profile_image table
$d = new DateTime();
$tz = new DateTimeZone("Asia/Colombo");
$d->setTimezone($tz);
$now = $d->format("Y-m-d H:i:s");

Database::iud("INSERT INTO `user_profile_image` (`user_email`, `image_path`, `updated_date`) VALUES ('" . $user_email . "', '" . $default_img . "', '" . $now . "')");

// Refresh session
$updated_rs = Database::search("SELECT user.*, campus.name AS campus_name FROM `user` LEFT JOIN `campus` ON user.campus_id=campus.id WHERE `email`='" . $user_email . "'");
$_SESSION["u"] = $updated_rs->fetch_assoc();

echo ("success");
?>

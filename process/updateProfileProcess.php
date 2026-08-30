<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"])) {
    echo ("Please login first.");
    exit();
}

$user_email = $_SESSION["u"]["email"];
$fname = isset($_POST["f"]) ? trim($_POST["f"]) : "";
$lname = isset($_POST["l"]) ? trim($_POST["l"]) : "";
$mobile = isset($_POST["m"]) ? trim($_POST["m"]) : "";

if (empty($fname)) {
    echo ("First Name cannot be empty.");
} else if (empty($lname)) {
    echo ("Last Name cannot be empty.");
} else if (empty($mobile) || strlen($mobile) != 10) {
    echo ("Mobile Number must be exactly 10 digits.");
} else {
    // Fetch current user details
    $user_rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $user_email . "'");
    $user_data = $user_rs->fetch_assoc();
    $current_img = $user_data["profile_img"];

    $new_file_name = null;

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image = $_FILES["image"];
        $allowed_types = array("image/jpeg", "image/jpg", "image/png", "image/svg+xml", "image/webp");

        if (in_array(strtolower($image["type"]), $allowed_types)) {
            $ext_arr = explode(".", $image["name"]);
            $ext = strtolower(end($ext_arr));
            
            $new_file_name = "resources/profile_images/prof_" . uniqid() . "." . $ext;
            $upload_path = "../" . $new_file_name;

            // Delete old custom photo if exists and is not default
            if (!empty($current_img) && $current_img != "resources/profile_images/default.png" && file_exists("../" . $current_img)) {
                @unlink("../" . $current_img);
            }

            // Upload new profile image
            if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
                echo ("Failed to upload profile picture.");
                exit();
            }

            // Log image history in user_profile_image table
            $d = new DateTime();
            $tz = new DateTimeZone("Asia/Colombo");
            $d->setTimezone($tz);
            $now = $d->format("Y-m-d H:i:s");

            Database::iud("INSERT INTO `user_profile_image` (`user_email`, `image_path`, `updated_date`) VALUES ('" . $user_email . "', '" . $new_file_name . "', '" . $now . "')");
        } else {
            echo ("Invalid image format. Allowed formats: JPG, PNG, SVG, WEBP.");
            exit();
        }
    }

    if ($new_file_name != null) {
        Database::iud("UPDATE `user` SET `fname`='" . $fname . "', `lname`='" . $lname . "', `mobile`='" . $mobile . "', `profile_img`='" . $new_file_name . "' WHERE `email`='" . $user_email . "'");
    } else {
        Database::iud("UPDATE `user` SET `fname`='" . $fname . "', `lname`='" . $lname . "', `mobile`='" . $mobile . "' WHERE `email`='" . $user_email . "'");
    }

    // Refresh session data
    $updated_rs = Database::search("SELECT user.*, campus.name AS campus_name FROM `user` LEFT JOIN `campus` ON user.campus_id=campus.id WHERE `email`='" . $user_email . "'");
    $_SESSION["u"] = $updated_rs->fetch_assoc();

    echo ("success");
}
?>

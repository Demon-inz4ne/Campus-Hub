<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"]) || ($_SESSION["u"]["user_type_id"] != 1 && $_SESSION["u"]["user_type_id"] != 2)) {
    echo ("Unauthorized access.");
    exit();
}

$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$action = isset($_POST["action"]) ? trim($_POST["action"]) : "";

if (empty($email)) {
    echo ("Student email is required.");
    exit();
}

$user_rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $email . "'");
if ($user_rs->num_rows == 0) {
    echo ("Student record not found.");
    exit();
}

$student = $user_rs->fetch_assoc();

// If Sub Admin, ensure student belongs to their assigned campus
if ($_SESSION["u"]["user_type_id"] == 2) {
    $admin_campus = $_SESSION["u"]["campus_id"];
    if ($student["campus_id"] != $admin_campus) {
        echo ("Unauthorized: This student does not belong to your assigned campus.");
        exit();
    }
}

if ($action == "approve") {
    Database::iud("UPDATE `user` SET `status_id`='1' WHERE `email`='" . $email . "'");
    echo ("success");
} else if ($action == "reject") {
    Database::iud("UPDATE `user` SET `status_id`='3' WHERE `email`='" . $email . "'");
    echo ("success");
} else {
    echo ("Invalid action requested.");
}
?>

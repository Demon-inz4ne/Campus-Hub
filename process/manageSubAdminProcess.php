<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"]) || $_SESSION["u"]["user_type_id"] != 1) {
    echo ("Unauthorized access.");
    exit();
}

$action = isset($_POST["action"]) ? trim($_POST["action"]) : "";

if ($action == "add") {
    $fname = isset($_POST["fname"]) ? trim($_POST["fname"]) : "";
    $lname = isset($_POST["lname"]) ? trim($_POST["lname"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
    $mobile = isset($_POST["mobile"]) ? trim($_POST["mobile"]) : "";
    $campus_id = isset($_POST["campus_id"]) ? trim($_POST["campus_id"]) : "";

    if (empty($fname) || empty($lname)) {
        echo ("Please enter sub-admin full name.");
    } else if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo ("Valid email address is required.");
    } else if (empty($password)) {
        echo ("Please enter password.");
    } else if (empty($mobile) || strlen($mobile) != 10) {
        echo ("Please enter a 10-digit mobile number.");
    } else if (empty($campus_id) || $campus_id == "0") {
        echo ("Please select a campus to assign to this sub-admin.");
    } else {
        $chk = Database::search("SELECT * FROM `user` WHERE `email`='" . $email . "'");
        if ($chk->num_rows > 0) {
            echo ("User with this email address already exists.");
        } else {
            $campus_rs = Database::search("SELECT * FROM `campus` WHERE `id`='" . $campus_id . "'");
            $campus_name = $campus_rs ? $campus_rs->fetch_assoc()["name"] : "Campus";

            $d = new DateTime();
            $tz = new DateTimeZone("Asia/Colombo");
            $d->setTimezone($tz);
            $date = $d->format("Y-m-d H:i:s");

            // Insert as user_type_id = 2 (Sub Admin), status_id = 1 (Active)
            Database::iud("INSERT INTO `user` (`email`, `fname`, `lname`, `password`, `mobile`, `institution`, `campus_id`, `user_type_id`, `status_id`, `joined_date`) 
            VALUES ('" . $email . "', '" . $fname . "', '" . $lname . "', '" . $password . "', '" . $mobile . "', '" . $campus_name . "', '" . $campus_id . "', '2', '1', '" . $date . "')");

            echo ("success");
        }
    }
} else if ($action == "delete") {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    if (empty($email)) {
        echo ("Sub-admin email is required.");
    } else {
        Database::iud("DELETE FROM `user` WHERE `email`='" . $email . "' AND `user_type_id`='2'");
        echo ("success");
    }
} else {
    echo ("Invalid action.");
}
?>

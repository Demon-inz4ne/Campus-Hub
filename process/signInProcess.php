<?php
session_start();
include "../includes/connection.php";

$email = isset($_POST["e"]) ? trim($_POST["e"]) : "";
$password = isset($_POST["p"]) ? trim($_POST["p"]) : "";
$rememberMe = isset($_POST["r"]) ? $_POST["r"] : "false";

if (empty($email)) {
    echo ("Please Enter Your Email Address.");
} else if (strlen($email) > 100) {
    echo ("Email Address must contain fewer than 100 characters.");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Invalid Email Address format.");
} else if (empty($password)) {
    echo ("Please Enter Your Password.");
} else {
    $rs = Database::search("SELECT user.*, campus.name AS campus_name FROM `user` LEFT JOIN `campus` ON user.campus_id=campus.id WHERE `email`='" . $email . "' AND `password`='" . $password . "'");
    if ($rs->num_rows == 1) {
        $data = $rs->fetch_assoc();

        $status_id = $data["status_id"];
        $user_type_id = $data["user_type_id"];
        $vcode = $data["vcode"];
        $campus_name = !empty($data["campus_name"]) ? $data["campus_name"] : $data["institution"];

        if ($status_id == 2) {
            // Pending verification/approval by Sub Admin
            echo ("pending:Registration Pending Approval! Please check your email inbox for the verification code. Your account is awaiting approval from your campus Sub-Admin (" . htmlspecialchars($campus_name) . ").");
            exit();
        } else if ($status_id == 3) {
            // Rejected
            echo ("rejected:Account Registration Rejected. Your registration request was rejected by your campus admin.");
            exit();
        }

        // Active/Approved user
        $_SESSION["u"] = $data;

        if ($rememberMe == "true") {
            setcookie("email", $email, time() + (60 * 60 * 24 * 365), "/");
            setcookie("password", $password, time() + (60 * 60 * 24 * 365), "/");
        } else {
            setcookie("email", "", -1, "/");
            setcookie("password", "", -1, "/");
        }

        if ($user_type_id == 1) {
            echo ("main_admin");
        } else if ($user_type_id == 2) {
            echo ("sub_admin");
        } else {
            echo ("student");
        }
    } else {
        echo ("Invalid Email Address or Password.");
    }
}
?>

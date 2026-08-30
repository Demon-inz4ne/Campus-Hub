<?php
include "../includes/connection.php";

$email = isset($_POST["e"]) ? trim($_POST["e"]) : "";
$code = isset($_POST["c"]) ? trim($_POST["c"]) : "";

if (empty($email)) {
    echo ("Please enter your Email Address.");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Invalid Email Address format.");
} else if (empty($code)) {
    echo ("Please enter the Verification Code sent to your email.");
} else {
    $rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $email . "' AND `vcode`='" . $code . "'");
    if ($rs->num_rows == 1) {
        $user = $rs->fetch_assoc();
        echo ("success");
    } else {
        echo ("Invalid Email Verification Code. Please check your email inbox (and spam folder) and try again.");
    }
}
?>

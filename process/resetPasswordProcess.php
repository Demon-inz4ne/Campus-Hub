<?php
include "../includes/connection.php";

$email = isset($_POST["e"]) ? trim($_POST["e"]) : "";
$code = isset($_POST["c"]) ? trim($_POST["c"]) : "";
$new_pw = isset($_POST["np"]) ? trim($_POST["np"]) : "";
$re_pw = isset($_POST["rnp"]) ? trim($_POST["rnp"]) : "";

if (empty($email)) {
    echo ("Please enter your Email Address.");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Invalid Email Address format.");
} else if (empty($code)) {
    echo ("Please enter the Verification Code sent to your email.");
} else if (empty($new_pw)) {
    echo ("Please enter your New Password.");
} else if (strlen($new_pw) < 5 || strlen($new_pw) > 20) {
    echo ("Password must contain between 5 to 20 characters.");
} else if (empty($re_pw)) {
    echo ("Please re-type your New Password.");
} else if ($new_pw !== $re_pw) {
    echo ("Passwords do not match. Please verify your new password.");
} else {
    $rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $email . "' AND `vcode`='" . $code . "'");
    if ($rs->num_rows == 1) {
        Database::iud("UPDATE `user` SET `password`='" . $new_pw . "', `vcode`=NULL WHERE `email`='" . $email . "'");
        echo ("success");
    } else {
        echo ("Invalid or expired Verification Code. Please check your inbox or request a new code.");
    }
}
?>

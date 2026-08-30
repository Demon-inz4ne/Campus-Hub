<?php
include "../includes/connection.php";
include "../includes/mailer.php";

$email = isset($_POST["e"]) ? trim($_POST["e"]) : (isset($_GET["e"]) ? trim($_GET["e"]) : "");

if (empty($email)) {
    echo ("Please enter your Email Address.");
} else if (strlen($email) > 100) {
    echo ("Email Address must contain fewer than 100 characters.");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Invalid Email Address format.");
} else {
    $rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $email . "'");
    if ($rs->num_rows == 1) {
        $user = $rs->fetch_assoc();
        $userName = $user["fname"] . " " . $user["lname"];

        // Generate 6-digit Reset Code
        $vcode = "CH-" . rand(100000, 999999);

        // Update vcode in user record
        Database::iud("UPDATE `user` SET `vcode`='" . $vcode . "' WHERE `email`='" . $email . "'");

        // Send Reset Email
        $sent = CampusMailer::sendPasswordResetEmail($email, $userName, $vcode);
        if ($sent) {
            echo ("success");
        } else {
            echo ("Failed to send reset code email. Please check your internet connection or email configuration.");
        }
    } else {
        echo ("No account found registered with this Email Address.");
    }
}
?>

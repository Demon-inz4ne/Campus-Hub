<?php
include "../includes/connection.php";
include "../includes/mailer.php";

$fname = isset($_POST["f"]) ? trim($_POST["f"]) : "";
$lname = isset($_POST["l"]) ? trim($_POST["l"]) : "";
$email = isset($_POST["e"]) ? trim($_POST["e"]) : "";
$password = isset($_POST["p"]) ? trim($_POST["p"]) : "";
$mobile = isset($_POST["m"]) ? trim($_POST["m"]) : "";
$campus_id = isset($_POST["campus_id"]) ? trim($_POST["campus_id"]) : "0";
$student_no = isset($_POST["student_no"]) ? trim($_POST["student_no"]) : "";

if (empty($fname)) {
    echo ("Please enter your First Name.");
} else if (strlen($fname) > 50) {
    echo ("First Name must contain fewer than 50 characters.");
} else if (empty($lname)) {
    echo ("Please enter your Last Name.");
} else if (strlen($lname) > 50) {
    echo ("Last Name must contain fewer than 50 characters.");
} else if (empty($email)) {
    echo ("Please enter your Email Address.");
} else if (strlen($email) > 100) {
    echo ("Email Address must contain fewer than 100 characters.");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Invalid Email Address format.");
} else if (empty($password)) {
    echo ("Please enter your Password.");
} else if (strlen($password) < 5 || strlen($password) > 20) {
    echo ("Password must contain between 5 to 20 characters.");
} else if (empty($mobile)) {
    echo ("Please enter your Mobile Number.");
} else if (strlen($mobile) != 10) {
    echo ("Mobile Number must contain exactly 10 digits.");
} else if (!preg_match("/07[0,1,2,4,5,6,7,8]{1}[0-9]{7}/", $mobile)) {
    echo ("Invalid Mobile Number format.");
} else if (empty($campus_id) || $campus_id == "0") {
    echo ("Please select your Campus.");
} else if (empty($student_no)) {
    echo ("Please enter your Campus Student Number.");
} else {
    $campus_rs = Database::search("SELECT * FROM `campus` WHERE `id`='" . $campus_id . "'");
    if ($campus_rs->num_rows == 0) {
        echo ("Invalid Campus selected.");
        exit();
    }
    $campus_data = $campus_rs->fetch_assoc();
    $campus_name = $campus_data["name"];

    $rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $email . "' OR `mobile`='" . $mobile . "' OR `student_number`='" . $student_no . "'");
    if ($rs->num_rows > 0) {
        echo ("User with the same Email Address, Mobile Number, or Student Number already exists.");
    } else {
        $d = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $d->setTimezone($tz);
        $date = $d->format("Y-m-d H:i:s");

        // verification code eka 
        $vcode = "CH-" . rand(100000, 999999);

        // tempory add wena student
        Database::iud("INSERT INTO `user` (`email`, `fname`, `lname`, `password`, `mobile`, `institution`, `campus_id`, `student_number`, `user_type_id`, `status_id`, `joined_date`, `profile_img`, `vcode`) 
        VALUES ('" . $email . "', '" . $fname . "', '" . $lname . "', '" . $password . "', '" . $mobile . "', '" . $campus_name . "', '" . $campus_id . "', '" . $student_no . "', '3', '2', '" . $date . "', 'resources/profile_images/default.png', '" . $vcode . "')");

        // Send Email via PHPMailer
        $student_full_name = $fname . " " . $lname;
        CampusMailer::sendVerificationEmail($email, $student_full_name, $campus_name, $student_no, $vcode);

        echo ("success_sent:" . $email . ":" . $campus_name);
    }
}
?>

<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"])) {
    echo ("Please login to perform this action.");
    exit();
}

$user_email = $_SESSION["u"]["email"];
$reg_id = isset($_POST["id"]) ? trim($_POST["id"]) : (isset($_GET["id"]) ? trim($_GET["id"]) : "");

if (empty($reg_id) || !is_numeric($reg_id)) {
    echo ("Invalid registration ID.");
    exit();
}

$chk_rs = Database::search("SELECT * FROM `event_registration` WHERE `id`='" . $reg_id . "' AND `user_email`='" . $user_email . "'");

if ($chk_rs->num_rows == 0 && $_SESSION["u"]["user_type_id"] != 1) {
    echo ("Registration record not found or unauthorized.");
    exit();
}

Database::iud("DELETE FROM `event_registration` WHERE `id`='" . $reg_id . "'");
echo ("success");
?>

<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"]) || ($_SESSION["u"]["user_type_id"] != 1 && $_SESSION["u"]["user_type_id"] != 2)) {
    echo ("Unauthorized access.");
    exit();
}

$form_id = isset($_POST["id"]) ? trim($_POST["id"]) : (isset($_GET["id"]) ? trim($_GET["id"]) : "");

if (empty($form_id) || !is_numeric($form_id)) {
    echo ("Invalid Form ID.");
    exit();
}

$user = $_SESSION["u"];
$user_type = $user["user_type_id"];

if ($user_type == 2) {
    // Sub Admin: check that the form belongs to a student of their campus
    $campus_id = $user["campus_id"];
    $chk_rs = Database::search("SELECT online_form_submission.* FROM `online_form_submission` 
                                INNER JOIN `user` ON online_form_submission.user_email = user.email 
                                WHERE online_form_submission.id = '" . $form_id . "' AND user.campus_id = '" . $campus_id . "'");
    if ($chk_rs->num_rows == 0) {
        echo ("Unauthorized to review form submission from another campus.");
        exit();
    }
}

Database::iud("UPDATE `online_form_submission` SET `status`='Reviewed' WHERE `id`='" . $form_id . "'");
echo ("success");
?>

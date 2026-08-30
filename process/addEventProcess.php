<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"]) || ($_SESSION["u"]["user_type_id"] != 1 && $_SESSION["u"]["user_type_id"] != 2)) {
    echo ("Unauthorized access.");
    exit();
}

$title = isset($_POST["t"]) ? trim($_POST["t"]) : "";
$desc = isset($_POST["d"]) ? trim($_POST["d"]) : "";
$category = isset($_POST["c"]) ? trim($_POST["c"]) : "";
$date = isset($_POST["date"]) ? trim($_POST["date"]) : "";
$location = isset($_POST["loc"]) ? trim($_POST["loc"]) : "";
$max_part = isset($_POST["max"]) ? trim($_POST["max"]) : "50";
$open_all = isset($_POST["open_all"]) ? $_POST["open_all"] : "0";

$user_email = $_SESSION["u"]["email"];
$user_type = $_SESSION["u"]["user_type_id"];

if ($user_type == 2) {
    $campus_id = $_SESSION["u"]["campus_id"];
} else {
    $campus_id = isset($_POST["campus_id"]) && !empty($_POST["campus_id"]) ? $_POST["campus_id"] : "1";
}

if (empty($title)) {
    echo ("Please enter event title.");
} else if (empty($desc)) {
    echo ("Please enter event description.");
} else if (empty($date)) {
    echo ("Please select event date.");
} else if (empty($location)) {
    echo ("Please enter location.");
} else {
    $banner_path = "resources/event_images/default_event.jpg";
    if (isset($_FILES["banner"]) && $_FILES["banner"]["error"] == 0) {
        $file = $_FILES["banner"];
        $ext_arr = explode(".", $file["name"]);
        $ext = strtolower(end($ext_arr));
        $banner_path = "resources/event_images/" . uniqid() . "." . $ext;
        move_uploaded_file($file["tmp_name"], "../" . $banner_path);
    }

    Database::iud("INSERT INTO `event` (`title`, `description`, `category_id`, `campus_id`, `is_open_to_all`, `event_date`, `location`, `banner_img`, `max_participants`, `created_by`, `status_id`) 
    VALUES ('" . $title . "', '" . $desc . "', '" . $category . "', '" . $campus_id . "', '" . $open_all . "', '" . $date . "', '" . $location . "', '" . $banner_path . "', '" . $max_part . "', '" . $user_email . "', '1')");

    echo ("success");
}
?>

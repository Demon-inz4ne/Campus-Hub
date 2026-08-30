<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"]) || $_SESSION["u"]["user_type_id"] != 1) {
    echo ("Unauthorized access.");
    exit();
}

$action = isset($_POST["action"]) ? trim($_POST["action"]) : "";

if ($action == "add") {
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
    $code = isset($_POST["code"]) ? trim($_POST["code"]) : "";

    if (empty($name)) {
        echo ("Please enter campus name.");
    } else if (empty($code)) {
        echo ("Please enter campus code.");
    } else {
        $chk = Database::search("SELECT * FROM `campus` WHERE `name`='" . $name . "' OR `code`='" . $code . "'");
        if ($chk->num_rows > 0) {
            echo ("Campus with the same name or code already exists.");
        } else {
            Database::iud("INSERT INTO `campus` (`name`, `code`) VALUES ('" . $name . "', '" . strtoupper($code) . "')");
            echo ("success");
        }
    }
} else if ($action == "delete") {
    $id = isset($_POST["id"]) ? trim($_POST["id"]) : "";
    if (empty($id)) {
        echo ("Campus ID is required.");
    } else {
        Database::iud("DELETE FROM `campus` WHERE `id`='" . $id . "'");
        echo ("success");
    }
} else {
    echo ("Invalid action.");
}
?>

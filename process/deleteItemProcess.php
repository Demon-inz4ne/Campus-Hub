<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"]) || ($_SESSION["u"]["user_type_id"] != 1 && $_SESSION["u"]["user_type_id"] != 2)) {
    echo ("Unauthorized access.");
    exit();
}

$type = isset($_POST["type"]) ? trim($_POST["type"]) : "";
$id = isset($_POST["id"]) ? trim($_POST["id"]) : "";

if (empty($type) || empty($id)) {
    echo ("Missing parameters.");
    exit();
}

if ($type == "event") {
    Database::iud("DELETE FROM `event` WHERE `id`='" . $id . "'");
    echo ("success");
} else if ($type == "announcement") {
    Database::iud("DELETE FROM `announcement` WHERE `id`='" . $id . "'");
    // Update XML
    $ann_rs = Database::search("SELECT * FROM `announcement` ORDER BY id ASC");
    $xml_content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml_content .= '<!DOCTYPE announcements SYSTEM "announcements.dtd">' . "\n";
    $xml_content .= '<announcements>' . "\n";
    while ($a = $ann_rs->fetch_assoc()) {
        $xml_content .= "    <announcement>\n";
        $xml_content .= "        <id>" . $a["id"] . "</id>\n";
        $xml_content .= "        <title>" . htmlspecialchars($a["title"]) . "</title>\n";
        $xml_content .= "        <content>" . htmlspecialchars($a["content"]) . "</content>\n";
        $xml_content .= "        <date>" . $a["date_published"] . "</date>\n";
        $xml_content .= "        <author>" . htmlspecialchars($a["author_email"]) . "</author>\n";
        $xml_content .= "    </announcement>\n";
    }
    $xml_content .= '</announcements>';
    @file_put_contents("../announcements.xml", $xml_content);
    echo ("success");
} else if ($type == "student") {
    Database::iud("DELETE FROM `user` WHERE `email`='" . $id . "' AND `user_type_id`='3'");
    echo ("success");
} else {
    echo ("Invalid delete target type.");
}
?>

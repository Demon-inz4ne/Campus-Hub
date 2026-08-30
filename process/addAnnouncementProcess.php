<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"]) || ($_SESSION["u"]["user_type_id"] != 1 && $_SESSION["u"]["user_type_id"] != 2)) {
    echo ("Unauthorized access.");
    exit();
}

$title = isset($_POST["t"]) ? trim($_POST["t"]) : "";
$content = isset($_POST["c"]) ? trim($_POST["c"]) : "";
$author = $_SESSION["u"]["email"];
$user_type = $_SESSION["u"]["user_type_id"];

if ($user_type == 2) {
    $campus_id = "'" . $_SESSION["u"]["campus_id"] . "'";
} else {
    $c_id = isset($_POST["campus_id"]) ? trim($_POST["campus_id"]) : "";
    $campus_id = (!empty($c_id) && $c_id != "0") ? "'" . $c_id . "'" : "NULL";
}

if (empty($title)) {
    echo ("Please enter announcement title.");
} else if (empty($content)) {
    echo ("Please enter announcement content.");
} else {
    $d = new DateTime();
    $tz = new DateTimeZone("Asia/Colombo");
    $d->setTimezone($tz);
    $date = $d->format("Y-m-d H:i:s");

    Database::iud("INSERT INTO `announcement` (`title`, `content`, `campus_id`, `date_published`, `author_email`) 
    VALUES ('" . $title . "', '" . $content . "', " . $campus_id . ", '" . $date . "', '" . $author . "')");

    // Regenerate XML file
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
}
?>

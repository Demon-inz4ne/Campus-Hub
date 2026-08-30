<?php
session_start();
include "../includes/connection.php";

if (!isset($_SESSION["u"])) {
    echo ("login_required");
} else if (!isset($_GET["id"])) {
    echo ("Invalid Event Selection.");
} else {
    $user_email = $_SESSION["u"]["email"];
    $event_id = $_GET["id"];

    $ev_rs = Database::search("SELECT * FROM `event` WHERE `id`='" . $event_id . "'");
    if ($ev_rs->num_rows == 0) {
        echo ("Event not found.");
    } else {
        $event_data = $ev_rs->fetch_assoc();
        $max_seats = intval($event_data["max_participants"]);

        $reg_rs = Database::search("SELECT * FROM `event_registration` WHERE `event_id`='" . $event_id . "' AND `user_email`='" . $user_email . "'");
        if ($reg_rs->num_rows > 0) {
            echo ("You have already registered for this event.");
        } else {
            // Check Seat Capacity Validation
            $cnt_rs = Database::search("SELECT COUNT(*) AS total_regs FROM `event_registration` WHERE `event_id`='" . $event_id . "'");
            $current_regs = intval($cnt_rs->fetch_assoc()["total_regs"]);

            if ($max_seats > 0 && $current_regs >= $max_seats) {
                echo ("Seat Capacity Full! This event has reached its maximum limit (" . $max_seats . " seats).");
                exit();
            }

            $d = new DateTime();
            $tz = new DateTimeZone("Asia/Colombo");
            $d->setTimezone($tz);
            $date = $d->format("Y-m-d H:i:s");

            Database::iud("INSERT INTO `event_registration` (`event_id`, `user_email`, `registered_date`, `status`) 
            VALUES ('" . $event_id . "', '" . $user_email . "', '" . $date . "', 'Registered')");

            echo ("success");
        }
    }
}
?>

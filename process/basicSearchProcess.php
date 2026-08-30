<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/connection.php";

$txt = isset($_POST["t"]) ? trim($_POST["t"]) : "";
$cat = isset($_POST["cat"]) ? $_POST["cat"] : "0";

$user_campus_id = (isset($_SESSION["u"]) && !empty($_SESSION["u"]["campus_id"])) ? $_SESSION["u"]["campus_id"] : 0;

$query = "SELECT event.*, category.name AS cat_name, campus.name AS campus_name FROM `event` 
INNER JOIN `category` ON event.category_id=category.id 
LEFT JOIN `campus` ON event.campus_id=campus.id WHERE 1=1 ";

if (isset($_SESSION["u"]) && $_SESSION["u"]["user_type_id"] == 3 && $user_campus_id > 0) {
    $query .= "AND (event.campus_id='" . $user_campus_id . "' OR event.is_open_to_all='1') ";
}

if (!empty($txt)) {
    $query .= "AND (`title` LIKE '%" . $txt . "%' OR `description` LIKE '%" . $txt . "%' OR `location` LIKE '%" . $txt . "%') ";
}

if ($cat != "0") {
    $query .= "AND `category_id`='" . $cat . "' ";
}

$query .= "ORDER BY event_date ASC";

$rs = Database::search($query);
$num = $rs->num_rows;

if ($num == 0) {
?>
    <div class="col-12 text-center py-5">
        <i class="bi bi-calendar-x display-1 text-muted"></i>
        <h5 class="fw-bold text-secondary mt-3">No upcoming events found matching your search criteria.</h5>
    </div>
<?php
} else {
    while ($ev = $rs->fetch_assoc()) {
?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden bg-white">
                <div class="bg-secondary text-white text-center overflow-hidden" style="height: 180px;">
                    <img src="<?php echo htmlspecialchars($ev["banner_img"]); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($ev["title"]); ?>" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-4 text-center\'><i class=\'bi bi-calendar-event display-3 text-warning\'></i></div>';" />
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        <span class="badge bg-primary text-white"><?php echo htmlspecialchars($ev["cat_name"]); ?></span>
                        <span class="badge bg-dark text-white"><?php echo htmlspecialchars($ev["campus_name"] ? $ev["campus_name"] : "Campus Event"); ?></span>
                        <?php if ($ev["is_open_to_all"] == 1): ?>
                            <span class="badge bg-success"><i class="bi bi-globe me-1"></i> Open to All</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><i class="bi bi-building me-1"></i> Campus Only</span>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold card-title text-dark"><?php echo htmlspecialchars($ev["title"]); ?></h5>
                    <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars(substr($ev["description"], 0, 100)) . '...'; ?></p>

                    <div class="small text-secondary mb-3">
                        <div class="mb-1"><i class="bi bi-clock me-1 text-primary"></i> <?php echo date("F d, Y @ h:i A", strtotime($ev["event_date"])); ?></div>
                        <div class="mb-1"><i class="bi bi-geo-alt me-1 text-danger"></i> <?php echo htmlspecialchars($ev["location"]); ?></div>
                        <div><i class="bi bi-people me-1 text-success"></i> Max Seats: <?php echo $ev["max_participants"]; ?></div>
                    </div>

                    <div class="d-flex gap-2 mt-auto">
                        <a href="event_detail.php?id=<?php echo $ev['id']; ?>" class="btn btn-outline-primary btn-sm flex-fill fw-bold">View Details</a>
                        <button class="btn btn-primary btn-sm flex-fill fw-bold" onclick="registerEvent(<?php echo $ev['id']; ?>)">Register Online</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

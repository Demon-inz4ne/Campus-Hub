<?php
include "includes/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$selected_cat = isset($_GET["cat"]) ? $_GET["cat"] : "0";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Events & Activities | CampusHub</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div class="card border-0 shadow-sm p-4 bg-primary text-white rounded-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2"><i class="bi bi-calendar-event me-2 text-warning"></i> Campus Events & Activities</h2>
                    <p class="mb-0 text-white-50">Browse upcoming workshops, student club competitions, sports activities, and academic seminars across universities.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="submit_form.php" class="btn btn-warning fw-bold text-dark"><i class="bi bi-plus-circle me-1"></i> Propose New Event</a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-3 mb-4 bg-white rounded-3">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control" id="basic_search_txt" placeholder="Search event title, keyword or location..." onkeyup="basicSearch(0);">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3">
                    <select class="form-select" id="basic_search_select" onchange="basicSearch(0);">
                        <option value="0">All Event Categories</option>
                        <?php
                        $cat_rs = Database::search("SELECT * FROM `category`");
                        while ($cat = $cat_rs->fetch_assoc()):
                            $selected = ($selected_cat == $cat["id"]) ? "selected" : "";
                        ?>
                            <option value="<?php echo $cat["id"]; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($cat["name"]); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-12 col-md-2 col-lg-2 d-grid">
                    <button class="btn btn-primary fw-bold" onclick="basicSearch(0);"><i class="bi bi-filter me-1"></i> Filter</button>
                </div>
            </div>
        </div>

        <div class="row g-4" id="basicSearchResult">
            <?php
            $user_campus_id = (isset($_SESSION["u"]) && !empty($_SESSION["u"]["campus_id"])) ? $_SESSION["u"]["campus_id"] : 0;
            
            $query = "SELECT event.*, category.name AS cat_name, campus.name AS campus_name FROM `event` 
            INNER JOIN `category` ON event.category_id=category.id 
            LEFT JOIN `campus` ON event.campus_id=campus.id WHERE 1=1 ";
            
            if (isset($_SESSION["u"]) && $_SESSION["u"]["user_type_id"] == 3 && $user_campus_id > 0) {
                $query .= "AND (event.campus_id='" . $user_campus_id . "' OR event.is_open_to_all='1') ";
            }
            if ($selected_cat != "0") {
                $query .= "AND `category_id`='" . $selected_cat . "' ";
            }
            $query .= "ORDER BY event_date ASC";

            $rs = Database::search($query);
            while ($ev = $rs->fetch_assoc()):
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
                                <?php
                                $is_registered = false;
                                if (isset($_SESSION["u"])) {
                                    $cur_user_email = $_SESSION["u"]["email"];
                                    $chk_reg = Database::search("SELECT * FROM `event_registration` WHERE `event_id`='" . $ev["id"] . "' AND `user_email`='" . $cur_user_email . "'");
                                    if ($chk_reg->num_rows > 0) {
                                        $is_registered = true;
                                    }
                                }
                                $tot_reg_cnt = Database::search("SELECT COUNT(*) AS total FROM `event_registration` WHERE `event_id`='" . $ev["id"] . "'")->fetch_assoc()["total"];
                                $is_seat_full = ($tot_reg_cnt >= $ev["max_participants"]);
                                ?>
                                <?php if ($is_registered): ?>
                                    <button class="btn btn-success btn-sm flex-fill fw-bold" id="reg_btn_<?php echo $ev['id']; ?>" onclick="alert('You are already registered for this event! You can manage your registrations from your Profile.');">
                                        <i class="bi bi-check-circle-fill me-1"></i> Registered
                                    </button>
                                <?php elseif ($is_seat_full): ?>
                                    <button class="btn btn-secondary btn-sm flex-fill fw-bold" disabled>
                                        <i class="bi bi-x-circle me-1"></i> Full
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-sm flex-fill fw-bold" id="reg_btn_<?php echo $ev['id']; ?>" onclick="registerEvent(<?php echo $ev['id']; ?>)">
                                        Register Online
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

</body>
</html>

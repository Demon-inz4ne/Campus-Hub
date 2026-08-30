<?php
include "includes/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home | CampusHub Student Portal</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div id="campusCarousel" class="carousel slide rounded-4 overflow-hidden shadow-sm" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#campusCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#campusCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#campusCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active text-white p-5 position-relative" style="background: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url('resources/carousel_images/1.jpg') center/cover no-repeat;">
                    <div class="p-4 p-md-5 text-center my-4">
                        <span class="badge bg-warning text-dark px-3 py-2 uppercase fw-bold mb-2">Campus Events 2026</span>
                        <h1 class="display-5 fw-bold text-white mb-3">Hackathon Innovation Challenge</h1>
                        <p class="fs-5 text-light max-w-75 mx-auto">Register online for upcoming coding competitions, workshops, and sports meets.</p>
                        <a href="events.php" class="btn btn-primary btn-lg fw-bold px-4 mt-2">Explore All Events</a>
                    </div>
                </div>
                <div class="carousel-item text-white p-5 position-relative" style="background: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url('resources/carousel_images/2.jpg') center/cover no-repeat;">
                    <div class="p-4 p-md-5 text-center my-4">
                        <span class="badge bg-light text-primary px-3 py-2 fw-bold mb-2">Student Communities</span>
                        <h1 class="display-5 fw-bold text-white mb-3">Join Student Clubs & Societies</h1>
                        <p class="fs-5 text-light max-w-75 mx-auto">Coordinate activities, submit membership forms, and connect across universities.</p>
                        <a href="submit_form.php" class="btn btn-warning btn-lg fw-bold text-dark px-4 mt-2">Submit Online Form</a>
                    </div>
                </div>
                <div class="carousel-item text-white p-5 position-relative" style="background: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url('resources/carousel_images/3.jpg') center/cover no-repeat;">
                    <div class="p-4 p-md-5 text-center my-4">
                        <span class="badge bg-info text-dark px-3 py-2 fw-bold mb-2">Multimedia Portal</span>
                        <h1 class="display-5 fw-bold text-white mb-3">Event Photos, Audio & Video Highlights</h1>
                        <p class="fs-5 text-light max-w-75 mx-auto">Access campus media content, audio podcasts, and official photo updates.</p>
                        <a href="gallery.php" class="btn btn-light btn-lg fw-bold text-dark px-4 mt-2">View Media Gallery</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#campusCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#campusCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="container mb-4">
        <div class="row g-3">
            <?php
            $users_cnt = Database::search("SELECT COUNT(*) AS total FROM `user` WHERE `user_type_id`=3")->fetch_assoc()["total"];
            $events_cnt = Database::search("SELECT COUNT(*) AS total FROM `event`")->fetch_assoc()["total"];
            $regs_cnt = Database::search("SELECT COUNT(*) AS total FROM `event_registration`")->fetch_assoc()["total"];
            $ann_cnt = Database::search("SELECT COUNT(*) AS total FROM `announcement`")->fetch_assoc()["total"];
            ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center bg-white rounded-3">
                    <h3 class="fw-bold text-primary mb-1"><?php echo $users_cnt; ?>+</h3>
                    <p class="small text-muted mb-0 fw-semibold">Registered Students</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center bg-white rounded-3">
                    <h3 class="fw-bold text-success mb-1"><?php echo $events_cnt; ?></h3>
                    <p class="small text-muted mb-0 fw-semibold">Upcoming Events</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center bg-white rounded-3">
                    <h3 class="fw-bold text-warning mb-1"><?php echo $regs_cnt; ?></h3>
                    <p class="small text-muted mb-0 fw-semibold">Event Registrations</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center bg-white rounded-3">
                    <h3 class="fw-bold text-info mb-1"><?php echo $ann_cnt; ?></h3>
                    <p class="small text-muted mb-0 fw-semibold">Published Announcements</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i> Event Categories</h4>
            <a href="events.php" class="text-primary text-decoration-none fw-bold small">View All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            <?php
            $cat_rs = Database::search("SELECT * FROM `category`");
            while ($cat = $cat_rs->fetch_assoc()):
            ?>
                <div class="col-6 col-md-4 col-lg-2-4">
                    <a href="events.php?cat=<?php echo $cat['id']; ?>" class="text-decoration-none">
                        <div class="card border-0 shadow-sm text-center p-3 h-100 bg-white hover-shadow transition">
                            <i class="bi bi-award fs-1 text-primary mb-2"></i>
                            <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($cat["name"]); ?></h6>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-check-fill text-success me-2"></i> Featured Upcoming Events</h4>
            <a href="events.php" class="text-primary text-decoration-none fw-bold small">Browse Events <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            <?php
            $user_campus_id = (isset($_SESSION["u"]) && !empty($_SESSION["u"]["campus_id"])) ? $_SESSION["u"]["campus_id"] : 0;

            $ev_query = "SELECT event.*, category.name AS cat_name, campus.name AS campus_name FROM `event` 
            INNER JOIN `category` ON event.category_id=category.id 
            LEFT JOIN `campus` ON event.campus_id=campus.id ";

            if (isset($_SESSION["u"]) && $_SESSION["u"]["user_type_id"] == 3 && $user_campus_id > 0) {
                $ev_query .= "WHERE (event.campus_id='" . $user_campus_id . "' OR event.is_open_to_all='1') ";
            }
            $ev_query .= "ORDER BY event_date ASC LIMIT 6";

            $ev_rs = Database::search($ev_query);
            while ($ev = $ev_rs->fetch_assoc()):
            ?>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden bg-white">
                        <div class="bg-secondary text-white text-center overflow-hidden" style="height: 180px;">
                            <img src="<?php echo htmlspecialchars($ev["banner_img"]); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($ev["title"]); ?>" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-4 text-center\'><i class=\'bi bi-calendar-event display-3 text-warning\'></i></div>';" />
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                <span class="badge bg-primary"><?php echo htmlspecialchars($ev["cat_name"]); ?></span>
                                <span class="badge bg-dark"><?php echo htmlspecialchars($ev["campus_name"] ? $ev["campus_name"] : "Campus Event"); ?></span>
                                <?php if ($ev["is_open_to_all"] == 1): ?>
                                    <span class="badge bg-success"><i class="bi bi-globe me-1"></i> Open to All</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-building me-1"></i> Campus Only</span>
                                <?php endif; ?>
                            </div>
                            <h5 class="fw-bold card-title text-dark"><?php echo htmlspecialchars($ev["title"]); ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars(substr($ev["description"], 0, 90)) . '...'; ?></p>

                            <div class="small text-secondary mb-3">
                                <div class="mb-1"><i class="bi bi-clock me-1 text-primary"></i> <?php echo date("F d, Y @ h:i A", strtotime($ev["event_date"])); ?></div>
                                <div><i class="bi bi-geo-alt me-1 text-danger"></i> <?php echo htmlspecialchars($ev["location"]); ?></div>
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
                                        Register
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="container mb-5">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-dark mb-0"><i class="bi bi-megaphone-fill text-warning me-2"></i> Official Announcements</h4>
                <a href="announcements.php" class="btn btn-sm btn-outline-warning text-dark fw-bold">All Announcements</a>
            </div>
            <div class="list-group list-group-flush">
                <?php
                $ann_query = "SELECT announcement.*, campus.name AS campus_name FROM `announcement` LEFT JOIN `campus` ON announcement.campus_id=campus.id ";
                if (isset($_SESSION["u"]) && $_SESSION["u"]["user_type_id"] == 3 && $user_campus_id > 0) {
                    $ann_query .= "WHERE (announcement.campus_id='" . $user_campus_id . "' OR announcement.campus_id IS NULL) ";
                }
                $ann_query .= "ORDER BY date_published DESC LIMIT 4";

                $ann_rs = Database::search($ann_query);
                while ($ann = $ann_rs->fetch_assoc()):
                ?>
                    <div class="list-group-item border-bottom py-3">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <div>
                                <h6 class="mb-1 fw-bold text-primary d-inline me-2"><?php echo htmlspecialchars($ann["title"]); ?></h6>
                                <?php if (!empty($ann["campus_name"])): ?>
                                    <span class="badge bg-secondary small"><?php echo htmlspecialchars($ann["campus_name"]); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark small">All Campuses</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><i class="bi bi-calendar3"></i> <?php echo date("Y-m-d H:i", strtotime($ann["date_published"])); ?></small>
                        </div>
                        <p class="mb-1 small text-secondary"><?php echo htmlspecialchars($ann["content"]); ?></p>
                        <small class="text-muted">Published by <?php echo htmlspecialchars($ann["author_email"]); ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

</body>

</html>
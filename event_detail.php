<?php
include "includes/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET["id"])) {
    header("Location: events.php");
    exit();
}

$event_id = $_GET["id"];
$ev_rs = Database::search("SELECT event.*, category.name AS cat_name, user.fname, user.lname, user.institution FROM `event` 
INNER JOIN `category` ON event.category_id=category.id 
INNER JOIN `user` ON event.created_by=user.email 
WHERE event.id='" . $event_id . "'");

if ($ev_rs->num_rows == 0) {
    header("Location: events.php");
    exit();
}

$ev = $ev_rs->fetch_assoc();

$reg_cnt_rs = Database::search("SELECT COUNT(*) AS total FROM `event_registration` WHERE `event_id`='" . $event_id . "'");
$reg_cnt = $reg_cnt_rs->fetch_assoc()["total"];

$is_registered = false;
if (isset($_SESSION["u"])) {
    $u_mail = $_SESSION["u"]["email"];
    $user_reg_rs = Database::search("SELECT * FROM `event_registration` WHERE `event_id`='" . $event_id . "' AND `user_email`='" . $u_mail . "'");
    if ($user_reg_rs->num_rows > 0) {
        $is_registered = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($ev["title"]); ?> | CampusHub</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="events.php" class="text-decoration-none">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($ev["title"]); ?></li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <span class="badge bg-primary text-white mb-2 align-self-start fs-6"><?php echo htmlspecialchars($ev["cat_name"]); ?></span>
                    <h2 class="fw-bold text-dark mb-3"><?php echo htmlspecialchars($ev["title"]); ?></h2>

                    <div class="p-0 bg-secondary text-white rounded-3 text-center overflow-hidden mb-4" style="height: 300px;">
                        <img src="<?php echo htmlspecialchars($ev["banner_img"]); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($ev["title"]); ?>" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-5 text-center\'><i class=\'bi bi-calendar-event display-1 text-warning\'></i></div>';" />
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Event Overview & Description</h5>
                    <p class="text-secondary leading-relaxed fs-6 mb-4"><?php echo nl2br(htmlspecialchars($ev["description"])); ?></p>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Registered Participants (<?php echo $reg_cnt; ?>)</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Institution</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $participants_rs = Database::search("SELECT user.fname, user.lname, user.institution, event_registration.registered_date 
                                FROM `event_registration` 
                                INNER JOIN `user` ON event_registration.user_email=user.email 
                                WHERE event_registration.event_id='" . $event_id . "' ORDER BY registered_date DESC");
                                $i = 1;
                                if ($participants_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="4" class="text-center text-muted">No students registered yet. Be the first to register!</td></tr>
                                <?php else:
                                    while ($p = $participants_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($p["fname"] . " " . $p["lname"]); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($p["institution"]); ?></span></td>
                                        <td class="small text-muted"><?php echo date("M d, Y h:i A", strtotime($p["registered_date"])); ?></td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 80px;">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Event Information</h5>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">DATE & TIME</small>
                        <span class="fw-bold text-dark"><i class="bi bi-calendar3 text-primary me-1"></i> <?php echo date("F d, Y @ h:i A", strtotime($ev["event_date"])); ?></span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">VENUE / LOCATION</small>
                        <span class="fw-bold text-dark"><i class="bi bi-geo-alt text-danger me-1"></i> <?php echo htmlspecialchars($ev["location"]); ?></span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold">SEAT CAPACITY</small>
                        <span class="fw-bold text-dark"><i class="bi bi-people text-success me-1"></i> <?php echo $reg_cnt; ?> / <?php echo $ev["max_participants"]; ?> Registered</span>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block fw-semibold">ORGANIZER</small>
                        <span class="fw-bold text-dark"><i class="bi bi-building text-info me-1"></i> <?php echo htmlspecialchars($ev["fname"] . " " . $ev["lname"]) . " (" . htmlspecialchars($ev["institution"]) . ")"; ?></span>
                    </div>

                    <div class="d-grid gap-2">
                        <?php if ($is_registered): ?>
                            <div class="alert alert-success text-center fw-bold py-2 mb-0" role="alert">
                                <i class="bi bi-check-circle-fill me-1"></i> You are Registered!
                            </div>
                        <?php elseif ($reg_cnt >= $ev["max_participants"]): ?>
                            <div class="alert alert-danger text-center fw-bold py-2 mb-0" role="alert">
                                <i class="bi bi-x-circle-fill me-1"></i> Seat Capacity Full
                            </div>
                        <?php else: ?>
                            <button class="btn btn-primary btn-lg fw-bold" onclick="registerEvent(<?php echo $ev['id']; ?>)">
                                <i class="bi bi-check2-circle me-1"></i> Register Online Now
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

</body>
</html>

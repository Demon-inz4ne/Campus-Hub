<?php
include "includes/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["u"])) {
    header("Location: index.php");
    exit();
}

$u = $_SESSION["u"];
$u_email = $u["email"];
$user_rs = Database::search("SELECT user.*, campus.name AS campus_name FROM `user` LEFT JOIN `campus` ON user.campus_id=campus.id WHERE `email`='" . $u_email . "'");
$user_data = $user_rs->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile | CampusHub</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class="bi bi-person-bounding-box text-primary me-2"></i> User Profile</h4>

                    <div class="text-center mb-4 position-relative">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="<?php echo htmlspecialchars($user_data["profile_img"]); ?>" id="profile_img_preview" class="rounded-circle profile-avatar-lg shadow border object-fit-cover" style="width: 140px; height: 140px;" alt="Avatar" onerror="this.onerror=null; this.src='resources/profile_images/default.png';" />
                            <label for="pf_img_uploader" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow hover-pointer" style="cursor: pointer;" title="Change Profile Photo">
                                <i class="bi bi-camera-fill fs-5"></i>
                            </label>
                        </div>
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($user_data["fname"] . " " . $user_data["lname"]); ?></h5>
                        <div class="d-flex justify-content-center gap-1 flex-wrap mb-2">
                            <span class="badge bg-primary px-3 py-2 fw-semibold"><?php echo htmlspecialchars($user_data["campus_name"] ? $user_data["campus_name"] : $user_data["institution"]); ?></span>
                            <?php if (!empty($user_data["student_number"])): ?>
                                <span class="badge bg-dark px-3 py-2 fw-semibold">ID: <?php echo htmlspecialchars($user_data["student_number"]); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($user_data["profile_img"] != "resources/profile_images/default.png"): ?>
                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold rounded-pill" onclick="removeProfileImage();">
                                <i class="bi bi-trash me-1"></i> Remove Custom Photo
                            </button>
                        <?php endif; ?>
                    </div>

                    <form id="profileForm" onsubmit="return false;">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email Address (Readonly)</label>
                            <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($user_data["email"]); ?>" readonly>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">First Name</label>
                                <input type="text" class="form-control" id="pf_fname" value="<?php echo htmlspecialchars($user_data["fname"]); ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Last Name</label>
                                <input type="text" class="form-control" id="pf_lname" value="<?php echo htmlspecialchars($user_data["lname"]); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Mobile Number</label>
                            <input type="text" class="form-control" id="pf_mobile" value="<?php echo htmlspecialchars($user_data["mobile"]); ?>">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Campus</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user_data["campus_name"] ? $user_data["campus_name"] : $user_data["institution"]); ?>" readonly>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Student Number</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user_data["student_number"] ? $user_data["student_number"] : "N/A"); ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Select Profile Photo</label>
                            <input type="file" class="form-control" id="pf_img_uploader" accept="image/*" onchange="previewProfileImage(this);">
                            <div class="form-text">Supports JPG, PNG, WEBP, or SVG formats.</div>
                        </div>
                        <button type="button" class="btn btn-primary fw-bold w-100" onclick="updateProfile();"><i class="bi bi-save me-1"></i> Save Profile Changes</button>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-calendar-check text-success me-2"></i> My Event Registrations</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Event Title</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $my_regs_rs = Database::search("SELECT event_registration.id AS reg_id, event.id AS event_id, event.title, event.event_date, event.location, event_registration.status, event_registration.registered_date 
                                FROM `event_registration` 
                                INNER JOIN `event` ON event_registration.event_id=event.id 
                                WHERE event_registration.user_email='" . $u_email . "' ORDER BY registered_date DESC");

                                if ($my_regs_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">You haven't registered for any events yet.</td></tr>
                                <?php else:
                                    while ($reg = $my_regs_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($reg["title"]); ?></td>
                                        <td class="small text-secondary"><?php echo date("M d, Y", strtotime($reg["event_date"])); ?></td>
                                        <td class="small text-secondary"><?php echo htmlspecialchars($reg["location"]); ?></td>
                                        <td><span class="badge bg-primary"><i class="bi bi-check-circle-fill me-1"></i> Registered</span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger fw-semibold" onclick="unregisterEvent(<?php echo $reg['reg_id']; ?>);">
                                                <i class="bi bi-x-circle me-1"></i> Unregister
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-file-earmark-text text-warning me-2"></i> Submitted Online Forms</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $my_forms_rs = Database::search("SELECT * FROM `online_form_submission` WHERE `user_email`='" . $u_email . "' ORDER BY submitted_date DESC");

                                if ($my_forms_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="4" class="text-center text-muted py-3">No form submissions recorded.</td></tr>
                                <?php else:
                                    while ($fm = $my_forms_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($fm["subject"]); ?></td>
                                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($fm["category"]); ?></span></td>
                                        <td class="small text-secondary"><?php echo date("M d, Y", strtotime($fm["submitted_date"])); ?></td>
                                        <td>
                                            <?php if ($fm["status"] == "Reviewed"): ?>
                                                <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i> Reviewed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending Review</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

    <script>
    function unregisterEvent(regId) {
        if (!confirm("Are you sure you want to unregister from this event?")) {
            return;
        }

        var form = new FormData();
        form.append("id", regId);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                var response = request.responseText.trim();
                if (response == "success") {
                    alert("You have been successfully unregistered from the event.");
                    window.location.reload();
                } else {
                    alert(response);
                }
            }
        };
        request.open("POST", "process/unregisterEventProcess.php", true);
        request.send(form);
    }
    </script>
</body>
</html>

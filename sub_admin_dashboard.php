<?php
include "includes/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["u"]) || $_SESSION["u"]["user_type_id"] != 2) {
    header("Location: index.php");
    exit();
}

$sub_admin = $_SESSION["u"];
$campus_id = $sub_admin["campus_id"];

$campus_rs = Database::search("SELECT * FROM `campus` WHERE `id`='" . $campus_id . "'");
$campus_data = $campus_rs ? $campus_rs->fetch_assoc() : null;
$campus_name = $campus_data ? $campus_data["name"] : "Campus Sub-Admin";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sub-Admin Portal | <?php echo htmlspecialchars($campus_name); ?></title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div class="card border-0 shadow-sm p-4 bg-primary text-white rounded-4 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2"><i class="bi bi-shield-check me-1"></i> Sub-Admin Portal</span>
                    <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($campus_name); ?></h2>
                    <p class="mb-0 text-white-50">Manage campus student verification requests, campus events, and announcements.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#addCampusEventModal">
                        <i class="bi bi-calendar-plus me-1"></i> Add Campus Event
                    </button>
                    <button class="btn btn-light fw-bold text-primary" data-bs-toggle="modal" data-bs-target="#addCampusAnnouncementModal">
                        <i class="bi bi-megaphone me-1"></i> Publish Announcement
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <?php
            $pending_cnt = Database::search("SELECT COUNT(*) AS total FROM `user` WHERE `campus_id`='" . $campus_id . "' AND `status_id`='2'")->fetch_assoc()["total"];
            $active_cnt = Database::search("SELECT COUNT(*) AS total FROM `user` WHERE `campus_id`='" . $campus_id . "' AND `status_id`='1' AND `user_type_id`='3'")->fetch_assoc()["total"];
            $events_cnt = Database::search("SELECT COUNT(*) AS total FROM `event` WHERE `campus_id`='" . $campus_id . "'")->fetch_assoc()["total"];
            $ann_cnt = Database::search("SELECT COUNT(*) AS total FROM `announcement` WHERE `campus_id`='" . $campus_id . "'")->fetch_assoc()["total"];
            $pending_forms_cnt = Database::search("SELECT COUNT(*) AS total FROM `online_form_submission` INNER JOIN `user` ON online_form_submission.user_email = user.email WHERE user.campus_id='" . $campus_id . "' AND online_form_submission.status='Pending'")->fetch_assoc()["total"];
            ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3 border-start border-warning border-4">
                    <h3 class="fw-bold text-warning mb-0"><?php echo $pending_cnt; ?></h3>
                    <small class="text-muted fw-bold">Pending Student Approvals</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3 border-start border-success border-4">
                    <h3 class="fw-bold text-success mb-0"><?php echo $active_cnt; ?></h3>
                    <small class="text-muted fw-bold">Verified Campus Students</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3 border-start border-primary border-4">
                    <h3 class="fw-bold text-primary mb-0"><?php echo $events_cnt; ?></h3>
                    <small class="text-muted fw-bold">Campus Events</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3 border-start border-danger border-4">
                    <h3 class="fw-bold text-danger mb-0"><?php echo $pending_forms_cnt; ?></h3>
                    <small class="text-muted fw-bold">Pending Form Submissions</small>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs fw-bold mb-4" id="subAdminTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-panel" type="button" role="tab">
                    <i class="bi bi-hourglass-split me-1"></i> Pending Verification 
                    <?php if ($pending_cnt > 0): ?>
                        <span class="badge bg-danger ms-1"><?php echo $pending_cnt; ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students-panel" type="button" role="tab">
                    <i class="bi bi-people me-1"></i> Verified Students
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="forms-tab" data-bs-toggle="tab" data-bs-target="#forms-panel" type="button" role="tab">
                    <i class="bi bi-file-earmark-text me-1"></i> Student Forms & Inquiries
                    <?php if ($pending_forms_cnt > 0): ?>
                        <span class="badge bg-danger ms-1"><?php echo $pending_forms_cnt; ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events-panel" type="button" role="tab">
                    <i class="bi bi-calendar-event me-1"></i> Campus Events
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements-panel" type="button" role="tab">
                    <i class="bi bi-megaphone me-1"></i> Campus Announcements
                </button>
            </li>
        </ul>

        <div class="tab-content" id="subAdminTabContent">

            <!-- Pending Approvals Tab -->
            <div class="tab-pane fade show active" id="pending-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-check-fill text-warning me-2"></i> Registration Approval Requests</h5>
                        <span class="small text-muted">Review student ID and verify registration</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-warning">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Student Number</th>
                                    <th>Email Address</th>
                                    <th>Mobile</th>
                                    <th>Verification Code</th>
                                    <th>Registered Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pend_rs = Database::search("SELECT * FROM `user` WHERE `campus_id`='" . $campus_id . "' AND `status_id`='2' ORDER BY joined_date DESC");
                                if ($pend_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-check-circle fs-3 d-block text-success mb-2"></i>No pending student verification requests.</td></tr>
                                <?php else:
                                    while ($st = $pend_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($st["fname"] . " " . $st["lname"]); ?></td>
                                        <td><span class="badge bg-primary fs-6"><?php echo htmlspecialchars($st["student_number"] ? $st["student_number"] : "N/A"); ?></span></td>
                                        <td><?php echo htmlspecialchars($st["email"]); ?></td>
                                        <td><?php echo htmlspecialchars($st["mobile"]); ?></td>
                                        <td><code class="fw-bold text-dark bg-warning px-2 py-1 rounded"><?php echo htmlspecialchars($st["vcode"]); ?></code></td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($st["joined_date"])); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-success fw-bold me-1" onclick="verifyStudent('<?php echo $st['email']; ?>', 'approve');">
                                                <i class="bi bi-check-lg"></i> Approve / Verify
                                            </button>
                                            <button class="btn btn-sm btn-danger fw-bold" onclick="verifyStudent('<?php echo $st['email']; ?>', 'reject');">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Verified Students Tab -->
            <div class="tab-pane fade" id="students-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <h5 class="fw-bold text-dark mb-3">Approved Campus Students</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student Number</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Joined Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $act_rs = Database::search("SELECT user.*, status.name AS st_name FROM `user` INNER JOIN `status` ON user.status_id=status.id WHERE `campus_id`='" . $campus_id . "' AND `status_id`='1' AND `user_type_id`='3' ORDER BY joined_date DESC");
                                if ($act_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="6" class="text-center text-muted py-3">No active students registered for this campus yet.</td></tr>
                                <?php else:
                                    while ($ast = $act_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($ast["student_number"]); ?></span></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($ast["fname"] . " " . $ast["lname"]); ?></td>
                                        <td><?php echo htmlspecialchars($ast["email"]); ?></td>
                                        <td><?php echo htmlspecialchars($ast["mobile"]); ?></td>
                                        <td class="small text-muted"><?php echo date("Y-m-d", strtotime($ast["joined_date"])); ?></td>
                                        <td><span class="badge bg-success"><?php echo htmlspecialchars($ast["st_name"]); ?></span></td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Student Inquiries & Forms Tab -->
            <div class="tab-pane fade" id="forms-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i> Student Form Submissions & Proposals</h5>
                        <span class="small text-muted">Review proposals, club requests, and inquiries from students of <?php echo htmlspecialchars($campus_name); ?></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>Student Info</th>
                                    <th>Subject & Category</th>
                                    <th>Message Details</th>
                                    <th>Attachment</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $camp_forms_rs = Database::search("SELECT online_form_submission.*, user.fname, user.lname, user.student_number 
                                                                  FROM `online_form_submission` 
                                                                  INNER JOIN `user` ON online_form_submission.user_email = user.email 
                                                                  WHERE user.campus_id = '" . $campus_id . "' 
                                                                  ORDER BY online_form_submission.submitted_date DESC");
                                if ($camp_forms_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block text-secondary mb-2"></i>No form submissions from students of this campus.</td></tr>
                                <?php else:
                                    while ($cfm = $camp_forms_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($cfm["fname"] . " " . $cfm["lname"]); ?></div>
                                            <small class="text-primary fw-semibold"><?php echo htmlspecialchars($cfm["student_number"] ? $cfm["student_number"] : "N/A"); ?></small><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($cfm["user_email"]); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($cfm["subject"]); ?></div>
                                            <span class="badge bg-info text-dark mt-1"><?php echo htmlspecialchars($cfm["category"]); ?></span>
                                        </td>
                                        <td class="small text-secondary" style="max-width: 250px;"><?php echo nl2br(htmlspecialchars($cfm["message"])); ?></td>
                                        <td>
                                            <?php if (!empty($cfm["attachment"])): ?>
                                                <a href="<?php echo htmlspecialchars($cfm["attachment"]); ?>" target="_blank" class="btn btn-sm btn-outline-primary fw-semibold">
                                                    <i class="bi bi-paperclip me-1"></i> View File
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">No file</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($cfm["submitted_date"])); ?></td>
                                        <td>
                                            <?php if ($cfm["status"] == "Reviewed"): ?>
                                                <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i> Reviewed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($cfm["status"] != "Reviewed"): ?>
                                                <button class="btn btn-sm btn-success fw-bold" onclick="reviewFormSubmission(<?php echo $cfm['id']; ?>);">
                                                    <i class="bi bi-check-lg me-1"></i> Mark as Reviewed
                                                </button>
                                            <?php else: ?>
                                                <span class="text-success small fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Campus Events Tab -->
            <div class="tab-pane fade" id="events-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Campus Events Directory</h5>
                        <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addCampusEventModal"><i class="bi bi-plus-lg me-1"></i> Create Event</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date & Time</th>
                                    <th>Venue</th>
                                    <th>Visibility</th>
                                    <th>Max Seats</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cev_rs = Database::search("SELECT event.*, category.name AS cat_name FROM `event` INNER JOIN `category` ON event.category_id=category.id WHERE `campus_id`='" . $campus_id . "' ORDER BY event_date ASC");
                                if ($cev_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="6" class="text-center text-muted py-3">No events created for this campus yet.</td></tr>
                                <?php else:
                                    while ($cem = $cev_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($cem["title"]); ?></td>
                                        <td><span class="badge bg-primary"><?php echo htmlspecialchars($cem["cat_name"]); ?></span></td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($cem["event_date"])); ?></td>
                                        <td><?php echo htmlspecialchars($cem["location"]); ?></td>
                                        <td>
                                            <?php if ($cem["is_open_to_all"] == 1): ?>
                                                <span class="badge bg-success"><i class="bi bi-globe me-1"></i> Open to All</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-building me-1"></i> Campus Only</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $cem["max_participants"]; ?> Seats</td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Campus Announcements Tab -->
            <div class="tab-pane fade" id="announcements-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Campus Official Announcements</h5>
                        <button class="btn btn-sm btn-info text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addCampusAnnouncementModal"><i class="bi bi-megaphone me-1"></i> Publish Announcement</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-info">
                                <tr>
                                    <th>Title</th>
                                    <th>Content Preview</th>
                                    <th>Published Date</th>
                                    <th>Author</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $canc_rs = Database::search("SELECT * FROM `announcement` WHERE `campus_id`='" . $campus_id . "' ORDER BY date_published DESC");
                                if ($canc_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="4" class="text-center text-muted py-3">No campus announcements published yet.</td></tr>
                                <?php else:
                                    while ($can = $canc_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($can["title"]); ?></td>
                                        <td class="small text-secondary"><?php echo htmlspecialchars(substr($can["content"], 0, 100)) . '...'; ?></td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($can["date_published"])); ?></td>
                                        <td class="small"><?php echo htmlspecialchars($can["author_email"]); ?></td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal: Add Campus Event -->
    <div class="modal fade" id="addCampusEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus me-2"></i> Create Campus Event (<?php echo htmlspecialchars($campus_name); ?>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Event Title</label>
                        <input type="text" class="form-control" id="cev_title" placeholder="Ex: Campus Coding Championship 2026">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select" id="cev_category">
                                <?php
                                $c_rs = Database::search("SELECT * FROM `category`");
                                while ($c = $c_rs->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $c["id"]; ?>"><?php echo htmlspecialchars($c["name"]); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Max Participants / Capacity</label>
                            <input type="number" class="form-control" id="cev_max" value="50">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Date & Time</label>
                            <input type="datetime-local" class="form-control" id="cev_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Location / Venue</label>
                            <input type="text" class="form-control" id="cev_location" placeholder="Ex: Main Hall">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch bg-light p-3 rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="cev_open_to_all">
                            <label class="form-check-label fw-bold text-dark" for="cev_open_to_all">
                                Open to Students from All Campuses
                            </label>
                            <div class="form-text">If enabled, students from other universities will also see and register for this event.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Event Description</label>
                        <textarea class="form-control" id="cev_desc" rows="3" placeholder="Enter event details..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Banner Image Upload</label>
                        <input type="file" class="form-control" id="cev_banner" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning fw-bold text-dark" onclick="addCampusEvent();">Publish Campus Event</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Campus Announcement -->
    <div class="modal fade" id="addCampusAnnouncementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-megaphone me-2"></i> Publish Campus Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Announcement Title</label>
                        <input type="text" class="form-control" id="canc_title" placeholder="Ex: Exam Schedule Released">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Content</label>
                        <textarea class="form-control" id="canc_content" rows="4" placeholder="Enter full announcement text..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary fw-bold" onclick="addCampusAnnouncement();">Publish</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

    <script>
    function verifyStudent(email, action) {
        if (!confirm("Are you sure you want to " + action + " this student registration?")) {
            return;
        }

        var form = new FormData();
        form.append("email", email);
        form.append("action", action);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                var response = request.responseText;
                if (response == "success") {
                    alert("Student registration " + (action == "approve" ? "Approved!" : "Rejected!"));
                    window.location.reload();
                } else {
                    alert(response);
                }
            }
        };
        request.open("POST", "process/verifyStudentProcess.php", true);
        request.send(form);
    }

    function addCampusEvent() {
        var title = document.getElementById("cev_title");
        var desc = document.getElementById("cev_desc");
        var category = document.getElementById("cev_category");
        var date = document.getElementById("cev_date");
        var location = document.getElementById("cev_location");
        var maxPart = document.getElementById("cev_max");
        var openToAll = document.getElementById("cev_open_to_all");
        var banner = document.getElementById("cev_banner");

        var form = new FormData();
        form.append("t", title.value);
        form.append("d", desc.value);
        form.append("c", category.value);
        form.append("date", date.value);
        form.append("loc", location.value);
        form.append("max", maxPart.value);
        form.append("open_all", openToAll && openToAll.checked ? "1" : "0");
        if (banner && banner.files.length > 0) {
            form.append("banner", banner.files[0]);
        }

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                var response = request.responseText;
                if (response == "success") {
                    alert("Campus Event Created Successfully!");
                    window.location.reload();
                } else {
                    alert(response);
                }
            }
        };
        request.open("POST", "process/addEventProcess.php", true);
        request.send(form);
    }

    function addCampusAnnouncement() {
        var title = document.getElementById("canc_title");
        var content = document.getElementById("canc_content");

        var form = new FormData();
        form.append("t", title.value);
        form.append("c", content.value);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                var response = request.responseText;
                if (response == "success") {
                    alert("Campus Announcement Published Successfully!");
                    window.location.reload();
                } else {
                    alert(response);
                }
            }
        };
        request.open("POST", "process/addAnnouncementProcess.php", true);
        request.send(form);
    }

    function reviewFormSubmission(formId) {
        if (!confirm("Are you sure you want to mark this student submission as Reviewed?")) {
            return;
        }

        var form = new FormData();
        form.append("id", formId);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                var response = request.responseText.trim();
                if (response == "success") {
                    alert("Submission marked as Reviewed successfully!");
                    window.location.reload();
                } else {
                    alert(response);
                }
            }
        };
        request.open("POST", "process/reviewFormProcess.php", true);
        request.send(form);
    }
    </script>
</body>
</html>

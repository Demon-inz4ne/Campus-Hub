<?php
include "includes/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["u"]) || $_SESSION["u"]["user_type_id"] != 1) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Main Admin Dashboard | CampusHub System Management</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2"><i class="bi bi-shield-lock me-1"></i> System Main Admin</span>
                    <h2 class="fw-bold mb-1"><i class="bi bi-speedometer2 text-warning me-2"></i> CampusHub Master Management</h2>
                    <p class="mb-0 text-white-50">Full CRUD control over Campuses, Sub-Admins, Students, Events, and System Announcements.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#addCampusModal">
                        <i class="bi bi-building-add me-1"></i> Add Campus
                    </button>
                    <button class="btn btn-info fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#addSubAdminModal">
                        <i class="bi bi-person-plus-fill me-1"></i> Add Sub-Admin
                    </button>
                    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-lg me-1"></i> Create Event
                    </button>
                    <button class="btn btn-light fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                        <i class="bi bi-megaphone me-1"></i> Publish Notice
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <?php
            $campus_cnt = Database::search("SELECT COUNT(*) AS total FROM `campus`")->fetch_assoc()["total"];
            $subadmin_cnt = Database::search("SELECT COUNT(*) AS total FROM `user` WHERE `user_type_id`='2'")->fetch_assoc()["total"];
            $students_cnt = Database::search("SELECT COUNT(*) AS total FROM `user` WHERE `user_type_id`='3'")->fetch_assoc()["total"];
            $events_cnt = Database::search("SELECT COUNT(*) AS total FROM `event`")->fetch_assoc()["total"];
            ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3">
                    <h3 class="fw-bold text-danger mb-0"><?php echo $campus_cnt; ?></h3>
                    <small class="text-muted fw-bold">Active Campuses</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3">
                    <h3 class="fw-bold text-warning mb-0"><?php echo $subadmin_cnt; ?></h3>
                    <small class="text-muted fw-bold">Campus Sub-Admins</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3">
                    <h3 class="fw-bold text-primary mb-0"><?php echo $students_cnt; ?></h3>
                    <small class="text-muted fw-bold">Total Students</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3">
                    <h3 class="fw-bold text-success mb-0"><?php echo $events_cnt; ?></h3>
                    <small class="text-muted fw-bold">Total Events</small>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs fw-bold mb-4" id="adminTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="campuses-tab" data-bs-toggle="tab" data-bs-target="#campuses-panel" type="button" role="tab"><i class="bi bi-buildings me-1"></i> Campuses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="subadmins-tab" data-bs-toggle="tab" data-bs-target="#subadmins-panel" type="button" role="tab"><i class="bi bi-shield-person me-1"></i> Sub-Admins</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students-panel" type="button" role="tab"><i class="bi bi-people me-1"></i> Student Records</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events-panel" type="button" role="tab"><i class="bi bi-calendar-event me-1"></i> Manage Events</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements-panel" type="button" role="tab"><i class="bi bi-megaphone me-1"></i> Announcements</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="registrations-tab" data-bs-toggle="tab" data-bs-target="#registrations-panel" type="button" role="tab"><i class="bi bi-card-checklist me-1"></i> Registrations & Forms</button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabContent">

            <!-- 1. Campuses Management Tab -->
            <div class="tab-pane fade show active" id="campuses-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-buildings text-danger me-2"></i> System Campuses</h5>
                        <button class="btn btn-sm btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addCampusModal"><i class="bi bi-plus-lg me-1"></i> Add New Campus</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-danger">
                                <tr>
                                    <th>ID</th>
                                    <th>Campus Name</th>
                                    <th>Campus Code</th>
                                    <th>Registered Students</th>
                                    <th>Events</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c_list_rs = Database::search("SELECT campus.*, 
                                (SELECT COUNT(*) FROM `user` WHERE `campus_id`=campus.id AND `user_type_id`='3') AS st_count,
                                (SELECT COUNT(*) FROM `event` WHERE `campus_id`=campus.id) AS ev_count
                                FROM `campus` ORDER BY name ASC");
                                while ($c_item = $c_list_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td>#<?php echo $c_item["id"]; ?></td>
                                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($c_item["name"]); ?></td>
                                        <td><span class="badge bg-dark"><?php echo htmlspecialchars($c_item["code"]); ?></span></td>
                                        <td><span class="badge bg-info text-dark"><?php echo $c_item["st_count"]; ?> Students</span></td>
                                        <td><span class="badge bg-secondary"><?php echo $c_item["ev_count"]; ?> Events</span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger fw-bold" onclick="deleteCampus(<?php echo $c_item['id']; ?>);">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. Sub-Admins Management Tab -->
            <div class="tab-pane fade" id="subadmins-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-person text-warning me-2"></i> Campus Sub-Admins</h5>
                        <button class="btn btn-sm btn-warning text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addSubAdminModal"><i class="bi bi-person-plus-fill me-1"></i> Add Sub-Admin</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-warning">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Assigned Campus</th>
                                    <th>Joined Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sa_rs = Database::search("SELECT user.*, campus.name AS campus_name FROM `user` LEFT JOIN `campus` ON user.campus_id=campus.id WHERE `user_type_id`='2' ORDER BY joined_date DESC");
                                if ($sa_rs->num_rows == 0):
                                ?>
                                    <tr><td colspan="6" class="text-center text-muted py-3">No Sub-Admins assigned yet.</td></tr>
                                <?php else:
                                    while ($sa = $sa_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($sa["fname"] . " " . $sa["lname"]); ?></td>
                                        <td><?php echo htmlspecialchars($sa["email"]); ?></td>
                                        <td><?php echo htmlspecialchars($sa["mobile"]); ?></td>
                                        <td><span class="badge bg-primary fs-6"><?php echo htmlspecialchars($sa["campus_name"] ? $sa["campus_name"] : "Unassigned"); ?></span></td>
                                        <td class="small text-muted"><?php echo date("Y-m-d", strtotime($sa["joined_date"])); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger fw-bold" onclick="deleteSubAdmin('<?php echo $sa['email']; ?>');">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Student Records Tab -->
            <div class="tab-pane fade" id="students-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="row align-items-center mb-3 g-2">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-dark mb-0">System Student Records</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-md-end">
                                <label class="small fw-semibold text-muted mb-0">Filter Campus:</label>
                                <select class="form-select form-select-sm w-auto" id="student_campus_filter" onchange="filterStudents();">
                                    <option value="0">All Campuses</option>
                                    <?php
                                    $fc_rs = Database::search("SELECT * FROM `campus` ORDER BY name ASC");
                                    while ($fc = $fc_rs->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $fc["id"]; ?>"><?php echo htmlspecialchars($fc["name"]); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student No</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Campus</th>
                                    <th>V-Code</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTableBody">
                                <?php
                                $st_rs = Database::search("SELECT user.*, status.name AS st_name, campus.name AS campus_name FROM `user` INNER JOIN `status` ON user.status_id=status.id LEFT JOIN `campus` ON user.campus_id=campus.id WHERE `user_type_id`='3' ORDER BY joined_date DESC");
                                while ($st = $st_rs->fetch_assoc()):
                                ?>
                                    <tr class="student-row" data-campus="<?php echo $st['campus_id']; ?>">
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($st["student_number"] ? $st["student_number"] : "N/A"); ?></span></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($st["fname"] . " " . $st["lname"]); ?></td>
                                        <td><?php echo htmlspecialchars($st["email"]); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($st["campus_name"] ? $st["campus_name"] : $st["institution"]); ?></span></td>
                                        <td><code><?php echo htmlspecialchars($st["vcode"]); ?></code></td>
                                        <td>
                                            <?php if ($st["status_id"] == 1): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif ($st["status_id"] == 2): ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($st["status_id"] == 2): ?>
                                                <button class="btn btn-sm btn-success fw-bold me-1" onclick="verifyStudent('<?php echo $st['email']; ?>', 'approve');">Approve</button>
                                                <button class="btn btn-sm btn-warning text-dark fw-bold me-1" onclick="verifyStudent('<?php echo $st['email']; ?>', 'reject');">Reject</button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-danger fw-bold" onclick="deleteStudent('<?php echo $st['email']; ?>');"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. Manage Events Tab -->
            <div class="tab-pane fade" id="events-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="row align-items-center mb-3 g-2">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-dark mb-0">Campus Events Directory</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-md-end">
                                <label class="small fw-semibold text-muted mb-0">Filter Campus:</label>
                                <select class="form-select form-select-sm w-auto" id="event_campus_filter" onchange="filterEvents();">
                                    <option value="0">All Campuses</option>
                                    <?php
                                    $fc_rs2 = Database::search("SELECT * FROM `campus` ORDER BY name ASC");
                                    while ($fc2 = $fc_rs2->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $fc2["id"]; ?>"><?php echo htmlspecialchars($fc2["name"]); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>Event Title</th>
                                    <th>Campus</th>
                                    <th>Category</th>
                                    <th>Visibility</th>
                                    <th>Date & Time</th>
                                    <th>Venue</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="eventsTableBody">
                                <?php
                                $ev_m_rs = Database::search("SELECT event.*, category.name AS cat_name, campus.name AS campus_name FROM `event` INNER JOIN `category` ON event.category_id=category.id LEFT JOIN `campus` ON event.campus_id=campus.id ORDER BY event_date ASC");
                                while ($em = $ev_m_rs->fetch_assoc()):
                                ?>
                                    <tr class="event-row" data-campus="<?php echo $em['campus_id']; ?>">
                                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($em["title"]); ?></td>
                                        <td><span class="badge bg-dark"><?php echo htmlspecialchars($em["campus_name"]); ?></span></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($em["cat_name"]); ?></span></td>
                                        <td>
                                            <?php if ($em["is_open_to_all"] == 1): ?>
                                                <span class="badge bg-success"><i class="bi bi-globe me-1"></i> Open to All</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-building me-1"></i> Campus Only</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($em["event_date"])); ?></td>
                                        <td><?php echo htmlspecialchars($em["location"]); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger fw-bold" onclick="deleteItem('event', <?php echo $em['id']; ?>);"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5. Announcements Tab -->
            <div class="tab-pane fade" id="announcements-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <div class="row align-items-center mb-3 g-2">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-dark mb-0">System Announcements</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-md-end">
                                <label class="small fw-semibold text-muted mb-0">Filter Campus:</label>
                                <select class="form-select form-select-sm w-auto" id="ann_campus_filter" onchange="filterAnnouncements();">
                                    <option value="0">All Campuses</option>
                                    <?php
                                    $fc_rs3 = Database::search("SELECT * FROM `campus` ORDER BY name ASC");
                                    while ($fc3 = $fc_rs3->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $fc3["id"]; ?>"><?php echo htmlspecialchars($fc3["name"]); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-info">
                                <tr>
                                    <th>Title</th>
                                    <th>Campus Scope</th>
                                    <th>Published Date</th>
                                    <th>Author</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="annTableBody">
                                <?php
                                $ann_rs = Database::search("SELECT announcement.*, campus.name AS campus_name FROM `announcement` LEFT JOIN `campus` ON announcement.campus_id=campus.id ORDER BY date_published DESC");
                                while ($ann_item = $ann_rs->fetch_assoc()):
                                ?>
                                    <tr class="ann-row" data-campus="<?php echo $ann_item['campus_id'] ? $ann_item['campus_id'] : '0'; ?>">
                                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($ann_item["title"]); ?></td>
                                        <td>
                                            <?php if (!empty($ann_item["campus_name"])): ?>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($ann_item["campus_name"]); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">All Campuses System-Wide</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($ann_item["date_published"])); ?></td>
                                        <td class="small"><?php echo htmlspecialchars($ann_item["author_email"]); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger fw-bold" onclick="deleteItem('announcement', <?php echo $ann_item['id']; ?>);"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 6. Registrations & Form Submissions Tab -->
            <div class="tab-pane fade" id="registrations-panel" role="tabpanel">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Student Event Registrations</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-warning">
                                <tr>
                                    <th>Reg ID</th>
                                    <th>Event Name</th>
                                    <th>Student Email</th>
                                    <th>Registered On</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $all_regs = Database::search("SELECT event_registration.*, event.title FROM `event_registration` INNER JOIN `event` ON event_registration.event_id=event.id ORDER BY registered_date DESC");
                                while ($rg = $all_regs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td>#REG-<?php echo $rg["id"]; ?></td>
                                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($rg["title"]); ?></td>
                                        <td><?php echo htmlspecialchars($rg["user_email"]); ?></td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($rg["registered_date"])); ?></td>
                                        <td><span class="badge bg-success"><?php echo htmlspecialchars($rg["status"]); ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <h5 class="fw-bold text-dark mb-3">Online Student Form Requests</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-info">
                                <tr>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Submitted By</th>
                                    <th>Campus & Student</th>
                                    <th>Message</th>
                                    <th>Attachment</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $f_sub_rs = Database::search("SELECT online_form_submission.*, user.fname, user.lname, campus.name AS campus_name 
                                                             FROM `online_form_submission` 
                                                             LEFT JOIN `user` ON online_form_submission.user_email = user.email 
                                                             LEFT JOIN `campus` ON user.campus_id = campus.id 
                                                             ORDER BY online_form_submission.submitted_date DESC");
                                while ($fsub = $f_sub_rs->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="fw-bold">
                                            <?php echo htmlspecialchars($fsub["subject"]); ?><br>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($fsub["category"]); ?></span>
                                        </td>
                                        <td class="small">
                                            <strong><?php echo htmlspecialchars($fsub["fname"] . " " . $fsub["lname"]); ?></strong><br>
                                            <span class="text-primary fw-semibold"><?php echo htmlspecialchars($fsub["campus_name"] ? $fsub["campus_name"] : "General"); ?></span><br>
                                            <span class="text-muted"><?php echo htmlspecialchars($fsub["user_email"]); ?></span>
                                        </td>
                                        <td class="small text-secondary" style="max-width: 220px;"><?php echo nl2br(htmlspecialchars($fsub["message"])); ?></td>
                                        <td>
                                            <?php if ($fsub["attachment"]): ?>
                                                <a href="<?php echo htmlspecialchars($fsub["attachment"]); ?>" target="_blank" class="btn btn-sm btn-outline-primary fw-bold"><i class="bi bi-paperclip"></i> View File</a>
                                            <?php else: ?>
                                                <span class="text-muted small">None</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted"><?php echo date("Y-m-d H:i", strtotime($fsub["submitted_date"])); ?></td>
                                        <td>
                                            <?php if ($fsub["status"] == "Reviewed"): ?>
                                                <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i> Reviewed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($fsub["status"] != "Reviewed"): ?>
                                                <button class="btn btn-sm btn-success fw-bold" onclick="reviewFormSubmission(<?php echo $fsub['id']; ?>);">
                                                    <i class="bi bi-check-lg me-1"></i> Mark Reviewed
                                                </button>
                                            <?php else: ?>
                                                <span class="text-success small fw-semibold"><i class="bi bi-check-circle-fill"></i> Done</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal: Add Campus -->
    <div class="modal fade" id="addCampusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-building-add me-2"></i> Add New Campus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Campus / University Name</label>
                        <input type="text" class="form-control" id="m_campus_name" placeholder="Ex: University of Jaffna">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Campus Abbreviation / Code</label>
                        <input type="text" class="form-control" id="m_campus_code" placeholder="Ex: UOJ">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger fw-bold" onclick="addCampus();">Save Campus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Sub Admin -->
    <div class="modal fade" id="addSubAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Add Sub-Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">First Name</label>
                            <input type="text" class="form-control" id="sa_fname" placeholder="Ex: Nuwan">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control" id="sa_lname" placeholder="Ex: Perera">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control" id="sa_email" placeholder="jaffna.admin@campushub.lk">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="sa_password" placeholder="••••••••">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input type="text" class="form-control" id="sa_mobile" placeholder="0771234567">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Assigned Campus</label>
                        <select class="form-select" id="sa_campus_id">
                            <option value="0">Select Campus</option>
                            <?php
                            $c_sel_rs = Database::search("SELECT * FROM `campus` ORDER BY name ASC");
                            while ($csel = $c_sel_rs->fetch_assoc()):
                            ?>
                                <option value="<?php echo $csel["id"]; ?>"><?php echo htmlspecialchars($csel["name"]); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning fw-bold text-dark" onclick="addSubAdmin();">Create Sub-Admin</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Event -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="addEventModalLabel"><i class="bi bi-calendar-plus me-2"></i> Create Campus Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Event Title</label>
                        <input type="text" class="form-control" id="ev_title" placeholder="Ex: Inter-University Robotics Meet 2026">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Host Campus</label>
                            <select class="form-select" id="ev_campus_id">
                                <?php
                                $c_sel_rs2 = Database::search("SELECT * FROM `campus` ORDER BY name ASC");
                                while ($csel2 = $c_sel_rs2->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $csel2["id"]; ?>"><?php echo htmlspecialchars($csel2["name"]); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Event Category</label>
                            <select class="form-select" id="ev_category">
                                <?php
                                $c_rs = Database::search("SELECT * FROM `category`");
                                while ($c = $c_rs->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $c["id"]; ?>"><?php echo htmlspecialchars($c["name"]); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Date & Time</label>
                            <input type="datetime-local" class="form-control" id="ev_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Max Participants / Capacity</label>
                            <input type="number" class="form-control" id="ev_max" value="50">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Location / Venue</label>
                        <input type="text" class="form-control" id="ev_location" placeholder="Ex: Main Auditorium, UoC">
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch bg-light p-3 rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="ev_open_to_all">
                            <label class="form-check-label fw-bold text-dark" for="ev_open_to_all">
                                Open to Students from All Campuses
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Event Description</label>
                        <textarea class="form-control" id="ev_desc" rows="3" placeholder="Enter full details..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Banner Image Upload</label>
                        <input type="file" class="form-control" id="ev_banner" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary fw-bold" onclick="addEvent();">Publish Event</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Announcement -->
    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="addAnnouncementModalLabel"><i class="bi bi-megaphone me-2"></i> Publish System Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Target Campus Scope</label>
                        <select class="form-select" id="anc_campus_id">
                            <option value="0">All Campuses System-Wide</option>
                            <?php
                            $c_sel_rs3 = Database::search("SELECT * FROM `campus` ORDER BY name ASC");
                            while ($csel3 = $c_sel_rs3->fetch_assoc()):
                            ?>
                                <option value="<?php echo $csel3["id"]; ?>"><?php echo htmlspecialchars($csel3["name"]); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Announcement Title</label>
                        <input type="text" class="form-control" id="anc_title" placeholder="Ex: Registration Deadline Extended">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Content</label>
                        <textarea class="form-control" id="anc_content" rows="4" placeholder="Enter announcement text..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark fw-bold" onclick="addAnnouncement();">Publish Announcement</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

    <script>
    function filterStudents() {
        var cid = document.getElementById("student_campus_filter").value;
        var rows = document.querySelectorAll(".student-row");
        rows.forEach(function(r) {
            if (cid == "0" || r.getAttribute("data-campus") == cid) {
                r.style.display = "";
            } else {
                r.style.display = "none";
            }
        });
    }

    function filterEvents() {
        var cid = document.getElementById("event_campus_filter").value;
        var rows = document.querySelectorAll(".event-row");
        rows.forEach(function(r) {
            if (cid == "0" || r.getAttribute("data-campus") == cid) {
                r.style.display = "";
            } else {
                r.style.display = "none";
            }
        });
    }

    function filterAnnouncements() {
        var cid = document.getElementById("ann_campus_filter").value;
        var rows = document.querySelectorAll(".ann-row");
        rows.forEach(function(r) {
            if (cid == "0" || r.getAttribute("data-campus") == cid) {
                r.style.display = "";
            } else {
                r.style.display = "none";
            }
        });
    }

    function addCampus() {
        var name = document.getElementById("m_campus_name").value;
        var code = document.getElementById("m_campus_code").value;

        var form = new FormData();
        form.append("action", "add");
        form.append("name", name);
        form.append("code", code);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                if (request.responseText == "success") {
                    alert("Campus added successfully!");
                    window.location.reload();
                } else {
                    alert(request.responseText);
                }
            }
        };
        request.open("POST", "process/manageCampusProcess.php", true);
        request.send(form);
    }

    function deleteCampus(id) {
        if (!confirm("Are you sure you want to delete this campus? This will cascade remove related campus records.")) {
            return;
        }
        var form = new FormData();
        form.append("action", "delete");
        form.append("id", id);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                if (request.responseText == "success") {
                    alert("Campus deleted!");
                    window.location.reload();
                } else {
                    alert(request.responseText);
                }
            }
        };
        request.open("POST", "process/manageCampusProcess.php", true);
        request.send(form);
    }

    function addSubAdmin() {
        var fname = document.getElementById("sa_fname").value;
        var lname = document.getElementById("sa_lname").value;
        var email = document.getElementById("sa_email").value;
        var password = document.getElementById("sa_password").value;
        var mobile = document.getElementById("sa_mobile").value;
        var campusId = document.getElementById("sa_campus_id").value;

        var form = new FormData();
        form.append("action", "add");
        form.append("fname", fname);
        form.append("lname", lname);
        form.append("email", email);
        form.append("password", password);
        form.append("mobile", mobile);
        form.append("campus_id", campusId);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                if (request.responseText == "success") {
                    alert("Sub-Admin Account Created Successfully!");
                    window.location.reload();
                } else {
                    alert(request.responseText);
                }
            }
        };
        request.open("POST", "process/manageSubAdminProcess.php", true);
        request.send(form);
    }

    function deleteSubAdmin(email) {
        if (!confirm("Are you sure you want to delete this Sub-Admin account?")) {
            return;
        }
        var form = new FormData();
        form.append("action", "delete");
        form.append("email", email);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                if (request.responseText == "success") {
                    alert("Sub-Admin account removed!");
                    window.location.reload();
                } else {
                    alert(request.responseText);
                }
            }
        };
        request.open("POST", "process/manageSubAdminProcess.php", true);
        request.send(form);
    }

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
                if (request.responseText == "success") {
                    alert("Student status updated!");
                    window.location.reload();
                } else {
                    alert(request.responseText);
                }
            }
        };
        request.open("POST", "process/verifyStudentProcess.php", true);
        request.send(form);
    }

    function deleteStudent(email) {
        if (!confirm("Are you sure you want to delete this student record?")) {
            return;
        }
        deleteItem("student", email);
    }

    function deleteItem(type, id) {
        if (!confirm("Are you sure you want to delete this " + type + "?")) {
            return;
        }
        var form = new FormData();
        form.append("type", type);
        form.append("id", id);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                if (request.responseText == "success") {
                    alert("Item deleted successfully!");
                    window.location.reload();
                } else {
                    alert(request.responseText);
                }
            }
        };
        request.open("POST", "process/deleteItemProcess.php", true);
        request.send(form);
    }

    function addEvent() {
        var title = document.getElementById("ev_title");
        var desc = document.getElementById("ev_desc");
        var category = document.getElementById("ev_category");
        var campusId = document.getElementById("ev_campus_id");
        var date = document.getElementById("ev_date");
        var location = document.getElementById("ev_location");
        var maxPart = document.getElementById("ev_max");
        var openToAll = document.getElementById("ev_open_to_all");
        var banner = document.getElementById("ev_banner");

        var form = new FormData();
        form.append("t", title.value);
        form.append("d", desc.value);
        form.append("c", category.value);
        form.append("campus_id", campusId ? campusId.value : "1");
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
                    alert("New Event Created Successfully!");
                    window.location.reload();
                } else {
                    alert(response);
                }
            }
        };
        request.open("POST", "process/addEventProcess.php", true);
        request.send(form);
    }

    function addAnnouncement() {
        var title = document.getElementById("anc_title");
        var content = document.getElementById("anc_content");
        var campusId = document.getElementById("anc_campus_id");

        var form = new FormData();
        form.append("t", title.value);
        form.append("c", content.value);
        form.append("campus_id", campusId ? campusId.value : "0");

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.status == 200 && request.readyState == 4) {
                var response = request.responseText;
                if (response == "success") {
                    alert("Announcement Published Successfully!");
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

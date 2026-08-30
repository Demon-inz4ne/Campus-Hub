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
    <title>Announcements & Notifications | CampusHub</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div class="card border-0 shadow-sm p-4 bg-warning text-dark rounded-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2"><i class="bi bi-megaphone-fill me-2"></i> Campus Announcements</h2>
                    <p class="mb-0 text-dark-50">Official news, updates, deadline notices, and announcements from CampusHub management.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="xml_validator.php" class="btn btn-dark fw-bold"><i class="bi bi-filetype-xml me-1"></i> XML Feed Validator</a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-4">
                    <h4 class="fw-bold text-dark mb-4 border-bottom pb-2">Latest Published Notices</h4>

                    <div class="d-flex flex-column gap-3">
                        <?php
                        $user_campus_id = (isset($_SESSION["u"]) && !empty($_SESSION["u"]["campus_id"])) ? $_SESSION["u"]["campus_id"] : 0;
                        $ann_query = "SELECT announcement.*, campus.name AS campus_name FROM `announcement` LEFT JOIN `campus` ON announcement.campus_id=campus.id ";
                        if (isset($_SESSION["u"]) && $_SESSION["u"]["user_type_id"] == 3 && $user_campus_id > 0) {
                            $ann_query .= "WHERE (announcement.campus_id='" . $user_campus_id . "' OR announcement.campus_id IS NULL) ";
                        }
                        $ann_query .= "ORDER BY date_published DESC";

                        $ann_rs = Database::search($ann_query);
                        while ($ann = $ann_rs->fetch_assoc()):
                        ?>
                            <div class="p-3 border rounded-3 bg-light hover-shadow">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h5 class="fw-bold text-primary mb-0 d-inline me-2"><?php echo htmlspecialchars($ann["title"]); ?></h5>
                                        <?php if (!empty($ann["campus_name"])): ?>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($ann["campus_name"]); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-info text-dark">All Campuses</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i> <?php echo date("F d, Y h:i A", strtotime($ann["date_published"])); ?></span>
                                </div>
                                <p class="text-secondary mb-2"><?php echo nl2br(htmlspecialchars($ann["content"])); ?></p>
                                <div class="small text-muted border-top pt-2">
                                    <span>Published by: <strong><?php echo htmlspecialchars($ann["author_email"]); ?></strong></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-code-square text-primary me-2"></i> XML Integration Notice</h5>
                    <p class="small text-muted mb-3">
                        CampusHub announcements are also exported in valid XML syntax validated against <code>announcements.dtd</code> schema.
                    </p>
                    <a href="xml_validator.php" class="btn btn-outline-primary btn-sm fw-bold w-100 mb-2"><i class="bi bi-check-circle me-1"></i> Check XML DTD Validator</a>
                    <a href="announcements.xml" target="_blank" class="btn btn-outline-secondary btn-sm fw-bold w-100"><i class="bi bi-file-earmark-code me-1"></i> View Raw XML File</a>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

</body>
</html>

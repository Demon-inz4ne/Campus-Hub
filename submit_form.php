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
    <title>Submit Online Form | CampusHub</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <h3 class="fw-bold text-dark mb-1"><i class="bi bi-file-earmark-text text-primary me-2"></i> Submit Online Student Form</h3>
                    <p class="text-muted small mb-4">Submit requests for new student clubs, propose campus activities, or send official inquiries with file attachments.</p>

                    <div class="d-none mb-3" id="form_msg"></div>

                    <form id="student_form" onsubmit="return false;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subject / Title</label>
                            <input type="text" class="form-control" id="form_subject" placeholder="Ex: Proposal for Campus Robotics Club">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Form Category</label>
                            <select class="form-select" id="form_category">
                                <option value="Club Application">Student Club / Society Application</option>
                                <option value="Event Proposal">Event / Workshop Proposal</option>
                                <option value="Activity Update">Sports / Activity Update Submission</option>
                                <option value="General Query">General Inquiry / Feedback</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Detailed Message & Description</label>
                            <textarea class="form-control" id="form_message" rows="5" placeholder="Provide full details, expected participants, budget requirements..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Attach Document / File (Optional)</label>
                            <input type="file" class="form-control" id="form_attachment">
                            <small class="text-muted">Supports PDF, DOCX, PNG, JPG files.</small>
                        </div>

                        <div class="d-grid">
                            <?php if (isset($_SESSION["u"])): ?>
                                <button type="button" class="btn btn-primary btn-lg fw-bold" onclick="submitOnlineForm();">
                                    <i class="bi bi-send me-1"></i> Submit Form Online
                                </button>
                            <?php else: ?>
                                <a href="index.php" class="btn btn-warning btn-lg fw-bold text-dark">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Please Sign In to Submit Form
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

</body>
</html>

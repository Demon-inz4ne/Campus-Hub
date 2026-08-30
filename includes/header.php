<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold brand-title fs-4" href="home.php">
            <img src="resources/logo/logo.jpg" alt="CampusHubLogo" width="34" height="34" class="rounded-circle me-2 border border-2 border-white shadow-sm object-fit-cover">
            <span>CampusHub</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#campusNavbar" aria-controls="campusNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="campusNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                <li class="nav-item">
                    <a class="nav-link text-white" href="home.php"><i class="bi bi-house-door me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="events.php"><i class="bi bi-calendar-event me-1"></i> Events & Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="gallery.php"><i class="bi bi-film me-1"></i> Media Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="announcements.php"><i class="bi bi-megaphone me-1"></i> Announcements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="submit_form.php"><i class="bi bi-file-earmark-text me-1"></i> Submit Form</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="xml_validator.php"><i class="bi bi-filetype-xml me-1"></i> XML Feed</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION["u"])): 
                    $user = $_SESSION["u"];
                ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="fw-bold">Hi, <?php echo htmlspecialchars($user["fname"]); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i> My Profile</a></li>
                            <?php if ($user["user_type_id"] == 1): ?>
                                <li><a class="dropdown-item text-primary fw-bold" href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Main Admin Portal</a></li>
                            <?php elseif ($user["user_type_id"] == 2): ?>
                                <li><a class="dropdown-item text-warning fw-bold text-dark" href="sub_admin_dashboard.php"><i class="bi bi-shield-check me-2"></i> Sub-Admin Portal</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="#" onclick="signOut();"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="index.php" class="btn btn-warning fw-bold text-dark me-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In / Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

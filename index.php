<?php
include "includes/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = "";
$password = "";
if (isset($_COOKIE["email"])) {
    $email = $_COOKIE["email"];
}
if (isset($_COOKIE["password"])) {
    $password = $_COOKIE["password"];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to CampusHub | Portal Login</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <div class="container my-auto py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-5 bg-primary text-white d-flex flex-column justify-content-center p-4 text-center">
                            <img src="resources/logo/logo.jpg" alt="CampusHub Logo" class="rounded-circle shadow mx-auto mb-3 border border-3 border-white object-fit-cover" style="width: 105px; height: 105px;">
                            <h2 class="fw-bold mb-2">CampusHub</h2>
                            <p class="small text-white-50">Student Services & Event Coordination Platform across Educational Institutions.</p>
                            <div class="mt-3">
                                <span class="badge bg-warning text-dark px-3 py-2 fw-bold">Clubs • Events • Sports</span>
                            </div>
                        </div>

                        <div class="col-md-7 p-4 bg-white">

                            <div id="signInBox">
                                <h3 class="fw-bold text-dark mb-1">Sign In</h3>
                                <p class="text-muted small mb-4">Enter your student or admin credentials to continue.</p>

                                <div class="d-none mb-3" id="msgdiv1">
                                    <div class="alert alert-danger py-2 small" role="alert" id="msg1"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Email Address</label>
                                    <input type="email" class="form-control" id="email2" value="<?php echo htmlspecialchars($email); ?>" placeholder="e.g. john@gmail.com">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Password</label>
                                    <input type="password" class="form-control" id="password2" value="<?php echo htmlspecialchars($password); ?>" placeholder="Enter password">
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label small" for="rememberMe">Remember Me</label>
                                    </div>
                                    <a href="javascript:void(0);" class="small text-decoration-none text-primary fw-semibold" onclick="openForgotPasswordModal();">Forgot Password?</a>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg fw-bold fs-6" onclick="signIn();">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                                    </button>
                                    <button class="btn btn-outline-secondary fw-semibold" onclick="changeView();">
                                        New Student? Create Account
                                    </button>
                                </div>
                            </div>

                            <div class="d-none" id="signUpBox">
                                <h3 class="fw-bold text-dark mb-1">Create Student Account</h3>
                                <p class="text-muted small mb-3">Register online for events and campus activities.</p>

                                <div class="d-none mb-3" id="msgdiv">
                                    <div class="alert alert-danger py-2 small" role="alert" id="msg"></div>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small">First Name</label>
                                        <input type="text" class="form-control" id="fname" placeholder="Ex: John">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small">Last Name</label>
                                        <input type="text" class="form-control" id="lname" placeholder="Ex: Doe">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Email Address</label>
                                    <input type="email" class="form-control" id="email" placeholder="john@gmail.com">
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="••••••••">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small">Mobile Number</label>
                                        <input type="text" class="form-control" id="mobile" placeholder="0771234567">
                                    </div>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small">University / Campus</label>
                                        <select class="form-select" id="campus_id">
                                            <option value="0">Select Campus</option>
                                            <?php
                                            $campus_rs = Database::search("SELECT * FROM `campus` ORDER BY name ASC");
                                            while ($c = $campus_rs->fetch_assoc()):
                                            ?>
                                                <option value="<?php echo $c["id"]; ?>"><?php echo htmlspecialchars($c["name"]); ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small">Student Number</label>
                                        <input type="text" class="form-control" id="student_no" placeholder="Ex: UOC-2026-104">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-success fw-bold fs-6" onclick="signUp();">
                                        <i class="bi bi-person-plus me-1"></i> Register Student Account
                                    </button>
                                    <button class="btn btn-outline-secondary fw-semibold" onclick="changeView();">
                                        Already Registered? Sign In
                                    </button>
                                </div>
                            </div>

                            <!-- Modal: Verify Email Code -->
                            <div class="modal fade" id="verifyEmailModal" tabindex="-1" aria-labelledby="verifyEmailModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title fw-bold" id="verifyEmailModalLabel"><i class="bi bi-envelope-check me-2"></i> Submit Verification Code</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info py-2 small" role="alert">
                                                <i class="bi bi-info-circle me-1"></i> We have sent a 6-digit verification code to your email address. Please check your <strong>Email Inbox</strong> (and Spam folder) and submit the code below.
                                            </div>
                                            <div class="d-none mb-3" id="vmsgdiv">
                                                <div class="alert alert-danger py-2 small" role="alert" id="vmsg"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Registered Email Address</label>
                                                <input type="email" class="form-control" id="v_email" placeholder="student@email.com">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Email Verification Code (From Inbox)</label>
                                                <input type="text" class="form-control text-center fw-bold fs-5" id="v_code">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary fw-bold" onclick="verifyEmailCode();">Submit Code</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal: Forgot Password & Reset -->
                            <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title fw-bold" id="forgotPasswordModalLabel"><i class="bi bi-shield-lock me-2 text-warning"></i> Reset Password</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info py-2 small" role="alert">
                                                Enter your registered email address to receive a password reset verification code.
                                            </div>
                                            <div class="d-none mb-3" id="fpMsgDiv">
                                                <div class="alert alert-danger py-2 small" role="alert" id="fpMsg"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Registered Email Address</label>
                                                <div class="input-group">
                                                    <input type="email" class="form-control" id="fp_email" placeholder="e.g. student@gmail.com">
                                                    <button class="btn btn-outline-primary fw-semibold" type="button" id="btnSendResetCode" onclick="forgotPasswordSendCode();">
                                                        <i class="bi bi-send me-1"></i> Send Code
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="border-top pt-3 mt-3">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small">Verification Code (From Email)</label>
                                                    <input type="text" class="form-control text-center fw-bold fs-6" id="fp_code">
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold small">New Password</label>
                                                        <input type="password" class="form-control" id="fp_new_pw" placeholder="8 characters">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold small">Re-type Password</label>
                                                        <input type="password" class="form-control" id="fp_re_pw" placeholder="Confirm password">
                                                    </div>
                                                </div>
                                                
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-primary fw-bold" onclick="resetPasswordSubmit();">
                                                        <i class="bi bi-check-circle me-1"></i> Reset Password
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light py-2">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
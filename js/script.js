function changeView() {
    var signInBox = document.getElementById("signInBox");
    var signUpBox = document.getElementById("signUpBox");
    if (signInBox && signUpBox) {
        signInBox.classList.toggle("d-none");
        signUpBox.classList.toggle("d-none");
    }
}

function signUp() {
    var fname = document.getElementById("fname");
    var lname = document.getElementById("lname");
    var email = document.getElementById("email");
    var password = document.getElementById("password");
    var mobile = document.getElementById("mobile");
    var campusId = document.getElementById("campus_id");
    var studentNo = document.getElementById("student_no");

    var form = new FormData();
    form.append("f", fname ? fname.value : "");
    form.append("l", lname ? lname.value : "");
    form.append("e", email ? email.value : "");
    form.append("p", password ? password.value : "");
    form.append("m", mobile ? mobile.value : "");
    form.append("campus_id", campusId ? campusId.value : "0");
    form.append("student_no", studentNo ? studentNo.value : "");

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText;
            var msg = document.getElementById("msg");
            var msgdiv = document.getElementById("msgdiv");
            if (response.startsWith("success_sent:")) {
                var parts = response.split(":");
                var registeredEmail = parts[1];
                var campusName = parts[2];
                msg.innerHTML = "<strong>Registration Submitted!</strong><br>We have sent a verification code to <strong>" + registeredEmail + "</strong>.<br>Please check your email inbox and submit the code.";
                msg.className = "alert alert-success";
                msgdiv.className = "d-block";

                var vEmailInput = document.getElementById("v_email");
                if (vEmailInput) {
                    vEmailInput.value = registeredEmail;
                }
                
                // Show Verification Modal
                var modalEl = document.getElementById("verifyEmailModal");
                if (modalEl) {
                    var vModal = new bootstrap.Modal(modalEl);
                    vModal.show();
                }
            } else {
                msg.innerHTML = response;
                msg.className = "alert alert-danger";
                msgdiv.className = "d-block";
            }
        }
    };
    request.open("POST", "process/signUpProcess.php", true);
    request.send(form);
}

function verifyEmailCode() {
    var email = document.getElementById("v_email");
    var code = document.getElementById("v_code");
    var vmsg = document.getElementById("vmsg");
    var vmsgdiv = document.getElementById("vmsgdiv");

    var form = new FormData();
    form.append("e", email ? email.value : "");
    form.append("c", code ? code.value : "");

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText;
            if (response == "success") {
                vmsg.innerHTML = "<strong>Email Verified Successfully!</strong><br>Your account registration is now waiting for approval by your campus Sub-Admin.";
                vmsg.className = "alert alert-success";
                vmsgdiv.className = "d-block";
                setTimeout(function(){
                    var modalEl = document.getElementById("verifyEmailModal");
                    var modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    changeView();
                }, 3000);
            } else {
                vmsg.innerHTML = response;
                vmsg.className = "alert alert-danger";
                vmsgdiv.className = "d-block";
            }
        }
    };
    request.open("POST", "process/verifyEmailCodeProcess.php", true);
    request.send(form);
}

function signIn() {
    var email = document.getElementById("email2");
    var password = document.getElementById("password2");
    var rememberMe = document.getElementById("rememberMe");

    var form = new FormData();
    form.append("e", email ? email.value : "");
    form.append("p", password ? password.value : "");
    form.append("r", rememberMe ? rememberMe.checked : false);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText;
            var msg1 = document.getElementById("msg1");
            var msgdiv1 = document.getElementById("msgdiv1");
            if (response == "main_admin") {
                window.location = "admin_dashboard.php";
            } else if (response == "sub_admin") {
                window.location = "sub_admin_dashboard.php";
            } else if (response == "student" || response == "success") {
                window.location = "home.php";
            } else if (response.startsWith("pending:")) {
                var txt = response.substring(8);
                msg1.innerHTML = "<strong><i class='bi bi-hourglass-split me-1'></i> Pending Approval:</strong><br>" + txt;
                msg1.className = "alert alert-warning border-warning text-dark";
                msgdiv1.className = "d-block";
            } else if (response.startsWith("rejected:")) {
                var txt = response.substring(9);
                msg1.innerHTML = "<strong><i class='bi bi-x-circle me-1'></i> Registration Rejected:</strong><br>" + txt;
                msg1.className = "alert alert-danger";
                msgdiv1.className = "d-block";
            } else {
                msg1.innerHTML = response;
                msg1.className = "alert alert-danger";
                msgdiv1.className = "d-block";
            }
        }
    };
    request.open("POST", "process/signInProcess.php", true);
    request.send(form);
}

function signOut() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            if (request.responseText == "success") {
                window.location = "index.php";
            }
        }
    };
    request.open("GET", "process/signOutProcess.php", true);
    request.send();
}

function basicSearch(page) {
    var searchTxt = document.getElementById("basic_search_txt");
    var selectCat = document.getElementById("basic_search_select");

    var form = new FormData();
    form.append("t", searchTxt ? searchTxt.value : "");
    form.append("cat", selectCat ? selectCat.value : "0");
    form.append("page", page);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var view = document.getElementById("basicSearchResult");
            if (view) {
                view.innerHTML = request.responseText;
            }
        }
    };
    request.open("POST", "process/basicSearchProcess.php", true);
    request.send(form);
}

function registerEvent(eventId) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText.trim();
            if (response == "success") {
                var btn = document.getElementById("reg_btn_" + eventId);
                if (btn) {
                    btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Registered';
                    btn.className = "btn btn-success btn-sm flex-fill fw-bold";
                    btn.onclick = function () {
                        alert("You are already registered for this event! You can manage your registrations from your Profile.");
                    };
                }
                alert("Successfully registered for this event!");
                window.location.reload();
            } else if (response == "login_required") {
                alert("Please login to your account to register for events.");
                window.location = "index.php";
            } else {
                alert(response);
            }
        }
    };
    request.open("GET", "process/registerEventProcess.php?id=" + eventId, true);
    request.send();
}

function previewProfileImage(input) {
    if (input && input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var imgPreview = document.getElementById("profile_img_preview");
            if (imgPreview) {
                imgPreview.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeProfileImage() {
    if (!confirm("Are you sure you want to remove your profile photo and reset to default?")) {
        return;
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText;
            if (response == "success") {
                alert("Profile picture removed successfully!");
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };
    request.open("POST", "process/removeProfileImgProcess.php", true);
    request.send();
}

function updateProfile() {
    var fname = document.getElementById("pf_fname");
    var lname = document.getElementById("pf_lname");
    var mobile = document.getElementById("pf_mobile");
    var img = document.getElementById("pf_img_uploader");

    var form = new FormData();
    form.append("f", fname ? fname.value : "");
    form.append("l", lname ? lname.value : "");
    form.append("m", mobile ? mobile.value : "");
    if (img && img.files.length > 0) {
        form.append("image", img.files[0]);
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText;
            if (response == "success") {
                alert("Profile updated successfully!");
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };
    request.open("POST", "process/updateProfileProcess.php", true);
    request.send(form);
}

function submitOnlineForm() {
    var subject = document.getElementById("form_subject");
    var category = document.getElementById("form_category");
    var message = document.getElementById("form_message");
    var attachment = document.getElementById("form_attachment");

    var form = new FormData();
    form.append("s", subject.value);
    form.append("c", category.value);
    form.append("m", message.value);
    if (attachment && attachment.files.length > 0) {
        form.append("file", attachment.files[0]);
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText;
            var msg = document.getElementById("form_msg");
            if (response == "success") {
                msg.innerHTML = "Form submitted successfully!";
                msg.className = "alert alert-success d-block";
                document.getElementById("student_form").reset();
            } else {
                msg.innerHTML = response;
                msg.className = "alert alert-danger d-block";
            }
        }
    };
    request.open("POST", "process/submitFormProcess.php", true);
    request.send(form);
}

function uploadMedia() {
    var title = document.getElementById("media_title");
    var desc = document.getElementById("media_desc");
    var type = document.getElementById("media_type");
    var file = document.getElementById("media_file");

    var form = new FormData();
    form.append("t", title.value);
    form.append("d", desc.value);
    form.append("type", type.value);
    if (file && file.files.length > 0) {
        form.append("file", file.files[0]);
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.status == 200 && request.readyState == 4) {
            var response = request.responseText;
            if (response == "success") {
                alert("Media uploaded successfully!");
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };
    request.open("POST", "process/uploadMediaProcess.php", true);
    request.send(form);
}

function addEvent() {
    var title = document.getElementById("ev_title");
    var desc = document.getElementById("ev_desc");
    var category = document.getElementById("ev_category");
    var date = document.getElementById("ev_date");
    var location = document.getElementById("ev_location");
    var maxPart = document.getElementById("ev_max");
    var banner = document.getElementById("ev_banner");

    var form = new FormData();
    form.append("t", title.value);
    form.append("d", desc.value);
    form.append("c", category.value);
    form.append("date", date.value);
    form.append("loc", location.value);
    form.append("max", maxPart.value);
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

    var form = new FormData();
    form.append("t", title.value);
    form.append("c", content.value);

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

function openForgotPasswordModal() {
    var emailInput = document.getElementById("email2");
    var fpEmailInput = document.getElementById("fp_email");
    if (emailInput && fpEmailInput && emailInput.value.trim() !== "") {
        fpEmailInput.value = emailInput.value.trim();
    }
    
    var fpMsgDiv = document.getElementById("fpMsgDiv");
    if (fpMsgDiv) {
        fpMsgDiv.className = "d-none mb-3";
    }

    var fpModalEl = document.getElementById("forgotPasswordModal");
    if (fpModalEl) {
        var fpModal = bootstrap.Modal.getOrCreateInstance(fpModalEl);
        fpModal.show();
    }
}

function forgotPasswordSendCode() {
    var email = document.getElementById("fp_email");
    var fpMsg = document.getElementById("fpMsg");
    var fpMsgDiv = document.getElementById("fpMsgDiv");
    var btnSend = document.getElementById("btnSendResetCode");

    if (!email || email.value.trim() === "") {
        fpMsg.innerHTML = "Please enter your registered Email Address.";
        fpMsg.className = "alert alert-danger py-2 small";
        fpMsgDiv.className = "d-block mb-3";
        return;
    }

    if (btnSend) {
        btnSend.disabled = true;
        btnSend.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending...';
    }

    var form = new FormData();
    form.append("e", email.value.trim());

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            if (btnSend) {
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="bi bi-send me-1"></i> Send Code';
            }

            if (request.status == 200) {
                var response = request.responseText.trim();
                if (response == "success") {
                    fpMsg.innerHTML = "<strong>Verification Code Sent!</strong><br>We sent a reset code to <strong>" + email.value.trim() + "</strong>. Please check your inbox and spam folder.";
                    fpMsg.className = "alert alert-success py-2 small";
                    fpMsgDiv.className = "d-block mb-3";
                } else {
                    fpMsg.innerHTML = response;
                    fpMsg.className = "alert alert-danger py-2 small";
                    fpMsgDiv.className = "d-block mb-3";
                }
            } else {
                fpMsg.innerHTML = "An error occurred while sending the email. Please try again.";
                fpMsg.className = "alert alert-danger py-2 small";
                fpMsgDiv.className = "d-block mb-3";
            }
        }
    };
    request.open("POST", "process/forgotPasswordProcess.php", true);
    request.send(form);
}

function resetPasswordSubmit() {
    var email = document.getElementById("fp_email");
    var code = document.getElementById("fp_code");
    var newPw = document.getElementById("fp_new_pw");
    var rePw = document.getElementById("fp_re_pw");
    var fpMsg = document.getElementById("fpMsg");
    var fpMsgDiv = document.getElementById("fpMsgDiv");

    var form = new FormData();
    form.append("e", email ? email.value.trim() : "");
    form.append("c", code ? code.value.trim() : "");
    form.append("np", newPw ? newPw.value : "");
    form.append("rnp", rePw ? rePw.value : "");

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText.trim();
            if (response == "success") {
                fpMsg.innerHTML = "<strong>Password Reset Successful!</strong><br>You can now sign in with your new password.";
                fpMsg.className = "alert alert-success py-2 small";
                fpMsgDiv.className = "d-block mb-3";

                // Auto fill email into sign in field
                var email2 = document.getElementById("email2");
                var password2 = document.getElementById("password2");
                if (email2 && email) {
                    email2.value = email.value.trim();
                }
                if (password2 && newPw) {
                    password2.value = newPw.value;
                }

                setTimeout(function () {
                    var fpModalEl = document.getElementById("forgotPasswordModal");
                    if (fpModalEl) {
                        var modalInstance = bootstrap.Modal.getInstance(fpModalEl);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    }
                }, 2500);
            } else {
                fpMsg.innerHTML = response;
                fpMsg.className = "alert alert-danger py-2 small";
                fpMsgDiv.className = "d-block mb-3";
            }
        }
    };
    request.open("POST", "process/resetPasswordProcess.php", true);
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




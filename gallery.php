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
    <title>Media Gallery | CampusHub Multimedia</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <?php include "includes/header.php"; ?>

    <div class="container my-4">
        <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2"><i class="bi bi-film me-2 text-warning"></i> Campus Multimedia Gallery</h2>
                    <p class="mb-0 text-white-50">Explore event photos, video highlights, and campus audio podcasts. Demonstrating HTML5 Multimedia Integration.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <?php if (isset($_SESSION["u"])): ?>
                        <button class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
                            <i class="bi bi-cloud-upload me-1"></i> Upload Media
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills mb-4 justify-content-center gap-2" id="galleryTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="all-tab" data-bs-toggle="pill" data-bs-target="#all-media" type="button" role="tab"><i class="bi bi-collection me-1"></i> All Content</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="image-tab" data-bs-toggle="pill" data-bs-target="#image-media" type="button" role="tab"><i class="bi bi-image me-1"></i> Photographs</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="video-tab" data-bs-toggle="pill" data-bs-target="#video-media" type="button" role="tab"><i class="bi bi-play-btn me-1"></i> Video Highlights</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="audio-tab" data-bs-toggle="pill" data-bs-target="#audio-media" type="button" role="tab"><i class="bi bi-music-note-beamer me-1"></i> Audio Podcasts</button>
            </li>
        </ul>

        <div class="tab-content" id="galleryTabsContent">
            
            <div class="tab-pane fade show active" id="all-media" role="tabpanel">
                <div class="row g-4">
                    <?php
                    $media_rs = Database::search("SELECT * FROM `media_gallery` INNER JOIN `user` ON `media_gallery`.`uploaded_by`=`user`.`email` ORDER BY upload_date DESC");
                    while ($m = $media_rs->fetch_assoc()):
                    ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden bg-white">
                                <div class="bg-dark text-white">
                                    <?php if ($m["media_type"] == "video"): ?>
                                        <div class="ratio ratio-16x9">
                                            <video controls class="w-100 rounded">
                                                <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    <?php elseif ($m["media_type"] == "audio"): ?>
                                        <div class="p-4 text-center">
                                            <i class="bi bi-broadcast display-3 text-warning mb-3"></i>
                                            <audio controls class="w-100">
                                                <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="audio/mpeg">
                                                <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="audio/wav">
                                                <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="audio/ogg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center bg-secondary overflow-hidden" style="height: 220px;">
                                            <img src="<?php echo htmlspecialchars($m["file_path"]); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($m["title"]); ?>" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-4 text-center\'><i class=\'bi bi-image display-3 text-warning\'></i></div>';" />
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body p-3">
                                    <span class="badge bg-primary mb-2 text-uppercase"><?php echo $m["media_type"]; ?></span>
                                    <h6 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($m["title"]); ?></h6>
                                    <p class="small text-muted mb-2"><?php echo htmlspecialchars($m["description"]); ?></p>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                        <small class="text-muted"><i class="bi bi-person me-1"></i> <?php echo htmlspecialchars($m["fname"] . " " . $m["lname"]); ?></small>
                                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> <?php echo date("Y-m-d", strtotime($m["upload_date"])); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="image-media" role="tabpanel">
                <div class="row g-4">
                    <?php
                    $img_rs = Database::search("SELECT * FROM `media_gallery` WHERE `media_type`='image' ORDER BY upload_date DESC");
                    if ($img_rs->num_rows == 0):
                    ?>
                        <div class="col-12 text-center py-5"><i class="bi bi-images display-1 text-muted"></i><p class="mt-2 text-muted fw-bold">No photographs uploaded yet.</p></div>
                    <?php else: while ($m = $img_rs->fetch_assoc()): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm bg-white overflow-hidden rounded-3 h-100">
                                <div class="bg-secondary text-center overflow-hidden" style="height: 220px;">
                                    <img src="<?php echo htmlspecialchars($m["file_path"]); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($m["title"]); ?>" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-4 text-center\'><i class=\'bi bi-image display-3 text-warning\'></i></div>';" />
                                </div>
                                <div class="p-3">
                                    <h6 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($m["title"]); ?></h6>
                                    <p class="small text-muted mb-2"><?php echo htmlspecialchars($m["description"]); ?></p>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                        <small class="text-muted"><i class="bi bi-person me-1"></i> <?php echo htmlspecialchars($m["uploaded_by"]); ?></small>
                                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> <?php echo date("Y-m-d", strtotime($m["upload_date"])); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="video-media" role="tabpanel">
                <div class="row g-4">
                    <?php
                    $vid_rs = Database::search("SELECT * FROM `media_gallery` WHERE `media_type`='video' ORDER BY upload_date DESC");
                    if ($vid_rs->num_rows == 0):
                    ?>
                        <div class="col-12 text-center py-5"><i class="bi bi-film display-1 text-muted"></i><p class="mt-2 text-muted fw-bold">No video highlights uploaded yet.</p></div>
                    <?php else: while ($m = $vid_rs->fetch_assoc()): ?>
                        <div class="col-12 col-md-6">
                            <div class="card border-0 shadow-sm p-3 bg-white">
                                <h6 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($m["title"]); ?></h6>
                                <div class="ratio ratio-16x9 mb-2">
                                    <video controls class="w-100 rounded">
                                        <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="video/mp4">
                                    </video>
                                </div>
                                <p class="small text-muted mb-0"><?php echo htmlspecialchars($m["description"]); ?></p>
                            </div>
                        </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="audio-media" role="tabpanel">
                <div class="row g-4">
                    <?php
                    $aud_rs = Database::search("SELECT * FROM `media_gallery` WHERE `media_type`='audio' ORDER BY upload_date DESC");
                    if ($aud_rs->num_rows == 0):
                    ?>
                        <div class="col-12 text-center py-5"><i class="bi bi-mic display-1 text-muted"></i><p class="mt-2 text-muted fw-bold">No audio podcasts uploaded yet.</p></div>
                    <?php else: while ($m = $aud_rs->fetch_assoc()): ?>
                        <div class="col-12 col-md-6">
                            <div class="card border-0 shadow-sm p-4 bg-white text-center">
                                <i class="bi bi-music-note-beamer display-2 text-primary mb-3"></i>
                                <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($m["title"]); ?></h5>
                                <p class="small text-muted mb-3"><?php echo htmlspecialchars($m["description"]); ?></p>
                                <audio controls class="w-100">
                                    <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="audio/mpeg">
                                    <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="audio/wav">
                                    <source src="<?php echo htmlspecialchars($m["file_path"]); ?>" type="audio/ogg">
                                </audio>
                            </div>
                        </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-labelledby="uploadMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="uploadMediaModalLabel"><i class="bi bi-cloud-upload me-2"></i> Upload Multimedia Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Media Title</label>
                        <input type="text" class="form-control" id="media_title" placeholder="Ex: Hackathon Highlights Photo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="media_desc" rows="2" placeholder="Short description..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Media Type</label>
                        <select class="form-select" id="media_type">
                            <option value="image">Photograph (Image)</option>
                            <option value="video">Video Highlight (.mp4)</option>
                            <option value="audio">Audio Podcast (.mp3)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select File</label>
                        <input type="file" class="form-control" id="media_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary fw-bold" onclick="uploadMedia();">Upload File</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

</body>
</html>

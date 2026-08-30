<?php
include "includes/connection.php";

$xmlFile = "announcements.xml";
$isValid = false;
$errorMessage = "";
$announcements = array();

if (file_exists($xmlFile)) {
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->load($xmlFile, LIBXML_DTDLOAD);

    if ($dom->validate()) {
        $isValid = true;
        $xml = simplexml_load_file($xmlFile);
        foreach ($xml->announcement as $item) {
            $announcements[] = array(
                "id" => (string)$item->id,
                "title" => (string)$item->title,
                "content" => (string)$item->content,
                "date" => (string)$item->date,
                "author" => (string)$item->author
            );
        }
    } else {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            $errorMessage .= $error->message . " ";
        }
        libxml_clear_errors();
    }
} else {
    $errorMessage = "announcements.xml file not found!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XML Validator & Feed | CampusHub</title>
    <link rel="icon" href="resources/logo/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <?php include "includes/header.php"; ?>
    <div class="container my-4">
        <div class="card border-0 shadow-sm p-4">
            <h3 class="fw-bold text-primary mb-3"><i class="bi bi-filetype-xml"></i> XML Technology & DTD Validation</h3>
            <p class="text-muted">This page demonstrates the XML Syntax and DTD Schema Validation requirement specified in the project scope.</p>

            <div class="alert <?php echo $isValid ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <h5 class="alert-heading fw-bold">
                    <?php if ($isValid): ?>
                        <i class="bi bi-check-circle-fill"></i> XML Validation Status: VALID
                    <?php else: ?>
                        <i class="bi bi-exclamation-triangle-fill"></i> XML Validation Status: INVALID
                    <?php endif; ?>
                </h5>
                <p class="mb-0">
                    <?php 
                    if ($isValid) {
                        echo "The XML document <code>announcements.xml</code> successfully passed validation against <code>announcements.dtd</code> schema.";
                    } else {
                        echo "XML Error: " . htmlspecialchars($errorMessage);
                    }
                    ?>
                </p>
            </div>

            <h5 class="fw-bold mt-3">Parsed XML Announcements Feed:</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-2">
                    <thead class="table-primary">
                        <tr>
                            <th>XML ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Published Date</th>
                            <th>Author</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announcements as $ann): ?>
                            <tr>
                                <td><?php echo $ann['id']; ?></td>
                                <td><span class="fw-bold"><?php echo htmlspecialchars($ann['title']); ?></span></td>
                                <td><?php echo htmlspecialchars($ann['content']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo $ann['date']; ?></span></td>
                                <td><?php echo htmlspecialchars($ann['author']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>

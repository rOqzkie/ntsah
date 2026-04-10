<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Disclosure Agreement</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .iframe-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="mb-3">
            <a href="./register.php" class="btn btn-outline-info btn-sm">← Back to Registration Page</a>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-bold">Non-Disclosure Agreement</h2>
            <p class="text-muted">Please review our Non-Disclosure Agreement document below.</p>
        </div>

        <div class="iframe-container mb-4">
            <iframe src="https://docs.google.com/gview?url=https://ntsah.site/uploads/NDA-Thesis.pdf&embedded=true"></iframe>
        </div>

        <div class="text-center">
            <a href="https://ntsah.site/uploads/NDA-Thesis.pdf" class="btn btn-primary" target="_blank">Download NDA (PDF)</a>
        </div>
    </div>

    <!-- Bootstrap JS (Optional if needed later for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
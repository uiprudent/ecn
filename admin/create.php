<?php
require_once '../config/database.php';

$message = '';
$error = '';

if ($_POST) {
    $title = trim($_POST['title']);
    $excerpt = trim($_POST['excerpt']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);
    $status = $_POST['status'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Generate slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    // Handle thumbnail upload
    $thumbnail = '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $uploadDir = '../uploads/thumbnails/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExtension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $fileName = $slug . '-thumb.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
            $thumbnail = 'uploads/thumbnails/' . $fileName;
        }
    }
    
    // Handle gallery images
    $gallery = [];
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
        $galleryDir = '../uploads/gallery/';
        if (!file_exists($galleryDir)) {
            mkdir($galleryDir, 0777, true);
        }
        
        foreach ($_FILES['gallery']['name'] as $key => $name) {
            if ($_FILES['gallery']['error'][$key] == 0) {
                $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
                $fileName = $slug . '-gallery-' . ($key + 1) . '.' . $fileExtension;
                $uploadPath = $galleryDir . $fileName;
                
                if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $uploadPath)) {
                    $gallery[] = 'uploads/gallery/' . $fileName;
                }
            }
        }
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO news (title, slug, excerpt, content, thumbnail, gallery, category, status, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $title,
            $slug,
            $excerpt,
            $content,
            $thumbnail,
            json_encode($gallery),
            $category,
            $status,
            $featured
        ]);
        
        $message = 'News article created successfully!';
        
        // Redirect after successful creation
        header('Location: index.php');
        exit;
        
    } catch(PDOException $e) {
        $error = 'Error creating article: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create News Article - ECN Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            color: #fff;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: none;
        }
        .preview-image {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-white">ECN Admin</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-newspaper me-2"></i> News Management
                    </a>
                    <a class="nav-link" href="../index.html" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i> View Website
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Create New Article</h2>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>

                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title *</label>
                                            <input type="text" class="form-control" id="title" name="title" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="excerpt" class="form-label">Excerpt</label>
                                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                                      placeholder="Brief description of the article..."></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="content" class="form-label">Content *</label>
                                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="published">Published</option>
                                                <option value="draft">Draft</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category</label>
                                            <select class="form-select" id="category" name="category">
                                                <option value="News">News</option>
                                                <option value="Events">Events</option>
                                                <option value="Energy Policy">Energy Policy</option>
                                                <option value="Research">Research</option>
                                                <option value="Technology">Technology</option>
                                                <option value="Renewable Energy">Renewable Energy</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="featured" name="featured">
                                                <label class="form-check-label" for="featured">
                                                    Featured Article
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="thumbnail" class="form-label">Thumbnail Image</label>
                                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" 
                                                   accept="image/*" onchange="previewThumbnail(this)">
                                            <div id="thumbnail-preview"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="gallery" class="form-label">Gallery Images (Optional)</label>
                                            <input type="file" class="form-control" id="gallery" name="gallery[]" 
                                                   accept="image/*" multiple onchange="previewGallery(this)">
                                            <div id="gallery-preview"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Create Article
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize CKEditor
        CKEDITOR.replace('content');

        function previewThumbnail(input) {
            const preview = document.getElementById('thumbnail-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewGallery(input) {
            const preview = document.getElementById('gallery-preview');
            preview.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image me-2';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
</body>
</html>
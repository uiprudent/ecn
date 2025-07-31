<?php
require_once '../config/database.php';

// Handle delete action
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php');
    exit;
}

// Fetch all news
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$news = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management Dashboard - ECN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        .sidebar .nav-link.active {
            background: #3498db;
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
        .news-thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .badge-featured {
            background: #e74c3c;
        }
        .badge-published {
            background: #27ae60;
        }
        .badge-draft {
            background: #f39c12;
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
                    <a class="nav-link active" href="index.php">
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
                        <h2>News & Events Management</h2>
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add New Article
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Thumbnail</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($news as $article): ?>
                                        <tr>
                                            <td>
                                                <?php if ($article['thumbnail']): ?>
                                                    <img src="../<?php echo htmlspecialchars($article['thumbnail']); ?>" 
                                                         alt="Thumbnail" class="news-thumbnail">
                                                <?php else: ?>
                                                    <div class="news-thumbnail bg-light d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($article['title']); ?></strong>
                                                <?php if ($article['featured']): ?>
                                                    <span class="badge badge-featured ms-2">Featured</span>
                                                <?php endif; ?>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($article['excerpt']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($article['category']); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $article['status'] == 'published' ? 'badge-published' : 'badge-draft'; ?>">
                                                    <?php echo ucfirst($article['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($article['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="../news-detail.php?slug=<?php echo $article['slug']; ?>" 
                                                       class="btn btn-outline-info" target="_blank" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit.php?id=<?php echo $article['id']; ?>" 
                                                       class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $article['id']; ?>" 
                                                       class="btn btn-outline-danger" title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete this article?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
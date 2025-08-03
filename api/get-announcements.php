<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE status = 'published' ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $announcements = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'announcements' => $announcements
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
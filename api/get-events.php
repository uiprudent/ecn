<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE status = 'published' ORDER BY event_date ASC LIMIT 10");
    $stmt->execute();
    $events = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'events' => $events
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
<?php
require_once 'db_connection.php';
require_once 'auth_check.php';

requireLogin();

try {
    // Update orders that have passed their estimated delivery time
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET is_processed = TRUE, is_delivered = TRUE 
        WHERE order_date <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
        AND is_delivered = FALSE
    ");
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 
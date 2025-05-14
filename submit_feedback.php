<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("INSERT INTO feedback (user_id, order_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $_SESSION['last_order_id'], $data['rating'], $data['comment']]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 
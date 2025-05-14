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
    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, reservation_date, reservation_time, num_guests, seating_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $data['date'],
        $data['time'],
        $data['guests'],
        $data['seating']
    ]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 
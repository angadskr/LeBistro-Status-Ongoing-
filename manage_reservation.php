<?php
require_once 'db_connection.php';
require_once 'auth_check.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'];
    
    try {
        switch ($action) {
            case 'create':
                $stmt = $pdo->prepare("
                    INSERT INTO reservations (user_id, reservation_date, reservation_time, num_guests, seating_type)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_SESSION['user_id'],
                    $data['date'],
                    $data['time'],
                    $data['guests'],
                    $data['seating']
                ]);
                break;
                
            case 'update':
                $stmt = $pdo->prepare("
                    UPDATE reservations 
                    SET reservation_date = ?, reservation_time = ?, num_guests = ?, seating_type = ?
                    WHERE reservation_id = ? AND user_id = ?
                ");
                $stmt->execute([
                    $data['date'],
                    $data['time'],
                    $data['guests'],
                    $data['seating'],
                    $data['reservation_id'],
                    $_SESSION['user_id']
                ]);
                break;
                
            case 'delete':
                $stmt = $pdo->prepare("
                    DELETE FROM reservations 
                    WHERE reservation_id = ? AND user_id = ?
                ");
                $stmt->execute([$data['reservation_id'], $_SESSION['user_id']]);
                break;
        }
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch reservations
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM reservations 
            WHERE user_id = ? 
            ORDER BY reservation_date, reservation_time
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'reservations' => $reservations]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} 
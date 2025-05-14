<?php
require_once 'db_connection.php';
require_once 'auth_check.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!isset($data['date']) || !isset($data['time']) || !isset($data['guests']) || !isset($data['seating'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    try {
        // Add validation for date and time
        $reservationDate = new DateTime($data['date'] . ' ' . $data['time']);
        $now = new DateTime();
        
        if ($reservationDate < $now) {
            echo json_encode(['success' => false, 'message' => 'Cannot make reservations for past dates']);
            exit;
        }

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
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to create reservation']);
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserve a Table</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Playfair Display', serif;
      color: #333;
      background-image: url('d6b82fc897b37915d3ef6c9b6be2b33f.jpg'); /* Replace with your image URL */
      background-size: fixed;
      background-position: center;
      background-attachment: fixed;
      background-repeat: repeat;
      text-align: center;
      min-height: 100vh;
    }

    /* Container for the page */
    .container {
      max-width: 900px;
      margin: 20px auto;
      padding: 20px;
        background: #c9c9c287;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 3px solid rgba(15, 0, 0, 0.2);
      backdrop-filter: blur(10px);
      border-radius: 8px;
      
    }

    @keyframes containerGlow {
      0% {
        background: rgba(255, 72, 0, 0.8);

        
      }
      100% {
        background: #e9f50466;
        
      }
    }

    /* Page header */
    .header {
      font-size: 40px;
      background-color: #fff;
      font-weight: bolder;
      color: #0008ff;
      position: relative;
      
      z-index: 1;
      
    }

    /* Seating Chart */
    .seating-chart {
      margin-bottom: 20px;
      margin-top: 40px;
      text-align: center;
    }

    .seating-chart img {
      width: 60%;
      border-radius: 50%;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Reservation Form */
    form {
      text-align: left;
      margin-top: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-size: 16px;
      margin-bottom: 8px;
      color: #555;
    }

    input, select, button {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background-color: #444;
      color: white;
      font-size: 16px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      cursor: pointer;
    }

    button:hover {
      background-color: #555;
    }

    .form-group select {
      background-color: #fff;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">Reserve a Table</div>

    <!-- Seating Chart -->
    <div class="seating-chart">
      <img src="07614e7dc6d3ff8ef51b0acd519ca1cb.jpg" alt="Seating Chart">
    </div>

    <!-- Reservation Form -->
    <form id="reservation-form">
      <div class="form-group">
        <label for="date">Select Date</label>
        <input type="date" id="date" name="date" required>
      </div>

      <div class="form-group">
        <label for="time">Select Time</label>
        <input type="time" id="time" name="time" required>
      </div>

      <div class="form-group">
        <label for="guests">Number of Guests</label>
        <input type="number" id="guests" name="guests" min="1" max="20" required>
      </div>

      <div class="form-group">
        <label for="seating">Seating Type</label>
        <select id="seating" name="seating" required>
          <option value="round">Round Table</option>
          <option value="booth">Booth</option>
          <option value="window">Window Seating</option>
          <option value="outdoor">Outdoor</option>
        </select>
      </div>

      <button type="submit">Reserve Table</button>
    </form>
  </div>

  <script>
    const form = document.getElementById("reservation-form");

    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        const formData = {
            date: document.getElementById("date").value,
            time: document.getElementById("time").value,
            guests: document.getElementById("guests").value,
            seating: document.getElementById("seating").value
        };

        try {
            const response = await fetch('res.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            if (result.success) {
                alert('Reservation confirmed! We\'ll be waiting for you at ' + formData.time);
                form.reset();
            } else {
                alert(result.message || 'Failed to make reservation');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to make reservation. Please try again.');
        }
    });
  </script>
</body>
</html>
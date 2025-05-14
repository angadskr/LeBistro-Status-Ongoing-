<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get the action from GET or POST
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'get_orders':
            // Get all orders for the user
            $stmt = $pdo->prepare("
                SELECT oi.*
                FROM order_items oi
                JOIN orders o ON o.order_id = oi.order_id
                WHERE o.user_id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'items' => $items]);
            break;

        case 'get_order_details':
            $order_id = $_GET['order_id'];
            $stmt = $pdo->prepare("
                SELECT oi.*
                FROM order_items oi
                JOIN orders o ON o.order_id = oi.order_id
                WHERE o.order_id = ? AND o.user_id = ?
            ");
            $stmt->execute([$order_id, $_SESSION['user_id']]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'items' => $items]);
            break;

        case 'delete_order':
            $data = json_decode(file_get_contents('php://input'), true);
            $order_id = $data['order_id'];

            $pdo->beginTransaction();
            
            // Delete order items first
            $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->execute([$order_id]);
            
            // Then delete the order
            $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ? AND user_id = ?");
            $stmt->execute([$order_id, $_SESSION['user_id']]);
            
            $pdo->commit();
            echo json_encode(['success' => true]);
            break;

        case 'update_status':
            // Update orders that have passed their estimated delivery time
            $stmt = $pdo->prepare("
                UPDATE orders 
                SET is_processed = TRUE, is_delivered = TRUE 
                WHERE order_date <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                AND is_delivered = FALSE
            ");
            $stmt->execute();
            echo json_encode(['success' => true]);
            break;

        case 'process_order':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate order data
            if (!isset($data['items']) || empty($data['items'])) {
                echo json_encode(['success' => false, 'message' => 'No items in order']);
                exit;
            }
            
            if (!isset($data['total_amount']) || $data['total_amount'] <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid total amount']);
                exit;
            }

            try {
                $pdo->beginTransaction();

                // Insert order
                $stmt = $pdo->prepare("
                    INSERT INTO orders (user_id, order_type, total_amount, order_date) 
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$_SESSION['user_id'], $data['order_type'], $data['total_amount']]);
                $order_id = $pdo->lastInsertId();

                // Insert order items
                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, item_name, quantity, price) 
                    VALUES (?, ?, ?, ?)
                ");
                
                foreach ($data['items'] as $item) {
                    if (!isset($item['item_name']) || !isset($item['quantity']) || !isset($item['price'])) {
                        throw new Exception('Invalid item data');
                    }
                    $stmt->execute([$order_id, $item['item_name'], $item['quantity'], $item['price']]);
                }

                $pdo->commit();
                echo json_encode(['success' => true, 'order_id' => $order_id]);
            } catch (Exception $e) {
                $pdo->rollBack();
                error_log($e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Failed to process order']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Online</title>
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: url('c1efeec4f180badbceec7816920b2852.jpg') ;
      background-repeat: repeat;
      background-size:contain;
      animation: backgroundChange 5s infinite;
      color: #333;
      text-align: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      
    }
  @keyframes backgroundChange {
    0% {
      background-image: url('c1efeec4f180badbceec7816920b2852.jpg');
    }
    50% {
      background-image: url('049097d36e419936e0e94d4294bb5684.jpg');
      background-repeat: repeat-x;
    }
    100% {
      background-image: url('c1efeec4f180badbceec7816920b2852.jpg');
      background-repeat: repeat;
    }
  }
   
    /* Container */
    .container {
      width: 700px;
      height: 700px;
      margin: 0 auto;
      padding: 40px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
      overflow-y: auto; /* Add scroll if content overflows */
    }

    /* Header */
    .header {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 20px;
      align-items: center;
      color: #444;
    }

    /* Buttons Section */
    .buttons {
      padding-top: 20px;
      display: flex;
      justify-content: center;
      gap: 100px; /* Increased spacing between buttons */
      margin: 30px 0;
    }

    .button {
      min-width: 200px; /* Fixed minimum width for buttons */
      background-color: #444;
      color: white;
      padding: 15px 30px;
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .button:hover {
      background-color: #555;
    }

    /* Dynamic Content Section */
    .content {
      margin-top: 30px;
      text-align: left;
      width: 100%;
      max-height: 500px; /* Limit height to prevent overflow */
      overflow-y: auto; /* Add scroll for content */
    }

    .content.hidden {
      display: none;
    }

    /* Card Style for Menu */
    .menu-card {
      background-color: #444;
      color: white;
      padding: 20px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      text-decoration: none;
      font-size: 20px;
      font-weight: bold;
      display: block;
      margin: 20px auto;
      max-width: 300px;
      transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .menu-card:hover {
      transform: scale(1.05);
      background-color: #555;
    }

    /* Menu Items Styling */
    .menu-items {
      display: grid;
      grid-template-columns: repeat(2, 1fr); /* Two columns */
      gap: 15px;
      padding: 15px;
      width: 100%;
    }

    .menu-item {
      background: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      font-size: 0.9em; /* Slightly smaller font */
    }

    .item-controls {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 10px;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .quantity-btn {
      padding: 5px 10px;
      background: #444;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .total-amount {
      font-size: 1.2em;
      font-weight: bold;
      margin: 20px 0;
    }
  </style>
</head>
<body>
</body>

  <div class="container">
    <div class="header">Order Online</div>

    <!-- Buttons -->
    <div class="buttons">
      <button class="button" id="pickup">Pick Up</button>
      <button class="button" id="experience">Order for an Experience</button>
    </div>

    <!-- Add this section for order history -->
    <div id="order-history" class="hidden">
        <h2>Your Orders</h2>
        <div id="orders-list"></div>
    </div>

    <!-- Add this button to view orders -->
    <button class="button" id="view-orders">View My Orders</button>

    <!-- Pickup Content -->
    <div class="content hidden" id="pickup-content">
      <h2>Select Your Items</h2>
      <div class="menu-items">
        <!-- Menu items will be dynamically added here -->
      </div>
      <div class="total-amount">Total: $<span id="total">0.00</span></div>
      <form id="pickup-form" class="hidden">
        <div class="form-group">
          <label for="address">Enter your address</label>
          <input type="text" id="address" name="address" placeholder="Enter your address" required>
        </div>
        <div class="form-group">
          <button type="submit" class="button" onclick="deltime()">Submit</button>
        </div>
      </form>
      <div class="tracking hidden" id="tracking-status">
        <h3>Tracking Status</h3>
        <p>Your order is being prepared. </p>
        <p id="delivery-time">

        </p>
      </div>
    </div>

    <!-- Order for Experience Content -->
    <div class="content hidden" id="experience-content">
      <h2>Order for an Experience</h2>
      <a href="menu.html" class="menu-card">Click Here to View the Menu</a>
    </div>
  </div>

  <script>
    // References to buttons and content sections
    const pickupButton = document.getElementById('pickup');
    const experienceButton = document.getElementById('experience');
    const pickupContent = document.getElementById('pickup-content');
    const experienceContent = document.getElementById('experience-content');
    const trackingStatus = document.getElementById('tracking-status');
    const deliveryTime = document.getElementById('delivery-time');
    function deltime() {
        document.getElementById("delivery-time").innerHTML = 'Estimated time of delivery: <span id="delivery-time"></span>.';
    }

    // Menu items data
    const menuItems = [
      { id: 1, name: 'Item 1', price: 10.99 },
      { id: 2, name: 'Item 2', price: 12.99 },
      { id: 3, name: 'Item 3', price: 8.99 },
      // Add more items as needed
    ];

    let cart = {};
    let total = 0;

    // Create menu items
    function createMenuItems() {
      const menuContainer = document.querySelector('.menu-items');
      menuItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'menu-item';
        itemElement.innerHTML = `
          <h3>${item.name}</h3>
          <p>$${item.price}</p>
          <div class="item-controls">
            <input type="checkbox" id="item-${item.id}">
            <div class="quantity-control">
              <button class="quantity-btn minus" data-id="${item.id}">-</button>
              <span class="quantity" id="qty-${item.id}">0</span>
              <button class="quantity-btn plus" data-id="${item.id}">+</button>
            </div>
          </div>
        `;
        menuContainer.appendChild(itemElement);
      });
    }

    // Update total amount
    function updateTotal() {
      total = Object.entries(cart).reduce((sum, [id, qty]) => {
        const item = menuItems.find(item => item.id === parseInt(id));
        return sum + (item.price * qty);
      }, 0);
      document.getElementById('total').textContent = total.toFixed(2);
    }

    // Event listeners
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('quantity-btn')) {
        const id = e.target.dataset.id;
        const isPlus = e.target.classList.contains('plus');
        cart[id] = cart[id] || 0;
        
        if (isPlus) {
          cart[id]++;
        } else if (cart[id] > 0) {
          cart[id]--;
        }
        
        document.getElementById(`qty-${id}`).textContent = cart[id];
        updateTotal();
      }
    });

    // Modified pickup button click handler
    pickupButton.addEventListener('click', () => {
      pickupContent.classList.remove('hidden');
      experienceContent.classList.add('hidden');
      createMenuItems();
    });

    // Modified form submission
    pickupForm.addEventListener('submit', (event) => {
      event.preventDefault();
      if (total === 0) {
        alert('Please select at least one item');
        return;
      }
      trackingStatus.classList.remove('hidden');
      const now = new Date();
      const estimatedTime = new Date(now.getTime() + 30 * 60000);
      deliveryTime.textContent = `Estimated delivery time: ${estimatedTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
    });

    // Show address form only after items are selected
    document.addEventListener('change', (e) => {
      if (e.target.type === 'checkbox') {
        const id = e.target.id.split('-')[1];
        if (e.target.checked) {
          cart[id] = cart[id] || 1;
        } else {
          delete cart[id];
        }
        document.getElementById(`qty-${id}`).textContent = cart[id] || 0;
        updateTotal();
        
        if (Object.keys(cart).length > 0) {
          pickupForm.classList.remove('hidden');
        } else {
          pickupForm.classList.add('hidden');
        }
      }
    });

    // Experience button click handler
    experienceButton.addEventListener('click', () => {
      window.location.href = 'menu.html';
    });

    // Add these functions after existing script
    async function submitOrder(orderType) {
        const orderItems = Object.entries(cart).map(([id, qty]) => {
            const item = menuItems.find(item => item.id === parseInt(id));
            return {
                item_name: item.name,
                quantity: qty,
                price: item.price
            };
        });

        const orderData = {
            order_type: orderType,
            total_amount: total,
            items: orderItems
        };

        try {
            const response = await fetch('process_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();
            if (result.success) {
                if (orderType === 'pickup') {
                    trackingStatus.classList.remove('hidden');
                    // Show feedback form after estimated delivery time
                    setTimeout(showFeedbackForm, 30 * 60 * 1000); // 30 minutes
                } else {
                    window.location.href = 'res.html';
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function showFeedbackForm() {
        const feedbackHtml = `
            <div id="feedback-form" class="mt-4">
                <h3>Please Rate Your Experience</h3>
                <select id="rating" class="mb-2">
                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>
                </select>
                <textarea id="comment" placeholder="Your feedback..." class="w-full mb-2"></textarea>
                <button onclick="submitFeedback()" class="button">Submit Feedback</button>
            </div>
        `;
        trackingStatus.insertAdjacentHTML('beforeend', feedbackHtml);
    }

    async function submitFeedback() {
        const rating = document.getElementById('rating').value;
        const comment = document.getElementById('comment').value;

        try {
            const response = await fetch('submit_feedback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ rating, comment })
            });

            const result = await response.json();
            if (result.success) {
                alert('Thank you for your feedback!');
                document.getElementById('feedback-form').remove();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Function to load user's orders
    async function loadOrders() {
        try {
            const response = await fetch('get_orders.php');
            const data = await response.json();
            
            if (data.success) {
                const ordersList = document.getElementById('orders-list');
                ordersList.innerHTML = data.orders.map(order => `
                    <div class="order-item">
                        <h3>Order #${order.order_id}</h3>
                        <p>Type: ${order.order_type}</p>
                        <p>Total: $${order.total_amount}</p>
                        <p>Status: ${order.is_delivered ? 'Delivered' : 'Processing'}</p>
                        ${!order.is_delivered ? `
                            <button onclick="updateOrder(${order.order_id})" class="button">Update</button>
                            <button onclick="deleteOrder(${order.order_id})" class="button">Cancel</button>
                        ` : ''}
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Function to update order
    async function updateOrder(orderId) {
        // Show current order items and allow modifications
        try {
            const response = await fetch(`get_order_details.php?order_id=${orderId}`);
            const data = await response.json();
            
            if (data.success) {
                cart = {};
                data.items.forEach(item => {
                    cart[item.item_id] = item.quantity;
                });
                updateTotal();
                // Show update form
                document.getElementById('pickup-content').classList.remove('hidden');
                document.getElementById('order-history').classList.add('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Function to delete order
    async function deleteOrder(orderId) {
        if (confirm('Are you sure you want to cancel this order?')) {
            try {
                const response = await fetch('delete_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order_id: orderId })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Order cancelled successfully');
                    loadOrders();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }

    // Add event listener for view orders button
    document.getElementById('view-orders').addEventListener('click', () => {
        document.getElementById('order-history').classList.remove('hidden');
        document.getElementById('pickup-content').classList.add('hidden');
        document.getElementById('experience-content').classList.add('hidden');
        loadOrders();
    });

    // Add this to your existing script section
    function checkOrderStatus() {
        fetch('update_order_status.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadOrders(); // Refresh the orders list
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Check order status every minute
    setInterval(checkOrderStatus, 60000);

    // Add reservation management functions
    async function loadReservations() {
        try {
            const response = await fetch('order.php?action=get_reservations');
            const data = await response.json();
            
            if (data.success) {
                // Update UI with reservations
                const reservationsList = document.createElement('div');
                reservationsList.innerHTML = data.reservations.map(res => `
                    <div class="reservation-item">
                        <p>Date: ${res.reservation_date}</p>
                        <p>Time: ${res.reservation_time}</p>
                        <p>Guests: ${res.num_guests}</p>
                        <p>Seating: ${res.seating_type}</p>
                        <button onclick="updateReservation(${res.reservation_id})">Update</button>
                        <button onclick="deleteReservation(${res.reservation_id})">Cancel</button>
                    </div>
                `).join('');
                
                document.getElementById('reservations-list').innerHTML = reservationsList.innerHTML;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function updateReservation(reservationId) {
        // Show update form with current reservation details
        const formData = {
            action: 'update',
            reservation_id: reservationId,
            // Add form data here
        };
        
        try {
            const response = await fetch('order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            if (result.success) {
                alert('Reservation updated successfully');
                loadReservations();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function deleteReservation(reservationId) {
        if (confirm('Are you sure you want to cancel this reservation?')) {
            try {
                const response = await fetch('order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'delete',
                        reservation_id: reservationId
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Reservation cancelled successfully');
                    loadReservations();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }
  </script>
</body>
</html>


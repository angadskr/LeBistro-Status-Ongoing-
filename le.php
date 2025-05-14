<?php
$host = 'localhost';
$dbname = 'lebistro';
$username = 'root';
$password = 'Angad123@skr#';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Connection failed. Please try again later.");
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Bistro - Fine Dining Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    <script>
        console.log("Page is loading");
    </script>
    <style>
        .playfair { font-family: 'Playfair Display', serif; }
        .poppins { font-family: 'Poppins', sans-serif; }
        #map { height: 300px; }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .active-nav { border-bottom: 2px solid #4A5568; }
        img {
            min-height: 200px;
            background-color: #ccc;
        }
    </style>
</head>
<body class="bg-gray-50 poppins">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl playfair font-bold text-gray-800">Le Bistro</h1>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-gray-900 nav-link">Home</a>
                    <a href="menu.php" class="text-gray-600 hover:text-gray-900 nav-link">Menu</a>
                    <a href="res.php" class="text-gray-600 hover:text-gray-900 nav-link">Reserve</a>
                    <a href="order.php" class="text-gray-600 hover:text-gray-900 nav-link">Order</a>
                    <a href="#careers" class="text-gray-600 hover:text-gray-900 nav-link">Careers</a>
                    <button id="loginBtn" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Login</button>
                    <button id="getStartedBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Get Started</button>
                </div>
                <div class="md:hidden flex items-center">
                    <button id="mobileMenuBtn" class="text-gray-600"><i class="bi bi-list text-2xl"></i></button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white">
            <a href="#home" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Home</a>
            <a href="#menu" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Menu</a>
            <a href="#reserve" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Reserve</a>
            <a href="#order" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Order</a>
            <a href="#careers" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Careers</a>
            <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-20">
        <div class="relative h-[80vh]">
            <img 
                src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4" 
                alt="Restaurant Interior" 
                class="w-full h-full object-cover"
                onerror="this.src='https://place-hold.it/800x400/gray/white&text=Restaurant%20Interior'"
            >
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="text-center text-white">
                    <h1 class="text-5xl md:text-6xl playfair mb-4">Le Bistro</h1>
                    <p class="text-xl md:text-2xl mb-8">Experience Fine Dining at its Best</p>
                    <div class="space-x-4">
                        <button class="bg-white text-gray-900 px-6 py-3 rounded-md hover:bg-gray-100">Reserve Table</button>
                        <button class="border-2 border-white text-white px-6 py-3 rounded-md hover:bg-white hover:text-gray-900" >View Menu</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews & Accolades -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl playfair text-center mb-12">Reviews & Accolades</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <i class="bi bi-star-fill text-yellow-400 text-2xl mb-4"></i>
                    <h3 class="text-xl mb-2">Michelin Star</h3>
                    <p class="text-gray-600">Awarded 2 Michelin stars for exceptional cuisine</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <i class="bi bi-award text-yellow-400 text-2xl mb-4"></i>
                    <h3 class="text-xl mb-2">Best Fine Dining</h3>
                    <p class="text-gray-600">Voted Best Fine Dining Restaurant 2023</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <i class="bi bi-hand-thumbs-up text-yellow-400 text-2xl mb-4"></i>
                    <h3 class="text-xl mb-2">4.9/5 Rating</h3>
                    <p class="text-gray-600">Based on 1000+ customer reviews</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl playfair text-center mb-12">Find Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div id="map" class="rounded-lg shadow-lg"></div>
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-2xl mb-4">Le Bistro</h3>
                    <p class="mb-4"><i class="bi bi-geo-alt"></i> 123 Gourmet Street, Culinary District</p>
                    <p class="mb-4"><i class="bi bi-telephone"></i> (555) 123-4567</p>
                    <p class="mb-4"><i class="bi bi-clock"></i> Mon-Sun: 11:00 AM - 11:00 PM</p>
                    <button id="getDirections" class="bg-gray-800 text-white px-6 py-3 rounded-md hover:bg-gray-700 w-fit">
                        Get Directions
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section REPLACE WITH CARD IMAGES ADN QUOTES -->
    <section id="menu" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl playfair text-center mb-12">Our Menu</h2>
            <div class="flex justify-center space-x-4 mb-8">
                <button class="menu-category active bg-gray-800 text-white px-4 py-2 rounded-md" data-category="starters">Starters</button>
                <button class="menu-category bg-gray-200 text-gray-800 px-4 py-2 rounded-md" data-category="mains">Main Course</button>
                <button class="menu-category bg-gray-200 text-gray-800 px-4 py-2 rounded-md" data-category="desserts">Desserts</button>
            </div>
            <div id="menuItems" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Menu items will be dynamically populated -->
            </div>
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reserve" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl playfair text-center mb-12">Reserve a Table</h2>
            <form id="reservationForm" class="max-w-lg mx-auto">
                <div class="mb-4">
                    <input type="date" class="w-full p-3 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <input type="time" class="w-full p-3 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <select class="w-full p-3 border rounded-md" required>
                        <option value="">Number of Guests</option>
                        <option value="2">2 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="6">6 Guests</option>
                        <option value="8">8 Guests</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-gray-800 text-white px-6 py-3 rounded-md hover:bg-gray-700">
                    Reserve Now
                </button>
            </form>
        </div>
    </section>

    <!-- Order Section -->
    <section id="order" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl playfair text-center mb-12">Order Online</h2>
            <div class="flex justify-center space-x-4 mb-8">
                <button class="bg-gray-800 text-white px-6 py-3 rounded-md" onclick="setOrderType('delivery')">Delivery</button>
                <button class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md" onclick="setOrderType('pickup')">Pickup</button>
            </div>
            <div id="orderItems" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Order items will be dynamically populated -->
            </div>
        </div>
    </section>

    <!-- Careers Section -->
    <section id="careers" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl playfair text-center mb-12">Join Our Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl mb-4">Head Chef</h3>
                    <p class="mb-4">We're looking for an experienced Head Chef to lead our kitchen team.</p>
                    <button class="apply-btn bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Apply Now</button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl mb-4">Server</h3>
                    <p class="mb-4">Join our front-of-house team as a professional server.</p>
                    <button class="apply-btn bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Apply Now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Modals -->
    <div id="loginModal" class="modal">
        <div class="bg-white p-8 rounded-lg w-96 mx-auto mt-20">
            <h2 class="text-2xl mb-4">Login</h2>
            <form id="loginForm" action="login.php" method="POST">
                <div id="loginError" class="hidden mb-4 text-red-500"></div>
                <input type="email" name="email" placeholder="Email" class="w-full p-2 mb-4 border rounded" required>
                <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-4 border rounded" required>
                <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded">Login</button>
            </form>
            <p class="mt-4 text-center">
                Don't have an account? <button id="switchToSignup" class="text-blue-600 hover:text-blue-800">Create Account</button>
            </p>
        </div>
    </div>

    <div id="signupModal" class="modal">
        <div class="bg-white p-8 rounded-lg w-96 mx-auto mt-20">
            <h2 class="text-2xl mb-4">Create Account</h2>
            <form id="signupForm" action="signup.php" method="POST">
                <div id="signupError" class="hidden mb-4 text-red-500"></div>
                <input type="text" name="name" placeholder="Full Name" class="w-full p-2 mb-4 border rounded" required>
                <input type="email" name="email" placeholder="Email" class="w-full p-2 mb-4 border rounded" required>
                <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-4 border rounded" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full p-2 mb-4 border rounded" required>
                <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded">Sign Up</button>
            </form>
            <p class="mt-4 text-center">
                Already have an account? <button id="switchToLogin" class="text-blue-600 hover:text-blue-800">Login</button>
            </p>
        </div>
    </div>

    <script>
        // Navigation
        document.getElementById('mobileMenuBtn').addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        // Modal handling
        const loginBtn = document.getElementById('loginBtn');
        const getStartedBtn = document.getElementById('getStartedBtn');
        const loginModal = document.getElementById('loginModal');
        const signupModal = document.getElementById('signupModal');
        const switchToSignup = document.getElementById('switchToSignup');
        const switchToLogin = document.getElementById('switchToLogin');

        loginBtn.addEventListener('click', () => {
            loginModal.style.display = 'block';
            signupModal.style.display = 'none';
        });

        getStartedBtn.addEventListener('click', () => {
            signupModal.style.display = 'block';
            loginModal.style.display = 'none';
        });

        switchToSignup.addEventListener('click', () => {
            loginModal.style.display = 'none';
            signupModal.style.display = 'block';
        });

        switchToLogin.addEventListener('click', () => {
            signupModal.style.display = 'none';
            loginModal.style.display = 'block';
        });

        window.onclick = (event) => {
            if (event.target == loginModal) {
                loginModal.style.display = 'none';
            }
            if (event.target == signupModal) {
                signupModal.style.display = 'none';
            }
        };

        // Menu handling
        const menuItems = {
            starters: [
                { name: 'French Onion Soup', price: '$12', description: 'Classic soup with gruyere cheese' },
                { name: 'Escargot', price: '$16', description: 'With garlic herb butter' },
                { name: 'Beef Tartare', price: '$18', description: 'Hand-cut beef with traditional garnishes' }
            ],
            mains: [
                { name: 'Beef Bourguignon', price: '$32', description: 'Classic French beef stew' },
                { name: 'Coq au Vin', price: '$28', description: 'Wine braised chicken' },
                { name: 'Duck Confit', price: '$34', description: 'With braised lentils' }
            ],
            desserts: [
                { name: 'Crème Brûlée', price: '$12', description: 'Classic vanilla custard' },
                { name: 'Chocolate Soufflé', price: '$14', description: 'With vanilla ice cream' },
                { name: 'Tarte Tatin', price: '$12', description: 'Upside-down caramelized apple tart' }
            ]
        };

        function displayMenuItems(category) {
            const container = document.getElementById('menuItems');
            container.innerHTML = '';
            menuItems[category].forEach(item => {
                container.innerHTML += `
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl mb-2">${item.name}</h3>
                        <p class="text-gray-600 mb-4">${item.description}</p>
                        <p class="text-lg font-semibold">${item.price}</p>
                    </div>
                `;
            });
        }

        // Initialize map
        const map = L.map('map').setView([40.7128, -74.0060], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([40.7128, -74.0060]).addTo(map);

        // Initial menu display
        displayMenuItems('starters');

        // Menu category buttons
        document.querySelectorAll('.menu-category').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.menu-category').forEach(b => {
                    b.classList.remove('bg-gray-800', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-800');
                });
                e.target.classList.remove('bg-gray-200', 'text-gray-800');
                e.target.classList.add('bg-gray-800', 'text-white');
                displayMenuItems(e.target.dataset.category);
            });
        });

        // Form submissions
        document.getElementById('reservationForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Reservation submitted successfully!');
        });

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    document.getElementById('loginError').textContent = data.message;
                    document.getElementById('loginError').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('signup.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    document.getElementById('signupError').textContent = data.message;
                    document.getElementById('signupError').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Get user location
        document.getElementById('getDirections').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const userLocation = [position.coords.latitude, position.coords.longitude];
                    map.setView(userLocation, 13);
                    L.marker(userLocation).addTo(map);
                });
            }
        });
    </script>
</body>
</html>
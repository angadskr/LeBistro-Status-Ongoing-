<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Bistro - Fine Dining</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-lato { font-family: 'Lato', sans-serif; }
        .menu-section:not(:last-child) { border-bottom: 1px solid #e5e7eb; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <h1 class="font-playfair text-3xl font-bold text-gray-800">Le Bistro</h1>
                <div class="hidden md:flex space-x-8">
                    <a href="#starters" class="font-lato text-gray-600 hover:text-gray-900">Starters</a>
                    <a href="#main" class="font-lato text-gray-600 hover:text-gray-900">Main Course</a>
                    <a href="#dessert" class="font-lato text-gray-600 hover:text-gray-900">Dessert</a>
                    <a href="#drinks" class="font-lato text-gray-600 hover:text-gray-900">Drinks</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Starters Section -->
        <section id="starters" class="menu-section pb-12 mb-12">
            <h2 class="font-playfair text-3xl mb-8 text-gray-800 border-b pb-2">Starters</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1541014741259-de529411b96a" alt="French Onion Soup" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">French Onion Soup</h3>
                        <p class="font-lato text-gray-600 mt-1">Classic caramelized onion soup with gruyère cheese</p>
                        <p class="font-lato font-bold mt-2">$12</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1626200419199-391ae4be7f34" alt="Escargot" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Escargot à la Bourguignonne</h3>
                        <p class="font-lato text-gray-600 mt-1">Burgundy snails in garlic herb butter</p>
                        <p class="font-lato font-bold mt-2">$16</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Course Section -->
        <section id="main" class="menu-section pb-12 mb-12">
            <h2 class="font-playfair text-3xl mb-8 text-gray-800 border-b pb-2">Main Course</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1600891964092-4316c288032e" alt="Coq au Vin" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Coq au Vin</h3>
                        <p class="font-lato text-gray-600 mt-1">Braised chicken in red wine with mushrooms</p>
                        <p class="font-lato font-bold mt-2">$32</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836" alt="Beef Bourguignon" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Beef Bourguignon</h3>
                        <p class="font-lato text-gray-600 mt-1">Classic French beef stew with red wine</p>
                        <p class="font-lato font-bold mt-2">$36</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dessert Section -->
        <section id="dessert" class="menu-section pb-12 mb-12">
            <h2 class="font-playfair text-3xl mb-8 text-gray-800 border-b pb-2">Dessert</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1554856182-d9b44cc75a54" alt="Crème Brûlée" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Crème Brûlée</h3>
                        <p class="font-lato text-gray-600 mt-1">Classic vanilla custard with caramelized sugar</p>
                        <p class="font-lato font-bold mt-2">$12</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1587314168485-3236d6710814" alt="Chocolate Soufflé" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Chocolate Soufflé</h3>
                        <p class="font-lato text-gray-600 mt-1">Warm chocolate soufflé with vanilla ice cream</p>
                        <p class="font-lato font-bold mt-2">$14</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Drinks Section -->
        <section id="drinks" class="menu-section">
            <h2 class="font-playfair text-3xl mb-8 text-gray-800 border-b pb-2">Drinks</h2>
            
            <!-- Mocktails -->
            <h3 class="font-playfair text-2xl mb-6 text-gray-700">Mocktails</h3>
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1621873493381-4b0fdd551405" alt="Virgin Mojito" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Virgin Mojito</h3>
                        <p class="font-lato text-gray-600 mt-1">Fresh mint, lime, and soda water</p>
                        <p class="font-lato font-bold mt-2">$8</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1544145945-f90425340c7e" alt="Passion Fruit Fizz" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Passion Fruit Fizz</h3>
                        <p class="font-lato text-gray-600 mt-1">Fresh passion fruit puree with sparkling water</p>
                        <p class="font-lato font-bold mt-2">$9</p>
                    </div>
                </div>
            </div>

            <!-- Cocktails -->
            <h3 class="font-playfair text-2xl mb-6 text-gray-700">Cocktails</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b" alt="French 75" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">French 75</h3>
                        <p class="font-lato text-gray-600 mt-1">Gin, champagne, lemon juice, and sugar</p>
                        <p class="font-lato font-bold mt-2">$16</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <img src="https://images.unsplash.com/photo-1551538827-9c037cb4f32a" alt="Kir Royale" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h3 class="font-playfair text-xl font-semibold">Kir Royale</h3>
                        <p class="font-lato text-gray-600 mt-1">Crème de cassis topped with champagne</p>
                        <p class="font-lato font-bold mt-2">$15</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h2 class="font-playfair text-2xl">Le Bistro</h2>
                    <p class="font-lato text-gray-400 mt-2">Fine French Cuisine</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="bi bi-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
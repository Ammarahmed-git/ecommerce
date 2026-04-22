<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoStore - Premium ECommerce</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Floating Particles Canvas -->
    <canvas id="particles"></canvas>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <h2>Neo<span>Store</span></h2>
            </div>
            <ul class="nav-menu">
                <li><a href="#home">Home</a></li>              <!-- FIXED: #home -->
                <li><a href="#products">Products</a></li>      <!-- FIXED: #products -->
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="nav-icons">
                <div class="cart-icon" id="cartIcon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </div>
            </div>
            <div class="hamburger">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">  <!-- ✅ Correct ID -->
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="gradient-text">Future of</span><br>
                <span class="gradient-text">Shopping</span>
            </h1>
            <p class="hero-subtitle">Discover premium products with cutting-edge design</p>
            <button class="cta-button" onclick="scrollToProducts()">
                <span>Shop Now</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
        <div class="hero-3d">
            <div id="three-container"></div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products">  <!-- ✅ Correct ID -->
        <div class="container">
            <h2 class="section-title">Featured Products</h2>
            <div class="products-grid" id="productsGrid">
                <!-- Products loaded dynamically via JS -->
            </div>
        </div>
    </section>

    <!-- Cart Modal -->
    <div class="cart-modal" id="cartModal">
        <div class="cart-modal-content">
            <div class="cart-header">
                <h3>Shopping Cart</h3>
                <span class="close-cart" id="closeCart">&times;</span>
            </div>
            <div class="cart-items" id="cartItems"></div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total: $<span id="cartTotal">0</span></span>
                </div>
                <a href="cart.php" class="checkout-btn">Checkout →</a>  <!-- FIXED: Link to cart.php -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
<?php
session_start();
require_once 'config.php';

// Handle checkout form
if ($_POST) {
    // Simulate payment processing
    $orderData = [
        'items' => $_POST['cart_items'] ?? [],
        'total' => floatval($_POST['total']),
        'customer' => [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'address' => $_POST['address']
        ],
        'order_id' => 'ORD-' . time(),
        'status' => 'completed'
    ];
    
    // In real app, save to orders table and process payment
    $_SESSION['last_order'] = $orderData;
    
    // Clear cart
    unset($_SESSION['cart']);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'order_id' => $orderData['order_id']]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - NeoStore</title>
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
            <div class="nav-icons">
                <a href="index.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>
    </nav>

    <div class="cart-page">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Shopping Cart</h1>
                <div class="cart-summary">
                    <span id="cartItemCount">0 items</span>
                    <span class="total-price">Total: $<span id="cartTotalPrice">0</span></span>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="cart-content">
                <div class="cart-items-section" id="cartItemsSection">
                    <!-- Items loaded here -->
                </div>

                <!-- Checkout Form -->
                <div class="checkout-section">
                    <h3>Checkout</h3>
                    <form id="checkoutForm" class="checkout-form">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" name="card_number" placeholder="**** **** **** ****" maxlength="19" required>
                        </div>
                        <div class="card-details">
                            <div class="form-group">
                                <label>Expiry</label>
                                <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required>
                            </div>
                            <div class="form-group">
                                <label>CVC</label>
                                <input type="text" name="cvc" placeholder="***" maxlength="4" required>
                            </div>
                        </div>
                        
                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>$15.00</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total:</span>
                                <span id="finalTotal">$0.00</span>
                            </div>
                        </div>

                        <button type="submit" class="checkout-btn-large" id="checkoutBtn">
                            <i class="fas fa-lock"></i>
                            Pay Now - $<span id="payAmount">0</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal" id="successModal">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Order Successful!</h2>
            <p>Your order has been placed successfully.</p>
            <div class="order-details">
                <strong>Order ID:</strong> <span id="orderId"></span>
            </div>
            <div class="success-buttons">
                <button onclick="window.location.href='index.php'" class="btn-primary">Continue Shopping</button>
                <button onclick="printReceipt()" class="btn-secondary">Print Receipt</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        document.addEventListener('DOMContentLoaded', function() {
            initParticles();
            loadCart();
            setupEventListeners();
        });

        function initParticles() {
            // Same particle system as index.js
            const canvas = document.getElementById('particles');
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            
            let particlesArray = [];
            
            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 2 + 1;
                    this.speedX = Math.random() * 0.5 - 0.25;
                    this.speedY = Math.random() * 0.5 - 0.25;
                }
                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    if (this.size > 0.2) this.size -= 0.01;
                    if (this.x > canvas.width || this.x < 0) this.speedX *= -1;
                    if (this.y > canvas.height || this.y < 0) this.speedY *= -1;
                }
                draw() {
                    ctx.fillStyle = 'rgba(0, 245, 255, 0.3)';
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                for (let i = 0; i < particlesArray.length; i++) {
                    particlesArray[i].update();
                    particlesArray[i].draw();
                    if (particlesArray[i].size <= 0.2) {
                        particlesArray.splice(i, 1);
                        i--;
                    }
                }
                requestAnimationFrame(animate);
            }
            
            for (let i = 0; i < 100; i++) {
                particlesArray.push(new Particle());
            }
            animate();
        }

        function loadCart() {
            if (cart.length === 0) {
                document.getElementById('cartItemsSection').innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart" style="font-size: 4rem; color: rgba(255,255,255,0.3); margin-bottom: 1rem;"></i>
                        <h3>Your cart is empty</h3>
                        <p>Add some amazing products to get started!</p>
                        <a href="index.php" class="btn-primary">Start Shopping</a>
                    </div>
                `;
                document.querySelector('.checkout-section').style.display = 'none';
                return;
            }
            
            displayCartItems();
            updateTotals();
        }

        function displayCartItems() {
            const container = document.getElementById('cartItemsSection');
            container.innerHTML = cart.map((item, index) => `
                <div class="cart-item-large" data-index="${index}">
                    <div class="cart-item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-details">
                        <h4>${item.name}</h4>
                        <div class="item-price">$${item.price.toFixed(2)}</div>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                            <button class="remove-btn" onclick="removeItem(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="item-total">
                            $${(item.price * item.quantity).toFixed(2)}
                        </div>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('cartItemCount').textContent = `${cart.reduce((sum, item) => sum + item.quantity, 0)} items`;
        }

        function updateQuantity(index, change) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCartItems();
            updateTotals();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCartItems();
            updateTotals();
        }

        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const shipping = 15.00;
            const total = subtotal + shipping;
            
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('finalTotal').textContent = `$${total.toFixed(2)}`;
            document.getElementById('cartTotalPrice').textContent = total.toFixed(2);
            document.getElementById('payAmount').textContent = total.toFixed(2);
        }

        function setupEventListeners() {
            const form = document.getElementById('checkoutForm');
            form.addEventListener('submit', handleCheckout);
            
            // Card input formatting
            const cardNumber = form.querySelector('[name="card_number"]');
            cardNumber.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
                let matches = value.match(/\d{4,16}/g);
                let match = matches && matches[0] || '';
                let parts = [];
                
                for (let i = 0, len = match.length; i < len; i += 4) {
                    parts.push(match.substring(i, i + 4));
                }
                
                if (parts.length) {
                    e.target.value = parts.join(' ');
                } else {
                    e.target.value = value;
                }
            });
        }

        function handleCheckout(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const cartItems = cart.map(item => ({
                name: item.name,
                quantity: item.quantity,
                price: item.price
            }));
            
            // Add cart items to form data
            formData.append('cart_items', JSON.stringify(cartItems));
            formData.append('total', document.getElementById('finalTotal').textContent.replace('$', ''));
            
            // Animate checkout button
            const btn = document.getElementById('checkoutBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                fetch('cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.removeItem('cart');
                        showSuccessModal(data.order_id);
                    }
                })
                .catch(() => {
                    alert('Payment failed. Please try again.');
                    btn.innerHTML = '<i class="fas fa-lock"></i> Pay Now - $0';
                    btn.disabled = false;
                });
            }, 2000);
        }

        function showSuccessModal(orderId) {
            document.getElementById('orderId').textContent = orderId;
            document.getElementById('successModal').style.display = 'flex';
            
            // Success animation
            gsap.fromTo('.success-content', 
                { scale: 0.7, opacity: 0, y: 50 },
                { scale: 1, opacity: 1, y: 0, duration: 0.8, ease: "back.out(1.7)" }
            );
        }

        function printReceipt() {
            window.print();
        }
    </script>

    <style>
        /* Cart Page Specific Styles */
        .cart-page { min-height: 100vh; padding: 120px 0 50px; }
        .page-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 3rem; 
            flex-wrap: wrap; 
            gap: 1rem;
        }
        .page-title { font-size: 3rem; font-weight: 800; background: linear-gradient(45deg, #00f5ff, #ff00ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .cart-summary { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; }
        .total-price { font-size: 1.5rem; font-weight: 700; color: #00f5ff; }

        .cart-content { display: grid; grid-template-columns: 1fr 400px; gap: 3rem; }
        .cart-items-section { background: rgba(255,255,255,0.05); border-radius: 20px; padding: 2rem; max-height: 600px; overflow-y: auto; }

        .cart-item-large { 
            display: flex; gap: 1.5rem; padding: 1.5rem; 
            background: rgba(255,255,255,0.03); 
            border-radius: 15px; 
            margin-bottom: 1rem; 
            transition: all 0.3s ease;
        }
        .cart-item-large:hover { transform: translateX(5px); box-shadow: 0 10px 30px rgba(0,245,255,0.1); }
        .cart-item-image img { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; }
        .cart-item-details h4 { margin-bottom: 0.5rem; font-size: 1.2rem; }
        .item-price { color: #00f5ff; font-weight: 600; margin-bottom: 1rem; }
        .quantity-controls { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .quantity-btn { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; font-size: 1.1rem; }
        .quantity-btn:hover { background: rgba(0,245,255,0.3); }
        .remove-btn { background: #ff006e !important; width: 35px; height: 35px; }
        .item-total { font-weight: 700; color: #00f5ff; font-size: 1.2rem; }

        .checkout-section { background: rgba(255,255,255,0.05); border-radius: 20px; padding: 2rem; }
        .checkout-section h3 { margin-bottom: 1.5rem; font-size: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group textarea { 
            width: 100%; padding: 1rem; background: rgba(255,255,255,0.1); 
            border: 1px solid rgba(255,255,255,0.2); 
            border-radius: 12px; color: white; 
            font-size: 1rem; 
        }
        .form-group input::placeholder { color: rgba(255,255,255,0.6); }
        .card-details { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        
        .order-summary { margin: 2rem 0; padding: 1.5rem; background: rgba(0,245,255,0.1); border-radius: 12px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; }
        .summary-row.total { font-weight: 700; font-size: 1.3rem; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 0.5rem; }

        .checkout-btn-large { 
            width: 100%; background: linear-gradient(45deg, #00f5ff, #ff00ff); 
            border: none; padding: 1.2rem; border-radius: 12px; 
            font-size: 1.2rem; font-weight: 600; cursor: pointer; 
            transition: all 0.3s ease; margin-top: 1rem;
        }
        .checkout-btn-large:hover {
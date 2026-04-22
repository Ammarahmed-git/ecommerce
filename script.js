// Global variables
let scene, camera, renderer, model, controls;
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);

// Particle system
let particles;

// Initialize everything when DOM loads
document.addEventListener('DOMContentLoaded', function() {
    initParticles();
    initThreeJS();
    loadProducts();
    updateCartUI();
    setupEventListeners();
    initScrollAnimations();
});

// 1. PARTICLE SYSTEM
function initParticles() {
    const canvas = document.getElementById('particles');
    const ctx = canvas.getContext('2d');
    
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    let particlesArray = [];
    let mouse = { x: 0, y: 0 };
    
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
            ctx.fillStyle = 'rgba(0, 245, 255, 0.5)';
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
    
    function handleMouseMove(e) {
        for (let i = 0; i < 5; i++) {
            particlesArray.push(new Particle());
        }
    }
    
    canvas.addEventListener('mousemove', handleMouseMove);
    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
    
    for (let i = 0; i < 100; i++) {
        particlesArray.push(new Particle());
    }
    
    animate();
}

// 2. THREE.JS 3D MODEL
function initThreeJS() {
    const container = document.getElementById('three-container');
    
    // Scene
    scene = new THREE.Scene();
    scene.fog = new THREE.Fog(0x0f0f23, 10, 100);
    
    // Camera
    camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.z = 5;
    
    // Renderer
    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setClearColor(0x000000, 0);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);
    
    // Controls
    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    
    // Lighting
    const ambientLight = new THREE.AmbientLight(0x404040, 0.4);
    scene.add(ambientLight);
    
    const pointLight1 = new THREE.PointLight(0x00f5ff, 1, 100);
    pointLight1.position.set(5, 5, 5);
    pointLight1.castShadow = true;
    scene.add(pointLight1);
    
    const pointLight2 = new THREE.PointLight(0xff00ff, 1, 100);
    pointLight2.position.set(-5, -5, 5);
    scene.add(pointLight2);
    
    // Create floating geometric model
    createFloatingModel();
    
    // Animation loop
    animateThreeJS();
    
    // Resize handler
    window.addEventListener('resize', onWindowResize);
}

function createFloatingModel() {
    const geometry = new THREE.TorusKnotGeometry(1, 0.4, 100, 16);
    const material = new THREE.MeshPhysicalMaterial({
        color: 0x00f5ff,
        metalness: 0.8,
        roughness: 0.2,
        clearcoat: 1,
        clearcoatRoughness: 0.1,
        transmission: 0.9
    });
    
    model = new THREE.Mesh(geometry, material);
    model.castShadow = true;
    model.receiveShadow = true;
    scene.add(model);
}

function animateThreeJS() {
    requestAnimationFrame(animateThreeJS);
    
    // Rotate and float model
    model.rotation.x += 0.005;
    model.rotation.y += 0.01;
    model.rotation.z += 0.003;
    model.position.y = Math.sin(Date.now() * 0.001) * 0.2;
    
    controls.update();
    renderer.render(scene, camera);
}

function onWindowResize() {
    const container = document.getElementById('three-container');
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
}

// 3. PRODUCTS & CART FUNCTIONALITY
function loadProducts() {
    console.log('🚀 Loading products...');
    
    
    const products = [
        {id:1,name:"Cyber Watch Pro",price:299,image:"https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop",category:"Wearables"},
        {id:2,name:"Quantum Headphones",price:199,image:"https://images.unsplash.com/photo-1614007958369-293a40a8e17a?w=400&h=400&fit=crop",category:"Audio"},
        {id:3,name:"Neo Laptop Ultra",price:1299,image:"https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=400&fit=crop",category:"Electronics"},
        {id:4,name:"Smart Glasses AR",price:399,image:"https://images.unsplash.com/photo-1570549717069-c21e7a0f7e25?w=400&h=400&fit=crop",category:"Wearables"},
        {id:5,name:"Gaming Controller Pro",price:89,image:"https://images.unsplash.com/photo-1592924551709-7c3e6f92627e?w=400&h=400&fit=crop",category:"Gaming"},
        {id:6,name:"Wireless Speaker X1",price:149,image:"https://images.unsplash.com/photo-1617189037350-268f8b6a12f1?w=400&h=400&fit=crop",category:"Audio"}
    ];
    
    setTimeout(() => {
        displayProducts(products);
        console.log('✅ 6 Products displayed!');
    }, 100);  // Delay for DOM ready
}

function getSampleProducts() {
    return [
        {
            id: 1,
            name: "Cyber Watch Pro",
            price: 299,
            image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop",
            category: "Wearables"
        },
        {
            id: 2,
            name: "Quantum Headphones",
            price: 199,
            image: "https://images.unsplash.com/photo-1614007958369-293a40a8e17a?w=400&h=400&fit=crop",
            category: "Audio"
        },
        {
            id: 3,
            name: "Neo Laptop",
            price: 1299,
            image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=400&fit=crop",
            category: "Electronics"
        },
        {
            id: 4,
            name: "Smart Glasses",
            price: 399,
            image: "https://images.unsplash.com/photo-1570549717069-c21e7a0f7e25?w=400&h=400&fit=crop",
            category: "Wearables"
        },
        {
            id: 5,
            name: "Gaming Controller",
            price: 89,
            image: "https://images.unsplash.com/photo-1592924551709-7c3e6f92627e?w=400&h=400&fit=crop",
            category: "Gaming"
        },
        {
            id: 6,
            name: "Wireless Speaker",
            price: 149,
            image: "https://images.unsplash.com/photo-1617189037350-268f8b6a12f1?w=400&h=400&fit=crop",
            category: "Audio"
        }
    ];
}

function displayProducts(products) {
    const grid = document.getElementById('productsGrid');
    if (!grid) {
        console.error('❌ productsGrid not found!');
        return;
    }
    
    grid.innerHTML = '';  // Clear first
    
    products.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.dataset.id = product.id;
        card.innerHTML = `
            <img src="${product.image}" alt="${product.name}" class="product-image" 
                 onerror="this.src='https://via.placeholder.com/350x250/1a1a2e/00f5ff?text=${product.name}'">
            <h3 class="product-title">${product.name}</h3>
            <div class="product-price">$${product.price.toLocaleString()}</div>
            <button class="add-to-cart" onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price}, '${product.image}')">
                <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
        `;
        grid.appendChild(card);
    });
    
    console.log('🎉 Products rendered:', products.length);
}

function addToCart(id, name, price, image) {
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ id, name, price, image, quantity: 1 });
    }
    
    updateCart();
    showNotification(`${name} added to cart!`);
    
    // Animate button
    const button = event.target.closest('.add-to-cart');
    gsap.to(button, {
        scale: 0.95,
        duration: 0.1,
        yoyo: true,
        repeat: 1
    });
}

function updateCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    updateCartUI();
}

function updateCartUI() {
    document.getElementById('cartCount').textContent = cartCount;
}

function setupEventListeners() {
    // Cart icon click
    document.getElementById('cartIcon').addEventListener('click', toggleCartModal);
    
    // Close cart
    document.getElementById('closeCart').addEventListener('click', toggleCartModal);
    
    // Cart modal backdrop
    document.getElementById('cartModal').addEventListener('click', function(e) {
        if (e.target === this) toggleCartModal();
    });
}

function toggleCartModal() {
    const modal = document.getElementById('cartModal');
    modal.classList.toggle('active');
    
    if (modal.classList.contains('active')) {
        displayCartItems();
    }
}

function displayCartItems() {
    const cartItemsContainer = document.getElementById('cartItems');
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p style="text-align: center; padding: 2rem; opacity: 0.7;">Your cart is empty</p>';
    } else {
        cartItemsContainer.innerHTML = cart.map(item => `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}">
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <div class="price">$${item.price}</div>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                        <button class="quantity-btn" style="margin-left: 1rem; background: #ff006e;" onclick="removeFromCart(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    document.getElementById('cartTotal').textContent = total.toFixed(2);
}

function updateQuantity(id, change) {
    const item = cart.find(item => item.id === id);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            cart = cart.filter(cartItem => cartItem.id !== id);
        }
        updateCart();
        displayCartItems();
    }
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    updateCart();
    displayCartItems();
}

function scrollToProducts() {
    document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
}

function showNotification(message) {
    // Floating notification
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: linear-gradient(45deg, #00f5ff, #ff00ff);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        z-index: 3000;
        transform: translateX(400px);
        box-shadow: 0 10px 30px rgba(0, 245, 255, 0.4);
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    gsap.to(notification, {
        x: 0,
        duration: 0.5,
        ease: "back.out(1.7)"
    });
    
    gsap.to(notification, {
        x: 400,
        duration: 0.5,
        delay: 2,
        ease: "power2.in"
    });
    
    setTimeout(() => document.body.removeChild(notification), 3000);
}

// 4. SCROLL ANIMATIONS with GSAP
function initScrollAnimations() {
    gsap.registerPlugin(ScrollTrigger);
    
    // Hero title animation
    gsap.from(".hero-title", {
        opacity: 0,
        y: 100,
        duration: 1.2,
        stagger: 0.1,
        ease: "back.out(1.7)"
    });
    
    gsap.from(".hero-subtitle", {
        opacity: 0,
        y: 50,
        duration: 1,
        delay: 0.3
    });
    
    // Products stagger animation
    ScrollTrigger.create({
        trigger: "#products",
        start: "top 80%",
        onEnter: () => {
            gsap.from(".product-card", {
                opacity: 0,
                y: 100,
                scale: 0.8,
                duration: 0.8,
                stagger: 0.1,
                ease: "back.out(1.7)"
            });
        }
    });
    
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 100) {
            navbar.style.background = 'rgba(15, 15, 35, 0.98)';
        } else {
            navbar.style.background = 'rgba(15, 15, 35, 0.95)';
        }
    });
}

// Smooth scrolling for nav links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
<div align="center">

```
███╗   ██╗███████╗ ██████╗ ███████╗████████╗ ██████╗ ██████╗ ███████╗
████╗  ██║██╔════╝██╔═══██╗██╔════╝╚══██╔══╝██╔═══██╗██╔══██╗██╔════╝
██╔██╗ ██║█████╗  ██║   ██║███████╗   ██║   ██║   ██║██████╔╝█████╗  
██║╚██╗██║██╔══╝  ██║   ██║╚════██║   ██║   ██║   ██║██╔══██╗██╔══╝  
██║ ╚████║███████╗╚██████╔╝███████║   ██║   ╚██████╔╝██║  ██║███████╗
╚═╝  ╚═══╝╚══════╝ ╚═════╝ ╚══════╝   ╚═╝    ╚═════╝ ╚═╝  ╚═╝╚══════╝
```

### ✦ The Future of Shopping — Built Today ✦

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![Three.js](https://img.shields.io/badge/Three.js-000000?style=for-the-badge&logo=three.js&logoColor=white)](https://threejs.org)
[![GSAP](https://img.shields.io/badge/GSAP-88CE02?style=for-the-badge&logo=greensock&logoColor=black)](https://greensock.com/gsap)

</div>

---

## 🛍️ What is NeoStore?

**NeoStore** is a visually immersive, premium e-commerce web application that blends cutting-edge web technologies with a sleek, futuristic shopping experience. Featuring real-time 3D visuals powered by **Three.js**, buttery-smooth animations via **GSAP**, and a robust **PHP** backend — NeoStore isn't just a store, it's an *experience*.

---

## ✨ Features

| Feature | Description |
|---|---|
| 🌌 **3D Hero Section** | Interactive Three.js canvas with floating particles and 3D elements |
| 🛒 **Live Shopping Cart** | Dynamic cart modal with real-time item count and total |
| 📦 **Product Catalog** | Dynamically loaded product grid from the database |
| 💳 **Checkout Flow** | Dedicated cart & checkout page (`cart.php`) |
| 🎨 **Smooth Animations** | GSAP + ScrollTrigger for cinematic scroll-based transitions |
| 📱 **Fully Responsive** | Mobile-first design with hamburger navigation |
| ⚡ **Fast & Lightweight** | Minimal dependencies, optimised asset loading |

---

## 🗂️ Project Structure

```
ecommerce/
│
├── 📄 index.php        # Home page — Hero, navbar, product grid
├── 📄 products.php     # Products listing & detail view
├── 📄 cart.php         # Cart summary & checkout
├── 📄 config.php       # Database configuration & shared logic
│
├── 🎨 style.css        # All custom styles (glassmorphism, gradients, layout)
└── ⚙️  script.js       # Three.js scene, GSAP animations, cart logic
```

---

## 🚀 Getting Started

### Prerequisites

- PHP `7.4+`
- A local server environment: [XAMPP](https://www.apachefriends.org/) / [WAMP](https://www.wampserver.com/) / [Laragon](https://laragon.org/)
- MySQL / MariaDB

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/Ammarahmed-git/ecommerce.git

# 2. Move it into your server's web root
#    e.g. for XAMPP:
cp -r ecommerce/ /xampp/htdocs/

# 3. Import the database (if a .sql file is provided)
#    Then update config.php with your DB credentials

# 4. Start Apache & MySQL from your control panel

# 5. Open in browser
http://localhost/ecommerce/
```

---

## 🔧 Configuration

Open `config.php` and update your database credentials:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
?>
```

---

## 🧰 Tech Stack

```
Backend      →  PHP (server-side rendering, DB queries)
Database     →  MySQL / MariaDB
Frontend     →  HTML5, CSS3, Vanilla JavaScript
3D Graphics  →  Three.js (r128)
Animations   →  GSAP 3 + ScrollTrigger
Icons        →  Font Awesome 6
Typography   →  Google Fonts — Inter
```

---

## 📸 Pages at a Glance

```
🏠  /index.php    →  Landing page with 3D hero + featured products
📦  /products.php →  Full product catalog
🛒  /cart.php     →  Shopping cart & checkout
```

---

## 🛣️ Roadmap

- [ ] User authentication (register / login)
- [ ] Product search & filtering
- [ ] Admin dashboard for inventory management
- [ ] Payment gateway integration (Stripe / PayPal)
- [ ] Order history & tracking
- [ ] Wishlist functionality

---

## 🤝 Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request.

```bash
# Fork → Clone → Create a branch → Make changes → PR
git checkout -b feature/your-feature-name
git commit -m "✨ Add your feature"
git push origin feature/your-feature-name
```

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

<div align="center">

Made with ❤️ by [Ammar Ahmed](https://github.com/Ammarahmed-git)

⭐ **Star this repo** if you found it useful!

</div>

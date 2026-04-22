<?php
require_once 'config.php';

header('Content-Type: application/json');

// Sample products data (replace with your database)
$sampleProducts = [
    [
        'id' => 1,
        'name' => 'Cyber Watch Pro',
        'price' => 299,
        'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop',
        'category' => 'Wearables'
    ],
    [
        'id' => 2,
        'name' => 'Quantum Headphones',
        'price' => 199,
        'image' => 'https://images.unsplash.com/photo-1614007958369-293a40a8e17a?w=400&h=400&fit=crop',
        'category' => 'Audio'
    ],
    [
        'id' => 3,
        'name' => 'Neo Laptop Ultra',
        'price' => 1299,
        'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=400&fit=crop',
        'category' => 'Electronics'
    ],
    [
        'id' => 4,
        'name' => 'Smart Glasses AR',
        'price' => 399,
        'image' => 'https://images.unsplash.com/photo-1570549717069-c21e7a0f7e25?w=400&h=400&fit=crop',
        'category' => 'Wearables'
    ],
    [
        'id' => 5,
        'name' => 'Gaming Controller Pro',
        'price' => 89,
        'image' => 'https://images.unsplash.com/photo-1592924551709-7c3e6f92627e?w=400&h=400&fit=crop',
        'category' => 'Gaming'
    ],
    [
        'id' => 6,
        'name' => 'Wireless Speaker X1',
        'price' => 149,
        'image' => 'https://images.unsplash.com/photo-1617189037350-268f8b6a12f1?w=400&h=400&fit=crop',
        'category' => 'Audio'
    ]
];

echo json_encode($sampleProducts);
?>
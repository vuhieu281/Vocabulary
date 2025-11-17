<?php
require_once 'config/database.php';

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100),
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE
)";

if (mysqli_query($conn, $sql)) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn) . "<br>";
}

// Create services table
$sql = "CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    description TEXT,
    contact_info TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)";

if (mysqli_query($conn, $sql)) {
    echo "Services table created successfully<br>";
} else {
    echo "Error creating services table: " . mysqli_error($conn) . "<br>";
}

// Create reviews table
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if (mysqli_query($conn, $sql)) {
    echo "Reviews table created successfully<br>";
} else {
    echo "Error creating reviews table: " . mysqli_error($conn) . "<br>";
}

// Create review_images table
$sql = "CREATE TABLE IF NOT EXISTS review_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(id)
)";

if (mysqli_query($conn, $sql)) {
    echo "Review images table created successfully<br>";
} else {
    echo "Error creating review images table: " . mysqli_error($conn) . "<br>";
}

// Create discussions table
$sql = "CREATE TABLE IF NOT EXISTS discussions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if (mysqli_query($conn, $sql)) {
    echo "Discussions table created successfully<br>";
} else {
    echo "Error creating discussions table: " . mysqli_error($conn) . "<br>";
}

// Create discussion_comments table
$sql = "CREATE TABLE IF NOT EXISTS discussion_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    discussion_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (discussion_id) REFERENCES discussions(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if (mysqli_query($conn, $sql)) {
    echo "Discussion comments table created successfully<br>";
} else {
    echo "Error creating discussion comments table: " . mysqli_error($conn) . "<br>";
}

// Create admin user with direct SQL query
$sql = "INSERT INTO users (username, password, email, full_name, is_admin) 
        SELECT 'admin', '" . password_hash("admin123", PASSWORD_DEFAULT) . "', 'admin@techbook.com', 'System Administrator', TRUE 
        WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin')";

if (mysqli_query($conn, $sql)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo "Admin user created successfully<br>";
    } else {
        echo "Admin user already exists<br>";
    }
} else {
    echo "Error creating admin user: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
echo "Database initialization completed.";
?> 
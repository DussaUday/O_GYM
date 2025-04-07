<?php
// Database configuration
$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$username = "2chHuJJjXN4T7Z9.root";
$password = "65J2Srkj49NBfmuX";
$database = "test";
$port = 4000

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password, $port);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Function to create tables dynamically
    function createTables($conn) {
        // Define table structures
        $tables = [
            'ogym' => "CREATE TABLE IF NOT EXISTS ogym (
                id INT AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                gen VARCHAR(10) NOT NULL,
                dob DATE NOT NULL,
                height INT NOT NULL,
                weight INT NOT NULL,
                phone BIGINT NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            
            // Add more tables as needed
            'users' => "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        ];
        
        // Execute table creation
        foreach ($tables as $tableName => $sql) {
            try {
                $conn->exec($sql);
                error_log("Table $tableName created or already exists");
            } catch (PDOException $e) {
                error_log("Error creating table $tableName: " . $e->getMessage());
            }
        }
    }
    
    // Call the function to create tables
    createTables($conn);
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

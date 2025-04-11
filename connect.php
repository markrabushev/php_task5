<?php
$db_host = 'localhost';
$db_name = 'feedback_form';
$db_user = 'root';
$db_pass = '';

function initDB() {
    global $db_host, $db_name, $db_user, $db_pass;
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass);
        
        if ($conn->connect_error) {
            throw new Exception("Ошибка: " . $conn->connect_error);
        }
        
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if (!$conn->query($sql)) {
            throw new Exception("Ошибка: " . $conn->error);
        }
            
        $conn->select_db($db_name);
            
        $sql = "CREATE TABLE IF NOT EXISTS feedback (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            description TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
            
        if (!$conn->query($sql)) {
            throw new Exception("Ошибка: " . $conn->error);
        }
            
        $sql = "CREATE TABLE IF NOT EXISTS feedback_files (
            id INT AUTO_INCREMENT PRIMARY KEY,
            feedback_id INT NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            original_name VARCHAR(255) NOT NULL,
            FOREIGN KEY (feedback_id) REFERENCES feedback(id) ON DELETE CASCADE)";
            
        if (!$conn->query($sql)) {
            throw new Exception("Ошибка: " . $conn->error);
        }

        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL)";
            
        if (!$conn->query($sql)) {
            throw new Exception("Ошибка: " . $conn->error);
        }
            
        $conn->close();

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ошибка инициализации базы данных']);
        exit;
    }
}
?>
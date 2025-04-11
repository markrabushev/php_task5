<?php
require 'connect.php';
initDB();

$action = '';

if(isset($_GET['action'])){
    $action = $_GET['action'];
} else {
    echo json_encode(['success' => false, 'message' => 'В url не указан action']);
    exit;
}

$email = $password = '';

if (!empty($_POST['email'])) {
    $email = $_POST['email'];
} else {
    echo json_encode(['success' => false, 'message' => 'email is empty']);
    exit;
}   
if (!empty($_POST['password'])) {
    $password = $_POST['password'];
} else {
    echo json_encode(['success' => false, 'message' => 'password is empty']);
    exit;
}

try {
    if ($action === 'register') {
        
        $errors = [];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            throw new Exception("Ошибка: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $check_result = $conn->query($sql);
                
        if ($check_result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Этот email уже зарегистрирован']);
        } else {
            $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";
            if ($conn->query($sql)) {
                echo json_encode(['success' => true, 'redirect' => 'feedback.php']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка при регистрации: ' . $conn->error]);
            }
        }

    } elseif ($action === 'login') {
        $errors = [];

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            throw new Exception("Ошибка: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
                
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
                    
            if (password_verify($password, $user['password'])) {
                echo json_encode(['success' => true, 'redirect' => 'feedback.php']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Неверный email или пароль']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Неверный email или пароль']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'action is wrong']);
        exit;
    }

} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

?>
<?php
session_start();
require_once 'db_conn.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password';
    } else {
        $stmt = $conn->prepare("SELECT id, firstname, lastname, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            max-width: 480px; 
            width: 100%;
            margin: 0 auto;
            padding: 50px 45px;
            background: white;
            border-radius: 16px; 
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        
        .login-container h1 {
            color: #2a5298;
            margin-bottom: 40px !important; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px; 
            font-size: 36px !important; 
            font-weight: 700 !important; 
        }
        
        .message {
            padding: 16px 20px !important; 
            border-radius: 8px;
            margin: 0 0 25px 0 !important; 
            font-weight: 500;
            font-size: 16px !important; 
            text-align: left;
        }
        
        .form-group {
            margin-bottom: 28px !important; 
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 12px !important; 
            font-weight: 600 !important; 
            color: #444;
            font-size: 16px !important; 
        }
        
        .form-group input {
            width: 100%;
            padding: 16px 20px !important; 
            border: 2px solid #ddd; 
            border-radius: 10px; 
            font-size: 17px !important; 
            transition: all 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            border-color: #2a5298;
            outline: none;
            box-shadow: 0 0 0 4px rgba(42, 82, 152, 0.15); 
        }
        
        .btn-login {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            padding: 18px 35px !important; 
            border-radius: 10px; 
            cursor: pointer;
            font-size: 18px !important; /
            font-weight: 600 !important; 
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(42, 82, 152, 0.4);
        }
        
        .credentials {
            margin-top: 30px; 
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #2a5298;
            font-size: 16px !important; 
        }
        
        .credentials strong {
            display: block;
            margin-bottom: 12px;
            color: #2a5298;
            font-size: 17px;
        }
        
        .credentials div {
            text-align: left;
            line-height: 1.8;
        }
        
        .demo-email {
            color: #1e3c72;
            font-weight: 500;
        }
        
        .demo-password {
            color: #28a745;
            font-weight: 500;
        }

        ::placeholder {
            font-size: 15px;
            color: #999;
        }
        
        @media (max-width: 500px) {
            .login-container {
                padding: 40px 30px;
            }
            
            .login-container h1 {
                font-size: 30px !important;
            }
            
            .btn-login {
                padding: 16px 25px !important;
                font-size: 16px !important;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-container {
            animation: fadeIn 0.5s ease-out;
        }
        
        .login-container h1 span {
            font-size: 40px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1><span>🐬</span> Dolphin CRM</h1>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required 
                       placeholder="admin@project2.com" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="password123"
                       autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
        
        <div class="credentials">
            <strong>Demo Credentials:</strong>
            <div>
                <span class="demo-email">Email: admin@project2.com</span><br>
                <span class="demo-password">Password: password123</span>
            </div>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('email').focus();
        
        const form = document.querySelector('form');
        form.style.opacity = '0';
        form.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
            form.style.transition = 'all 0.4s ease-out';
            form.style.opacity = '1';
            form.style.transform = 'translateY(0)';
        }, 300);
    });
    </script>
</body>
</html>
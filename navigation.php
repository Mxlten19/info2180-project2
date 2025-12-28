<?php
session_start();

// Redirect to login if not logged in (except login page)
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['user_id']) && $current_page != 'login.php') {
    header('Location: login.php');
    exit;
}

$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>NAVBAR</title>
    <style>
        nav {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        nav ul {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 30px;
            margin: 0;
            padding: 0;
        }
        nav li {
            display: inline;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        nav a:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .welcome {
            margin-left: auto;
            font-size: 0.9em;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <?php if ($is_admin): ?>
                <li><a href="newcontact.php">New User</a></li>
            <?php endif; ?>
            <li><a href="createcontact.php">New Contact</a></li>
            <?php if ($is_admin): ?>
                <li><a href="users.php">Users</a></li>
            <?php endif; ?>
            <li class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?> (<?php echo htmlspecialchars($_SESSION['user_role'] ?? 'Member'); ?>)</li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
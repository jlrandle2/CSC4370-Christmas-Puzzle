<?php

require_once 'includes/auth_functions.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login_user($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Christmas Puzzle</title>
    <link rel="stylesheet" href="game.css">
</head>
<body>
    <div class="game-container">
        <h1>Santa's Login </h1>
        
        <div class="auth-box">
            <?php if ($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div class="input-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="action-btn">Start Playing</button>
            </form>
            
            <a href="register.php" class="auth-link">New Elf? Register Here</a>
        </div>
    </div>
</body>
</html>
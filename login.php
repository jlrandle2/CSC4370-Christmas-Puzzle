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
        <h1> Santa's Login </h1>
        
        <div style="background: rgba(0,0,0,0.5); padding: 20px; border-radius: 10px;">
            <?php if ($error): ?>
                <p style="color: #ffcccc; background: #D42426; padding: 10px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div style="margin-bottom: 15px;">
                    <label style="display:block;">Username:</label>
                    <input type="text" name="username" required style="padding: 5px; width: 200px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display:block;">Password:</label>
                    <input type="password" name="password" required style="padding: 5px; width: 200px;">
                </div>

                <button type="submit" class="tile" style="width: 200px; height: 50px; margin: 0 auto; font-size: 18px;">Start Playing</button>
            </form>
            
            <p style="margin-top: 20px;">New Elf? <a href="register.php" style="color: #fff;">Register Here</a></p>
        </div>
    </div>
</body>
</html>
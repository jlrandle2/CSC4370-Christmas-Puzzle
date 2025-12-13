<?php

require_once 'includes/auth_functions.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        $result = register_user($username, $password);
        if ($result === true) {
            
            header('Location: index.php?registered=1');
            exit();
        } else {
            $message = $result; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for Christmas Puzzle</title>
    <link rel="stylesheet" href="game.css">
</head>
<body>
    <div class="game-container">
        <h1> Elf Registration </h1>
        
        <div style="background: rgba(0,0,0,0.5); padding: 20px; border-radius: 10px;">
            <?php if ($message): ?>
                <p style="color: #ffcccc; background: #D42426; padding: 10px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="post" action="register.php">
                <div style="margin-bottom: 15px;">
                    <label style="display:block;">Username:</label>
                    <input type="text" name="username" required style="padding: 5px; width: 200px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display:block;">Password:</label>
                    <input type="password" name="password" required style="padding: 5px; width: 200px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display:block;">Confirm Password:</label>
                    <input type="password" name="confirm_password" required style="padding: 5px; width: 200px;">
                </div>

                <button type="submit" class="tile" style="width: 200px; height: 50px; margin: 0 auto; font-size: 18px;">Join Workshop</button>
            </form>
            
            <p style="margin-top: 20px;">Already an Elf? <a href="login.php" style="color: #fff;">Log In Here</a></p>
        </div>
    </div>
</body>
</html>
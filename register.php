<?php
// register.php
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
    <title>Register - Christmas Puzzle</title>
    <link rel="stylesheet" href="game.css">
</head>

<body>
    <div class="game-container">
        <h1> Elf Registration </h1>

        <div class="auth-box">
            <?php if ($message): ?>
                <div class="error-msg"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="post" action="register.php">
                <div class="input-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" class="action-btn">Join Workshop</button>
            </form>

            <a href="login.php" class="auth-link">Already an Elf? Log In Here</a>
        </div>
    </div>
</body>

</html>
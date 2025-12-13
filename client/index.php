<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas 15 Puzzle</title>
    <link rel="stylesheet" href="game.css">
</head>
<body>

<h1> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! </h1>
    
    <div class="game-container">
        <div id="puzzle-board">
            <div class="tile">1</div>
            <div class="tile">2</div>
            <div class="tile">3</div>
            <div class="tile">4</div>
            <div class="tile">5</div>
            <div class="tile">6</div>
            <div class="tile">7</div>
            <div class="tile">8</div>
            <div class="tile">9</div>
            <div class="tile">10</div>
            <div class="tile">11</div>
            <div class="tile">12</div>
            <div class="tile">13</div>
            <div class="tile">14</div>
            <div class="tile">15</div>
            <div class="tile empty-tile"></div>
        </div>
    </div>
    <div style="text-align: right; margin-right: 20px;">
    <a href="logout.php" style="color: #ffcccc; text-decoration: none; border: 1px solid white; padding: 5px 10px; border-radius: 5px;">Logout</a>
</div>

    <p>Moves: <span id="move-count">0</span> | Time: <span id="timer">00:00</span></p>
    <script src="game.js"></script>

</body>
</html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Christmas Puzzle</title>

    <link rel="stylesheet" href="game.css">
</head>

<body>
    <div class="header-controls">
        <a href="profile.php" class="logout-btn">Profile</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <h1>🎄 Christmas Puzzle 🎄</h1>


    <div class="game-container">
        <div id="puzzle-board"></div>

        <p style="margin-top: 15px; font-size: 18px;">
            ⏱ Time: <span id="timer">0:00</span>
        </p>

        <div style="margin-top: 20px;">
            <div style="display: flex; justify-content: center; gap: 12px;">
                <button class="action-btn" onclick="shuffleBoard()">Shuffle</button>
                <button class="action-btn" onclick="startTimer()">Start</button>
            </div>

            <div style="display: flex; justify-content: center; gap: 12px; margin-top: 12px;">
                <button class="action-btn" onclick="helpMove()">Help</button>
                <div class="action-btn" style="cursor: default;">
                    Helps used: <span id="helps-used">0</span>
                </div>
            </div>

            <div class="size-selector">
                <button class="action-btn size-btn" data-size="3">3×3</button>
                <button class="action-btn size-btn active" data-size="4">4×4</button>
                <button class="action-btn size-btn" data-size="6">6×6</button>
                <button class="action-btn size-btn" data-size="8">8×8</button>
                <button class="action-btn size-btn" data-size="10">10×10</button>
            </div>
        </div>
    </div>

    <audio id="bgm" src="assets/bgm.mp3" preload="auto" loop></audio>
    <audio id="move-sfx" src="assets/move.m4a" preload="auto"></audio>

    <div id="win-modal" class="modal hidden">
        <div class="modal-content">
            <h2>🎉 Congratulations! 🎄</h2>

            <p><strong>Time + Help:</strong> <span id="win-time"></span></p>
            <p><strong>Helps time added:</strong> <span id="win-help-time"></span> seconds</p>
            <p><strong>Helps used:</strong> <span id="win-helps"></span></p>
            <p><strong>Total moves:</strong> <span id="win-moves"></span></p>

            <button class="action-btn" onclick="closeWinModal()">Close</button>
        </div>
    </div>

    
<div style="display: flex; gap: 10px; margin-top: 10px; justify-content: center;">
    <button class="action-btn" style="width: auto; padding: 8px 15px;" onclick="usePowerup('freeze')">❄️ Freeze</button>
    <button class="action-btn" style="width: auto; padding: 8px 15px;" onclick="usePowerup('star')">⭐ Star</button>
    <button class="action-btn" style="width: auto; padding: 8px 15px;" onclick="usePowerup('bell')">🔔 Bell</button>
</div>

    <script src="game.js"></script>

</body>

</html>
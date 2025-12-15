<?php
session_start();
require_once "includes/auth_functions.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Unknown';

$conn = get_db_connection();

$stmt = $conn->prepare(
    "SELECT 
        COUNT(*) AS games_won,
        AVG(time_taken) AS avg_time,
        AVG(moves + helps_used) AS avg_total_moves,
        MIN(time_taken) AS best_time
     FROM game_stats
     WHERE user_id = ?"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$career = $stmt->get_result()->fetch_assoc();

$lastFiveStmt = $conn->prepare(
    "SELECT time_taken, helps_used, moves, played_at
     FROM game_stats
     WHERE user_id = ?
     ORDER BY played_at DESC
     LIMIT 5"
);
$lastFiveStmt->bind_param("i", $userId);
$lastFiveStmt->execute();
$lastFive = $lastFiveStmt->get_result();

$lastGameStmt = $conn->prepare(
    "SELECT time_taken, helps_used, moves
     FROM game_stats
     WHERE user_id = ?
     ORDER BY played_at DESC
     LIMIT 1"
);
$lastGameStmt->bind_param("i", $userId);
$lastGameStmt->execute();
$lastGame = $lastGameStmt->get_result()->fetch_assoc();

function format_time(?int $seconds): string
{
    if ($seconds === null)
        return "N/A";
    $m = intdiv($seconds, 60);
    $s = $seconds % 60;
    return "{$m}:" . str_pad((string) $s, 2, "0", STR_PAD_LEFT);
}


$avgTimeSeconds = $career['avg_time'] !== null
    ? (int) round($career['avg_time'])
    : null;

$bestTimeSeconds = $career['best_time'] !== null
    ? (int) $career['best_time']
    : null;

$avgTotalMoves = $career['avg_total_moves'] !== null
    ? round($career['avg_total_moves'], 2)
    : "N/A";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="game.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <div class="header-controls">
        <a href="index.php" class="logout-btn">Game</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <h1>👤 Profile: <?php echo htmlspecialchars($username); ?></h1>

    <div class="auth-box">
        <h2>Career Stats</h2>
        <p><strong>Games Won:</strong> <?php echo $career['games_won'] ?? 0; ?></p>
        <p><strong>AVG Time per Game:</strong> <?php echo format_time($avgTimeSeconds); ?></p>
        <p><strong>AVG Total Moves per Game:</strong> <?php echo $avgTotalMoves; ?></p>
        <p><strong>Best Time:</strong> <?php echo format_time($bestTimeSeconds); ?></p>

        <hr>

        <h2>Last 5 Games</h2>

        <?php if ($lastFive->num_rows === 0): ?>
            <p>No games played yet.</p>
        <?php else: ?>
            <table style="width:100%; text-align:left;">
                <tr>
                    <th>Time</th>
                    <th>Helps</th>
                    <th>Total Moves</th>
                    <th>Date</th>
                </tr>
                <?php while ($g = $lastFive->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo format_time((int) $g['time_taken']); ?></td>
                        <td><?php echo $g['helps_used']; ?></td>
                        <td><?php echo $g['moves'] + $g['helps_used']; ?></td>
                        <td><?php echo $g['played_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>

        <hr>

        <h2>Last Game</h2>

        <?php if (!$lastGame): ?>
            <p>No games played yet.</p>
        <?php else: ?>
            <p><strong>Time:</strong> <?php echo format_time((int) $lastGame['time_taken']); ?></p>
            <p><strong>Helps:</strong> <?php echo $lastGame['helps_used']; ?></p>
            <p><strong>Total Moves:</strong> <?php echo $lastGame['moves'] + $lastGame['helps_used']; ?></p>
        <?php endif; ?>

        <h2> Gift Collection</h2>
<?php
    $achStmt = $conn->prepare("SELECT achievement_name FROM achievements WHERE user_id = ?");
    $achStmt->bind_param("i", $userId);
    $achStmt->execute();
    $userAchievements = $achStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $unlockedNames = array_column($userAchievements, 'achievement_name');
    
    $allAchievements = [
        'speed_demon' => ['emoji' => '⚡', 'name' => 'Speed Demon', 'desc' => 'Complete puzzle under 2 min'],
        'persistent' => ['emoji' => '🎄', 'name' => 'Persistent Elf', 'desc' => 'Play 10 games'],
        'perfect_solve' => ['emoji' => '⭐', 'name' => 'Perfect Solve', 'desc' => 'Win without help'],
        'marathon' => ['emoji' => '🏆', 'name' => 'Marathon Master', 'desc' => 'Play 50 games']
    ];
    
    foreach ($allAchievements as $key => $ach) {
        $unlocked = in_array($key, $unlockedNames);
        $opacity = $unlocked ? '1' : '0.3';
        echo "<div style='display:inline-block; margin:10px; opacity:{$opacity};'>";
        echo "<div style='font-size:40px;'>{$ach['emoji']}</div>";
        echo "<div style='font-size:12px;'>{$ach['name']}</div>";
        echo "</div>";
    }
?>

    </div>

</body>

</html>
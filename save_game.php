<?php
session_start();
require_once "includes/auth_functions.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['time_taken'], $data['helps_used'], $data['moves'])) {
    http_response_code(400);
    exit();
}

$userId = $_SESSION['user_id'];
$timeTaken = (int) $data['time_taken'];
$helpsUsed = (int) $data['helps_used'];
$moves = (int) $data['moves'];

$conn = get_db_connection();

$stmt = $conn->prepare(
    "INSERT INTO game_stats (user_id, time_taken, helps_used, moves)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("iiii", $userId, $timeTaken, $helpsUsed, $moves);
$stmt->execute();

$stmt->close();
$conn->close();


$achievements = [];


if ($timeTaken < 120) {
    $achievements[] = 'speed_demon';
}


$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM game_stats WHERE user_id = ?");
$countStmt->bind_param("i", $userId);
$countStmt->execute();
$result = $countStmt->get_result()->fetch_assoc();

if ($result['total'] >= 10) {
    $achievements[] = 'persistent';
}

foreach ($achievements as $achievement) {
    $achStmt = $conn->prepare("INSERT IGNORE INTO achievements (user_id, achievement_name) VALUES (?, ?)");
    $achStmt->bind_param("is", $userId, $achievement);
    $achStmt->execute();
}

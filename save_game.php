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

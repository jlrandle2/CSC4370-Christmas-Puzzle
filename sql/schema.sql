CREATE DATABASE IF NOT EXISTS christmas_puzzle;
USE christmas_puzzle;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Game Stat
CREATE TABLE IF NOT EXISTS game_stats (
    stat_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    time_taken INT NOT NULL,
    helps_used INT NOT NULL DEFAULT 0,
    moves INT DEFAULT 0,
    difficulty VARCHAR(20) DEFAULT 'normal',
    played_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_game_stats_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

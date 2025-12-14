<?php

function get_db_connection()
{
    return new mysqli(
        "localhost",
        "root",
        "",
        "christmas_puzzle"
    );
}

function register_user($username, $password)
{
    $mysqli = get_db_connection();


    $check_stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $check_stmt->close();
        $mysqli->close();
        return "Username already taken";
    }
    $check_stmt->close();


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $mysqli->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $success = "Error: " . $mysqli->error;
    }

    $stmt->close();
    $mysqli->close();

    return $success;
}

function login_user($username, $password)
{
    $mysqli = get_db_connection();


    $stmt = $mysqli->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();


    $stmt->bind_result($id, $stored_hash);


    if ($stmt->fetch()) {

        if (password_verify($password, $stored_hash)) {

            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            return true;
        }
    }

    $stmt->close();
    $mysqli->close();
    return false;
}
?>
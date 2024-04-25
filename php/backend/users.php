<?php

// Set CORS headers
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Access-Control-Allow-Origin");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Content-Length: 0");
    header("HTTP/1.1 204 No Content");
    exit;
}

function connectDB() {
    $db_servername = getenv('MYSQL_SERVER_URL');
    $db_port = getenv('MYSQL_PORT');
    $db_username = getenv('MYSQL_USER');
    $db_password = getenv('MYSQL_PASSWORD');
    $db_name = getenv('MYSQL_DATABASE');

    $db = new mysqli($db_servername, $db_username, $db_password, $db_name, $db_port);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    return $db;
}

function addUser(string $username, string $email) {
    $db = connectDB();
    $sql = "INSERT INTO users (username, email) VALUES ('$username', '$email')";

    if ($db->query($sql) === TRUE) {
        $user_id = $db->insert_id;
        error_log("User added successfully. ID: " . $user_id);
    } else {
        error_log("Error: " . $sql . "<br>" . $conn->error);
    }

    $db->close();
}

function getUsers() {
    $db = connectDB();
    $result = $db->query("SELECT * FROM users");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    $users_json = json_encode($users);

    header("Content-Type: application/json");
    echo $users_json;

    error_log("User returned successfully. Users: " . count($users));

    $db->close();
}

function deleteUser(string $id) {
    error_log("2");

    $db = connectDB();
    $sql = "DELETE FROM users WHERE id = $id";

    error_log("3");

    if ($db->query($sql) === TRUE) {
        error_log("User deleted successfully. ID: " . $id);
    } else {
        error_log("Error: " . $sql . "<br>" . $conn->error);
    }

    $db->close();
}

$method = $_SERVER['REQUEST_METHOD'];
// $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
  case 'DELETE':
    error_log("1");
    if($_DELETE['id'])
        deleteUser($_DELETE['id']);
    break;
  case 'POST':
    if ($_POST['username'] && $_POST['email'])
        addUser($_POST['username'], $_POST['email']); 
    break;
  case 'GET':
    getUsers();
    break;
  default:
    console_log("Invalid request method");
    break;
}

?>
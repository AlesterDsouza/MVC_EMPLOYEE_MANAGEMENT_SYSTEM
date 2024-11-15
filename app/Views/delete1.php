<?php
session_start();
require_once __DIR__ . '/../Models/User1.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['delete1']) && is_numeric($_GET['delete1'])) {
    $userId = $_GET['delete1'];
    
    // Create a new instance of the User1 model and delete the user
    $userObj = new User1();
    $userObj->delete1($userId);
    
    // Redirect back to the user list page after deletion
    header("Location: user_list1.php");
    exit();
} else {
    echo "Invalid user ID!";
}
?>

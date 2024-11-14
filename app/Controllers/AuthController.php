<?php


// session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userObj;

    // public function __construct() {
    //     $this->userObj = new User();
    // }

    public function login($username, $password) {
        // Hardcoded credentials check
        $validUsername = "karuna";
        $validPassword = "karuna123";

        // Check if the username and password match the hardcoded credentials
        if ($username === $validUsername && $password === $validPassword) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            return true; // Login successful
        }

        // // Fetch all users from the database if the hardcoded credentials don't match
        // $users = $this->userObj->readAll();
        
        // // Loop through users to check if credentials match
        // foreach ($users as $user) {
        //     // If username matches and password matches (ensure passwords are compared directly without base64 encoding)
        //     if ($user['UserName'] === $username && $user['Password'] === $password) {
        //         $_SESSION['logged_in'] = true;
        //         $_SESSION['username'] = $username;
        //         return true; // Login successful
        //     }
        // }

        // If no match found, return false (invalid credentials)
        return false;
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../../public/index.php");
        exit();
    }
}
?>


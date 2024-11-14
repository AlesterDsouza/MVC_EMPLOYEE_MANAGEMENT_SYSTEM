<?php
// require_once '../app/Models/Admin.php';
// require_once __DIR__ . '/../Models/Admin.php';
require_once '/var/www/html/MVC_EMPLOYEE/app/Models/Admin.php';

class LoginController {
    public function showLoginForm($error = null) {

        require_once '/var/www/html/MVC_EMPLOYEE/app/Views/login.php';
    }

    public function login() {
        session_start();
        
        $username = $_POST['UserName'];
        $password = $_POST['Password'];
        $invalid = 0;

        if ($username === Admin::USERNAME && $password === Admin::PASSWORD) {
            $_SESSION['is_logged_in'] = true;
             header('Location: ../app/Views/user_list1.php');
            // require_once '../app/Views/user_list.php';
            exit();
        } else {
            $invalid = 1;
            $this->showLoginForm($invalid);
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ../public/index.php');
    }
}
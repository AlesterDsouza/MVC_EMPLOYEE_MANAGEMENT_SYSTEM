<?php

// require_once '../app/Models/User1.php';
require_once __DIR__ . '/../Models/User1.php';

$controller = new User1Controller();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'create1') {
    $controller->createUser($_POST);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete1') {
    $controller->deleteUser($_GET['id']);
}

if (isset($_GET['action']) && $_GET['action'] === 'edit1') {
 
    $controller->updateUser($_GET['id'],$_POST);
}

if (isset($_GET['action']) && $_GET['action'] === 'importCsv') {
    // echo 'Hello';
    // exit;
    $controller->importCsv();
}



class User1Controller {
    private $user1Model;

    public function __construct() {
        $this->user1Model = new User1();
        session_start();
    }

    public function listUsers() {
        // Redirect if not logged in
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header('Location: ../public/login.php');
            exit();
        }

        $search = $_GET['search'] ?? '';
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        
        $total_users = $this->user1Model->countUsers($search);
        $total_pages = ceil($total_users / $limit);
        $users = $this->user1Model->fetchUsers($search, $limit, $offset);
        
        require_once '../app/Views/user_list1.php';
    }
 
    public function createUser($userData) {
        // if ($this->user1Model->rollNoExists($userData['RollNo'])) {
        //     echo "Error: Roll number already exists!";
        //     return;
        // }
        if (isset($_POST['submit'])) {
            $firstName = $_POST['FirstName'];
            $lastName = $_POST['LastName'];
            $mobileNumber = $_POST['MobileNumber'];
            $email = $_POST['Email'];
            $address = $_POST['Address'];
        
            // Initialize profile picture variable
            $profilePic = null;
        
            // Move uploaded file to uploads directory
            if (!empty($_FILES['ProfilePic']['name'])) {
                $profilePic = time() . '_' . $_FILES['ProfilePic']['name'];
                if (!move_uploaded_file($_FILES['ProfilePic']['tmp_name'], 'uploads/' . $profilePic)) {
                    echo "<div class='alert alert-danger'>Failed to upload file.</div>";
                    die();
                }
            }
        
            $user = new User1();
        
            if ($user->mobileNumberExists($mobileNumber)) {
                echo "<div class='alert alert-danger'>Mobile number already exists. Please use a different one.</div>";
            } elseif ($user->emailExists($email)) {
                echo "<div class='alert alert-danger'>Email already exists. Please use a different one.</div>";
            } else {
                $user->create1($firstName, $lastName, $mobileNumber, $email, $address, $profilePic);
                echo "<div class='alert alert-success'>User created successfully!</div>";
                header('Location: User_list1.php');
                exit();
            }
        }
    }



    //     if ($this->user1Model-> mobileNumberExists($userData['MobileNumber'])) {
    //         echo "Error: Mobile number already exists!";
    //         return;
    //     }
    //     if ($this->user1Model->emailExists($userData['RollNo'])) {
    //         echo "Error: Email already exists!";
    //         return;
    //     }FirstName, LastName, MobileNumber, Email, Address, ProfilePic
    //     $this->user1Model->create1($userData['FirstName'], $userData['LastName'], $userData['MobileNumber'], $userData['Email'], $userData['Address'], $userData['MobileNumber'],);
        
    //     header('Location: ../Views/user_list1.php');
    // }



    public function importCsv() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
            $file = $_FILES['csv_file'];
    
            // Check file type
            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($fileType !== 'csv') {
                $_SESSION['error_message'] = "Invalid file format. Only CSV files are allowed.";
                header("Location: ../Views/user_list1.php");
                exit();
            }
    
           
            if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
                // $expectedHeaders = ['ID', 'FirstName', 'LastName', 'RollNo'];
                $expectedHeaders = ['FirstName', 'LastName', 'MobileNumber','Email', 'Address'];
                $headerRow = fgetcsv($handle);
    
           
                if ($headerRow !== $expectedHeaders) {
                    $_SESSION['error_message'] = "CSV headers do not match the required table format.";
                    fclose($handle);
                    header("Location: ../Views/user_list1.php");
                    exit();
                }
    
                // Process rows after the header
                while (($data = fgetcsv($handle)) !== false) {
                    // $id = $data[0];
                    // $firstName = $data[1];
                    // $lastName = $data[2];
                    // $rollNo = $data[3];

                    $firstName = $data[0];
                    $lastName = $data[1];
                    $mobileNumber = $data[2];
                    $email = $data[3];
                    $address = $data[4];
                    // $firstName, $lastName, $mobileNumber, $email, $address, $profilePic

                    // if ($this->user1Model->rollNoExists($rollNo)) {

                    //     $_SESSION['error_message'] = "Roll No exists.";
                    //     header("Location: ../Views/user_list1.php");
                       
                    //     // echo "<div class='alert alert-danger'>Error: Roll number $rollNo already exists! Please use a different one.</div>";
                    //     // continue;
                    //     exit();
                    //     }

                    if ($this->user1Model->mobileNumberExists($mobileNumber)) {
                        $_SESSION['error_message'] = "Mobile Number exists.";
                        header("Location: ../Views/user_list1.php");
                        exit();
                            //echo "<div class='alert alert-danger'>Mobile number already exists. Please use a different one.</div>";
                        } elseif ($this->user1Model->emailExists($email)) {
                            $_SESSION['error_message'] = "Email Address exists.";
                            header("Location: ../Views/user_list1.php");
                            exit();
                            //echo "<div class='alert alert-danger'>Email already exists. Please use a different one.</div>";
                        } 
                        //else {
                        //     $this->user1Model->create1($firstName, $lastName, $mobileNumber, $email, $address, $profilePic);
                        //     echo "<div class='alert alert-success'>User created successfully!</div>";
                        //     header('Location: user_list1.php');
                        //exit();
                        
                        
                        // $this->user1Model->create1($firstName, $lastName, $rollNo);
                   
                    $this->user1Model->create1($firstName, $lastName, $mobileNumber, $email, $address, $profilePic);
                    
                }
    
                fclose($handle);
                $_SESSION['success_message'] = "CSV file imported successfully.";
                header("Location: ../Views/user_list1.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Unable to open CSV file.";
                header("Location: ../Views/user_list1.php");
                exit();
            }
        
        }
    }

    public function editUser($id){
        $user = $this->user1Model->find1($id);
        require_once '../app/Views/edit1.php';
    }

public function updateUser($id, $userData) {


           // Check if the form is submitted
if (isset($_POST['submit'])) {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $mobileNumber = $_POST['MobileNumber'];
    $email = $_POST['Email'];
    $address = $_POST['Address'];

    // Handle profile picture upload
    $profilePic = $existingUser['ProfilePic']; // Keep existing profile pic if no new one is uploaded
    if ($_FILES['ProfilePic']['name']) {
        $profilePic = time() . '_' . $_FILES['ProfilePic']['name'];
        move_uploaded_file($_FILES['ProfilePic']['tmp_name'], 'uploads/' . $profilePic);
    }

    // Update user information
    if ($user->update1($id, $firstName, $lastName, $mobileNumber, $email, $address, $profilePic)) {
        header('Location: ../Views/user_list1.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update user.</div>";
    }
}


        // $this->user1Model->update1($id, $userData['FirstName'], $userData['LastName'], $userData['RollNo']);
        // header('Location: ../Views/user_list1.php');
        // // header('Location: ../app/Views/user_list1.php');
    }

    public function deleteUser($id) {
        $this->user1Model->delete1($id);
        header('Location: ../Views/user_list1.php');
    }
}
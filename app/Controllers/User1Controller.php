<?php

// require_once '../app/Models/User1.php';
require_once __DIR__ . '/../Models/User1.php';

$controller = new User1Controller();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'create1') {

   
    $controller->createUser($_POST);
}

if (isset($_GET['action']) && $_GET['action'] === 'edit1') {
 
    $controller->updateUser($_GET['id'],$_POST);
}

if (isset($_GET['action']) && $_GET['action'] === 'importCsv') {
    // echo 'Hello';
    // exit;
    $controller->importCsv();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'mass_delete') {

    // echo 'Hello';
    // exit;
    $controller->massDeleteUsers($_POST['ids'] ?? []);
}



class User1Controller {
    private $user1Model;

    public function __construct() {
        $this->user1Model = new User1();
        // session_start();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function listUsers() {
        // Redirect if not logged in
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
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

        require_once __DIR__ . '/../Views/user_list1.php';
    
    }
 
    public function createUser($userData) {

        if (isset($_POST['submit'])) {
            $firstName = $_POST['FirstName'];
            $lastName = $_POST['LastName'];
            $mobileNumber = $_POST['MobileNumber'];
            $email = $_POST['Email'];
            $address = $_POST['Address'];

            $profilePic = null;
        
            $uploadDir = __DIR__ . '/../../uploads/';

            // Move uploaded file to uploads directory
            if (!empty($_FILES['ProfilePic']['name'])) {
                $profilePic = time() . '_' . $_FILES['ProfilePic']['name'];
                if (!move_uploaded_file($_FILES['ProfilePic']['tmp_name'], $uploadDir . $profilePic)) {
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
                // echo 'Hello';
                // exit;
                $user->create1($firstName, $lastName, $mobileNumber, $email, $address, $profilePic);
                echo "<div class='alert alert-success'>User created successfully!</div>";
                header('Location:  ../Views/user_list1.php');
                exit();
            }
        }
    }

    public function importCsv() {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];
            $fileType = mime_content_type($fileTmpPath);
    
            // Allowed file types
            $allowedFileType = ['text/csv', 'application/vnd.ms-excel'];
            if (!in_array($fileType, $allowedFileType)) {
                $_SESSION['error_message'] = 'Invalid file type. Please upload a CSV file.';
                header('Location: ../Views/import.php');
                exit;
            }
    
            $fileHandle = fopen($fileTmpPath, 'r');
            $headers = fgetcsv($fileHandle); // Read header row
    
            // Verify if headers match the required format
            $expectedHeaders = ['FirstName', 'LastName', 'MobileNumber', 'Email', 'Address', 'ProfilePic'];
            if ($headers !== $expectedHeaders) {
                $_SESSION['error_message'] = 'Invalid CSV headers. Expected: ' . implode(', ', $expectedHeaders);
                header('Location: ../Views/import.php');
                exit;
            }
    
            $errors = [];
            $users = [];
    
            while (($row = fgetcsv($fileHandle)) !== false) {
                $firstName = trim($row[0]);
                $lastName = trim($row[1]);
                $mobileNumber = trim($row[2]);
                $email = trim($row[3]);
                $address = trim($row[4]);
                $profilePic = trim($row[5]);
    
                // Validation
                if (!preg_match('/^[a-zA-Z ]+$/', $firstName)) {
                    $errors[] = "Invalid first name: $firstName";
                    continue;
                }
    
                if (!preg_match('/^[a-zA-Z ]+$/', $lastName)) {
                    $errors[] = "Invalid last name: $lastName";
                    continue;
                }
    
                if (!preg_match('/^\d{10}$/', $mobileNumber)) {
                    $errors[] = "Invalid mobile number: $mobileNumber";
                    continue;
                }
    
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Invalid email address: $email";
                    continue;
                }
    
                if (empty($address)) {
                    $errors[] = "Address is required for user: $firstName $lastName";
                    continue;
                }
    
                if ($this->user1Model->mobileNumberExists($mobileNumber)) {
                    $errors[] = "Duplicate mobile number: $mobileNumber";
                    continue;
                }
    
                if ($this->user1Model->emailExists($email)) {
                    $errors[] = "Duplicate email: $email";
                    continue;
                }
    
                $uploadDir = __DIR__ . '/../../uploads/';
                $profilePicPath = $uploadDir . $profilePic;
                if (!file_exists($profilePicPath)) {
                    $errors[] = "Profile picture not found: $profilePic";
                    continue;
                }
    
                $users[] = [
                    'FirstName' => $firstName,
                    'LastName' => $lastName,
                    'MobileNumber' => $mobileNumber,
                    'Email' => $email,
                    'Address' => $address,
                    'ProfilePic' => $profilePic
                ];
            }
    
            fclose($fileHandle);
    
            if (!empty($errors)) {
                $_SESSION['error_message'] = implode('<br>', $errors);
                header('Location: ../Views/import.php');
                exit;
            }
    
            // Insert valid records
            foreach ($users as $user) {
                $this->user1Model->create1(
                    $user['FirstName'],
                    $user['LastName'],
                    $user['MobileNumber'],
                    $user['Email'],
                    $user['Address'],
                    $user['ProfilePic']
                );
            }
    
            $_SESSION['success_message'] = 'CSV file imported successfully!';
            header('Location: ../Views/user_list1.php');
            exit;
        } else {
            $_SESSION['error_message'] = 'Error uploading CSV file.';
            header('Location: ../Views/import.php');
            exit;
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
        move_uploaded_file($_FILES['ProfilePic']['tmp_name'], '../public/uploads/' . $profilePic);
    }


 

    // Update user information
    if ($user->update1($id, $firstName, $lastName, $mobileNumber, $email, $address, $profilePic)) {
        header('Location: ../Views/user_list1.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update user.</div>";
    }
}
    }


    public function deleteUser($id) {


        $this->user1Model->delete1($id);
        header('Location: ../Views/user_list1.php');
    }

    public function massDeleteUsers($userIds) {

    //        echo 'Hello';
    // exit;
        if (!empty($userIds)) {
            foreach ($userIds as $id) {
                $this->user1Model->deleteUser($id);
            }
            $_SESSION['success_message'] = "Selected users deleted successfully.";
        } else {
            $_SESSION['error_message'] = "No users selected for deletion.";
        }
        header("Location: ../Views/user_list1.php");
        exit();
    }



}
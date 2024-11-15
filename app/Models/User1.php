<?php
// require_once 'config/Database1.php';
require_once __DIR__ . '/../../config/Database.php';

class User1 {
    private $conn;

    public function __construct() {
        $db = new Database("MVC_EMPLOYEE");
        $this->conn = $db->conn;
    }

    public function create1($firstName, $lastName, $mobileNumber, $email, $address, $profilePic) {

        $stmt = $this->conn->prepare("INSERT INTO EMPLOYEE (FirstName, LastName, MobileNumber, Email, Address, ProfilePic) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $firstName, $lastName, $mobileNumber, $email, $address, $profilePic);
        return $stmt->execute();
    }

    public function delete1($id) {
        $stmt = $this->conn->prepare("DELETE FROM EMPLOYEE WHERE ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Updated this method to return an array of users
    public function readAll1() {
        $result = $this->conn->query("SELECT * FROM EMPLOYEE");
        // print_r($result);
        // exit;
        return $result->fetch_all(MYSQLI_ASSOC); // returns an array
    }

    // Add countUsers method for pagination
    // public function countUsers($search = '') {
    //     $search = "%" . $this->conn->real_escape_string($search) . "%"; // Sanitize search input
    //     $stmt = $this->conn->prepare("SELECT COUNT(*) FROM EMPLOYEE WHERE FirstName LIKE ? OR LastName LIKE ? OR MobileNumber LIKE ? OR Email LIKE ? OR Address LIKE ?");
    //     // $stmt->bind_param("ss", $search, $search);
    //     $stmt->bind_param('sssss', $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch);
    //     $stmt->execute();
    //     $stmt->bind_result($count);
    //     $stmt->fetch();
    //     return $count; // returns the total number of users
    // }

    public function countUsers($search = '') {
        $search = "%" . $this->conn->real_escape_string($search) . "%";
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM EMPLOYEE WHERE FirstName LIKE ? OR LastName LIKE ? OR MobileNumber LIKE ? OR Email LIKE ? OR Address LIKE ?");
        $stmt->bind_param('sssss', $search, $search, $search, $search, $search);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count;
    }

    // Add fetchUsers method for pagination with search
    // public function fetchUsers($search = '', $limit = 5, $offset = 0) {
    //     // echo 'Hello';
    //     // exit;
    //     $search = "%" . $this->conn->real_escape_string($search) . "%"; // Sanitize search input
    //     $stmt = $this->conn->prepare("SELECT * FROM EMPLOYEE WHERE FirstName LIKE ? OR LastName LIKE ? OR MobileNumber LIKE ? OR Email LIKE ? OR Address LIKE ? LIMIT ? OFFSET ?");
    //     // $stmt->bind_param("ssii", $search, $search, $limit, $offset);
    //     $stmt->bind_param('ssssiii', $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch, $limit, $offset);
    //     $stmt->execute();
    //     // print_r($stmt);
    //     // exit;
    //     $result = $stmt->get_result();
    //     return $result->fetch_all(MYSQLI_ASSOC); // returns an array of users
    // }        


    public function fetchUsers($search = '', $limit = 5, $offset = 0) {
        $search = "%" . $this->conn->real_escape_string($search) . "%";
        $stmt = $this->conn->prepare("SELECT * FROM EMPLOYEE WHERE FirstName LIKE ? OR LastName LIKE ? OR MobileNumber LIKE ? OR Email LIKE ? OR Address LIKE ? LIMIT ? OFFSET ?");
        $stmt->bind_param('ssssssi', $search, $search, $search, $search, $search, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update1($id, $firstName, $lastName, $mobileNumber, $email, $address, $profilePic) {
        $stmt = $this->conn->prepare("UPDATE EMPLOYEE SET FirstName = ?, LastName = ?, MobileNumber = ?, Email = ?, Address = ?, ProfilePic = ? WHERE ID = ?");
        // $stmt->bind_param("ssii", $firstName, $lastName, $rollNo, $id);
        $stmt->bind_param("ssisssi", $firstName, $lastName, $mobileNumber, $email, $address, $profilePic, $id);
        return $stmt->execute();
    }

    public function find1($id) {
        $stmt = $this->conn->prepare("SELECT * FROM EMPLOYEE WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        return $user;
    }

    public function deleteUser($id) {
        // Database query to delete the user by ID
        $sql = "DELETE FROM users WHERE ID = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }


    public function mobileNumberExists($mobileNumber) {
        // Fix: Access conn through $this->db
        $stmt = $this->conn->prepare("SELECT * FROM EMPLOYEE  WHERE MobileNumber = ?");
        $stmt->bind_param('s', $mobileNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; // Returns true if the mobile number exists
    }

    public function emailExists($email){

        $stmt = $this->conn->prepare("SELECT * FROM EMPLOYEE  WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; // Returns true if the email exists
    
    }

    }



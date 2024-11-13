<?php
session_start();
// require_once 'User1.php';
require_once __DIR__ . '/../Models/User1.php';

// Check if user is logged in
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header('Location: ../../public/login.php');
//     exit;
// }

// Initialize User1 object
$user = new User1();

// Handle search
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Handle pagination
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch users and total count based on search and pagination
$total_users = $user->countUsers($search);
$total_pages = ceil($total_users / $limit);
$users = $user->fetchUsers($search, $limit, $offset);



// Handle logout
// if (isset($_POST['logout'])) {
//     session_destroy();
//     header('Location: index.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="styles.css">
    <style>

input[name="search"] {
            width: 300px; /* Increase width as needed */
            padding: 10px; /* Add some padding for better appearance */
            font-size: 16px; /* Increase font size */
            margin-right: 10px; /* Space between input and button */
}
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Updated font family */
    background-color: #f9f9f9; /* Changed background color */
    margin: 0;
    padding: 40px; /* Increased padding */
}

.container {
    max-width: 800px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333; /* Changed color */
    font-size: 24px; /* Set font size */
    margin-bottom: 20px; /* Added margin bottom */
}

.btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 15px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #218838;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: white;
}

tr:hover {
    background-color: #f1f1f1;
}

.actions-column {
    text-align: center;
}

.action-link {
    color: #007bff;
    text-decoration: none;
}

.action-link:hover {
    text-decoration: underline;
}

img {
    border-radius: 50%;
    border: 1px solid #ccc;
}

.form-container {
    max-width: 500px; /* Set maximum width */
    margin: 0 auto; /* Centered alignment */
    background-color: #ffffff; /* Background color */
    padding: 20px 30px; /* Inner padding */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
    text-align: center; /* Centered text */
}

.form-group {
    margin-bottom: 15px; /* Spacing below each group */
    text-align: left; /* Align text left */
}

.form-group label {
    display: block; /* Block display */
    font-weight: bold; /* Bold text */
    margin-bottom: 5px; /* Spacing below label */
    color: #333; /* Color of label */
}

.form-group input, .form-group textarea {
    width: 100%; /* Full width */
    padding: 10px; /* Inner padding */
    border-radius: 5px; /* Rounded corners */
    border: 1px solid #ddd; /* Border */
    font-size: 16px; /* Font size */
    transition: border-color 0.3s; /* Transition effect */
}

.form-group input:focus, .form-group textarea:focus {
    border-color: #5cb85c; /* Border color on focus */
}

button {
    width: 100%; /* Full width */
    padding: 12px; /* Inner padding */
    background-color: #5cb85c; /* Button color */
    color: white; /* Text color */
    font-size: 18px; /* Font size */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer on hover */
    transition: background-color 0.3s; /* Transition effect */
}

button:hover {
    background-color: #4cae4c; /* Darker color on hover */
}

button:disabled {
    background-color: #ccc; /* Color when disabled */
    cursor: not-allowed; /* Not allowed cursor */
}

.error-message {
    color: red; /* Error message color */
    font-size: 14px; /* Font size */
    margin-top: 5px; /* Spacing above */
}

.success-message {
    color: green; /* Success message color */
    font-size: 14px; /* Font size */
    margin-top: 5px; /* Spacing above */
}

.error {
    color: red;
    font-weight: bold;
}

.success {
    color: green;
    font-weight: bold;
}
</style>
</head>
<body>
    <div class="container">
        <!-- Filter Form -->
        <form method="GET" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search" autocomplete="off">
            <button type="submit">Search</button>
        </form>

        <h2>User List</h2>
        
        <a href="create1.php" class="btn">Create User</a>
        <a href="../../public/index.php" class="btn">Logout</a>
        <a href="import.php" class="btn">Import</a>

        <!-- User List Table -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Profile Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['ID']); ?></td>
                        <td><?php echo htmlspecialchars($user['FirstName']); ?></td>
                        <td><?php echo htmlspecialchars($user['LastName']); ?></td>
                        <td><?php echo htmlspecialchars($user['MobileNumber']); ?></td>
                        <td><?php echo htmlspecialchars($user['Email']); ?></td>
                        <td><?php echo htmlspecialchars($user['Address']); ?></td>
                        <td>
                            <?php if (!empty($user['ProfilePic'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($user['ProfilePic']); ?>" alt="Profile Picture" width="50">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit1.php?id=<?php echo $user['ID']; ?>">Edit</a> |
                            <a href="delete1.php?delete1=<?php echo $user['ID']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>" <?php if ($i == $page) echo 'style="font-weight: bold;"'; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
}

header {
    background-color: #333;
    color: #fff;
    padding: 10px;
    text-align: center;
}

header nav ul {
    list-style: none;
    padding: 0;
}

header nav ul li {
    display: inline;
    margin: 0 10px;
}

header nav ul li a {
    color: #fff;
    text-decoration: none;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px;
    position: fixed;
    width: 100%;
    bottom: 0;
}

        /* The side navigation menu */
.sidenav {
  height: 100%; /* 100% Full-height */
  width: 0; /* 0 width - change this with JavaScript */
  position: fixed; /* Stay in place */
  z-index: 1; /* Stay on top */
  top: 0; /* Stay at the top */
  left: 0;
  background-color: #111; /* Black*/
  overflow-x: hidden; /* Disable horizontal scroll */
  padding-top: 60px; /* Place content 60px from the top */
  transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
}

/* The navigation menu links */
.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

/* When you mouse over the navigation links, change their color */
.sidenav a:hover {
  color: #f1f1f1;
}

/* Position and style the close button (top right corner) */
.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

/* Style page content - use this if you want to push the page content to the right when you open the side navigation */
#main {
  transition: margin-left .5s;
  padding: 20px;
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}

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
    <header>
        <nav>
            <ul>
                <li><a href="user_list1.php">Home</a></li>
                <li><a href="import.php">Import</a></li>
            </ul>
        </nav>
        <h1>Welcome to the import page</h1>
    </header>
    <main>

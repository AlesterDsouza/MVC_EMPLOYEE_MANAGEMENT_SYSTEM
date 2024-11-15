<?php
session_start();
require_once __DIR__ . '/../Models/User1.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../index.php");
    exit();
}

$user = new User1();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $existingUser = $user->find1($id);
}

if (isset($_POST['submit'])) {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $mobileNumber = $_POST['MobileNumber'];
    $email = $_POST['Email'];
    $address = $_POST['Address'];
    $profilePic = $existingUser['ProfilePic'];

    if ($_FILES['ProfilePic']['name']) {
        $profilePic = time() . '_' . $_FILES['ProfilePic']['name'];
        move_uploaded_file($_FILES['ProfilePic']['tmp_name'], 'uploads/' . $profilePic);
    }

    if ($user->update1($id, $firstName, $lastName, $mobileNumber, $email, $address, $profilePic)) {
        header('Location: User_list1.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update user.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body onload="validateAllFields()">
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Edit User</h2>
            <form action="edit1.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" id="editUserForm">
                <input type="hidden" name="ID" value="<?php echo $existingUser['ID']; ?>">

                <div class="form-group">
                    <label for="Name">First Name:</label>
                    <input type="text" class="form-control" id="FirstName" name="FirstName" 
                           value="<?php echo htmlspecialchars($existingUser['FirstName']); ?>" 
                           required autocomplete="off" oninput="validateFirstName()">
                    <div id="name-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="LastName">Last Name:</label>
                    <input type="text" class="form-control" id="LastName" name="LastName" 
                           value="<?php echo htmlspecialchars($existingUser['LastName']); ?>" 
                           required autocomplete="off" oninput="validateLastName()">
                    <div id="last-name-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="Phone">Mobile Number:</label>
                    <input type="text" class="form-control" id="MobileNumber" name="MobileNumber" 
                           value="<?php echo htmlspecialchars($existingUser['MobileNumber']); ?>" 
                           required autocomplete="off" oninput="validatePhone()">
                    <div id="phone-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input type="email" class="form-control" id="Email" name="Email" 
                           value="<?php echo htmlspecialchars($existingUser['Email']); ?>" 
                           required autocomplete="off" oninput="validateEmail()">
                    <div id="email-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="Address">Address:</label>
                    <input type="text" class="form-control" id="Address" name="Address" 
                           value="<?php echo htmlspecialchars($existingUser['Address']); ?>" 
                           required autocomplete="off" oninput="validateAddress()">
                    <div id="address-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="File">Profile Picture:</label>
                    <input type="file" class="form-control-file" id="ProfilePic" name="ProfilePic" onchange="validateImage()">
                    <div id="image-error" class="error-message"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn" name="submit" disabled>Submit</button>
            </form>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js" defer></script>
<script>

var submitBtn = document.getElementById('submitBtn');

// Validation flag variables
var vFirstName = false;
var vLastName = false;
var vPhone = false;
var vEmail = false;
var vAddress = false;
var vImage = false;

function validateFirstName() {
    var firstName = document.getElementById('FirstName').value;
    var nameError = document.getElementById('name-error');
    if (firstName.length === 0) {
        nameError.innerHTML = 'First name is required';
        nameError.style.color = 'red';
        vFirstName = false;
    } else if (firstName.length < 3) {
        nameError.innerHTML = 'First name must be at least 3 characters long';
        nameError.style.color = 'red';
        vFirstName = false;
    } else {
        nameError.innerHTML = 'Valid First Name';
        nameError.style.color = 'green';
        vFirstName = true;
    }
    checkSubmitButton();
}

function validateLastName() {
    var lastName = document.getElementById('LastName').value;
    var lastNameError = document.getElementById('last-name-error');
    if (lastName.length === 0) {
        lastNameError.innerHTML = 'Last name is required';
        lastNameError.style.color = 'red';
        vLastName = false;
    } else if (lastName.length < 3) {
        lastNameError.innerHTML = 'Last name must be at least 3 characters long';
        lastNameError.style.color = 'red';
        vLastName = false;
    } else {
        lastNameError.innerHTML = 'Valid Last Name';
        lastNameError.style.color = 'green';
        vLastName = true;
    }
    checkSubmitButton();
}

function validatePhone() {
    var phoneInput = document.getElementById('MobileNumber');
    var phone = phoneInput.value.replace(/\D/g, '');
    if (phone.length === 0) {
        document.getElementById('phone-error').innerHTML = 'Phone number is required';
        vPhone = false;
    } else if (phone.length !== 10) {
        document.getElementById('phone-error').innerHTML = 'Phone number must be exactly 10 digits';
        vPhone = false;
    } else {
        document.getElementById('phone-error').innerHTML = 'Valid Phone Number';
        vPhone = true;
    }
    checkSubmitButton();
}

function validateEmail() {
    var email = document.getElementById('Email').value;
    var emailPattern = /^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/;
    var emailError = document.getElementById('email-error');
    if (email.length === 0) {
        emailError.innerHTML = 'Email is required';
        emailError.style.color = 'red';
        vEmail = false;
    } else if (!email.match(emailPattern)) {
        emailError.innerHTML = 'Invalid email format';
        emailError.style.color = 'red';
        vEmail = false;
    } else {
        emailError.innerHTML = 'Valid Email';
        emailError.style.color = 'green';
        vEmail = true;
    }
    checkSubmitButton();
}

function validateAddress() {
    var address = document.getElementById('Address').value;
    var addressError = document.getElementById('address-error');
    if (address.length === 0) {
        addressError.innerHTML = 'Address is required';
        addressError.style.color = 'red';
        vAddress = false;
    } else {
        addressError.innerHTML = 'Valid Address';
        addressError.style.color = 'green';
        vAddress = true;
    }
    checkSubmitButton();
}

function validateImage() {
    var image = document.getElementById('ProfilePic').value;
    var imageError = document.getElementById('image-error');
    if (image) {
        var validExtensions = ['jpg', 'jpeg', 'png'];
        var fileExtension = image.split('.').pop().toLowerCase();
        if (validExtensions.includes(fileExtension)) {
            imageError.innerHTML = 'Valid Image File';
            imageError.style.color = 'green';
            vImage = true;
        } else {
            imageError.innerHTML = 'Only JPG, JPEG, and PNG files are allowed';
            imageError.style.color = 'red';
            vImage = false;
        }
    } else {
        imageError.innerHTML = 'Profile picture is optional';
        imageError.style.color = 'green';
        vImage = true;
    }
    checkSubmitButton();
}

function checkSubmitButton() {
    if (vFirstName && vLastName && vPhone && vEmail && vAddress && vImage) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

function validateAllFields() {
    validateFirstName();
    validateLastName();
    validatePhone();
    validateEmail();
    validateAddress();
    validateImage();
}

</script>
</body>
</html>

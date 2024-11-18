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
     
        // Validate Indian mobile number (starts with 6-9 and is 10 digits long)
    if (!preg_match('/^[6-9]\d{9}$/', $mobileNumber)) {
            echo "<div class='alert alert-danger'>Invalid mobile number! Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.</div>";
            exit();
    }
    

    if ($user->update1($id, $firstName, $lastName, $mobileNumber, $email, $address, $profilePic)) {
        header('Location: user_list1.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update user.</div>";
    }

    //     // Validate Indian mobile number (starts with 6-9 and is 10 digits long)
    //     if (!preg_match('/^[6-9]\d{9}$/', $mobileNumber)) {
    //         echo "<div class='alert alert-danger'>Invalid mobile number! Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.</div>";
    //         die();
    // }

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
    <!-- <script src="script.js" defer></script> -->
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
                    <div id="first-name-error" class="error-message"></div>
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

                <!-- <div class="form-group">
                    <label for="Phone">Mobile Number:</label>
                    <input type="text" class="form-control" id="MobileNumber" name="MobileNumber" 
                        value="<?php echo htmlspecialchars($existingUser['MobileNumber']); ?>" required>
                    <small class="form-text text-muted">Enter a valid 10-digit Indian mobile number.</small>
                </div> -->

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

                <!-- <div class="form-group">
                    <label for="File">Profile Picture:</label>
                    <input type="file" class="form-control-file" id="ProfilePic" name="ProfilePic" onchange="validateImage()">
                    <div id="image-error" class="error-message"></div>
                </div> -->

                <div class="form-group">
                    <label for="ProfilePic">Profile Picture:</label>
                    <input type="file" class="form-control-file" id="ProfilePic" name="ProfilePic" accept="image/jpeg, image/png" onchange="validateImage()">
                    <?php if ($existingUser['ProfilePic']): ?>
                        <img src="/uploads/<?php echo htmlspecialchars($existingUser['ProfilePic']); ?>" alt="Profile Picture" width="100" class="mt-2">
                    <?php else: ?>
                        <p>No profile picture available.</p>
                    <?php endif; ?>
                    <div id="image-error" class="error-message"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn" name="submit" disabled>Submit</button>
            </form>
        </div>
    </div>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>  -->
<!-- <script src="script.js" defer></script>  -->
<script>


var nameError = document.getElementById('name-error');
var firstNameError = document.getElementById('first-name-error');
var lastNameError = document.getElementById('last-name-error');
var phoneError = document.getElementById('phone-error');
var emailError = document.getElementById('email-error');
var addressError = document.getElementById('address-error');
var imageError = document.getElementById('image-error');
var submitBtn = document.getElementById('submitBtn');

var vFirstName = false;
var vLastName = false;
var vPhone = false;
var vEmail = false;
var vAddress = false;
var vImage = false;

// Restrict input to alphabetic characters for first name
function restrictFirstNameInput(event) {
    const value = event.target.value;
    event.target.value = value.replace(/[^a-zA-Z ]/g, ''); 
    validateFirstName();
}

// Restrict input to alphabetic characters for last name
function restrictLastNameInput(event) {
    const value = event.target.value;
    event.target.value = value.replace(/[^a-zA-Z ]/g, ''); 
    validateLastName();
}

// Validate first name
function validateFirstName() {
    var firstName = document.getElementById('FirstName').value;

    if (firstName.length === 0) {
        firstNameError.innerHTML = 'First name is required';
        firstNameError.style.color= 'red';
        firstNameError.classList.remove('success');
        firstNameError.classList.add('error');
        vFirstName = false;
    } else if (firstName.length < 3) {
        firstNameError.innerHTML = 'First name must be at least 3 characters long';
        firstNameError.style.color= 'red';
        firstNameError.classList.remove('success');
        firstNameError.classList.add('error');
        vFirstName = false;
    } else {
        firstNameError.innerHTML = 'Valid First Name';
        firstNameError.style.color= 'green';
        firstNameError.classList.remove('error');
        firstNameError.classList.add('success');
        vFirstName = true;
    }
    checkSubmitButton();
}

// Validate last name
function validateLastName() {
    var lastName = document.getElementById('LastName').value;

    if (lastName.length === 0) {
        lastNameError.innerHTML = 'Last name is required';
        lastNameError.style.color= 'red';
        lastNameError.classList.remove('success');
        lastNameError.classList.add('error');
        vLastName = false;
    } else if (lastName.length < 3) {
        lastNameError.innerHTML = 'Last name must be at least 3 characters long';
        lastNameError.style.color= 'red';
        lastNameError.classList.remove('success');
        lastNameError.classList.add('error');
        vLastName = false;
    } else {
        lastNameError.innerHTML = 'Valid Last Name';
        lastNameError.style.color= 'green';
        lastNameError.classList.remove('error');
        lastNameError.classList.add('success');
        vLastName = true;
    }
    checkSubmitButton();
}

// Validate phone number
function validatePhone() {
    const phoneInput = document.getElementById('MobileNumber').value;
    const regex = /^[6-9]\d{9}$/;
    const phoneError = document.getElementById('phone-error');

    if (!regex.test(phoneInput)) {
        phoneError.innerHTML = "Invalid phone number! Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.";
        phoneError.style.color = "red";
        vPhone = false;  
    } else {
        phoneError.innerHTML = "Valid phone number.";
        phoneError.style.color = "green";
        vPhone = true;  
    }
    checkSubmitButton();
}

    // var phoneInput = document.getElementById('MobileNumber');
    // var phone = phoneInput.value;

    // phone = phone.replace(/\D/g, ''); // Remove any non-digit characters
    // if (phone.length > 10) {
    //     phone = phone.slice(0, 10); // Limit the input to the first 10 digits
    // }
    // phoneInput.value = phone; // Update the input value to exclude extra digits

    // if (phone.length === 0) {
    //     phoneError.innerHTML = 'Phone number is required';
    //     phoneError.style.color = 'red';
    //     phoneError.classList.remove('success');
    //     phoneError.classList.add('error');
    //     vPhone = false;
    // } else if (phone.length !== 10) {
    //     phoneError.innerHTML = 'Phone number must be exactly 10 digits';
    //     phoneError.style.color = 'red';
    //     phoneError.classList.remove('success');
    //     phoneError.classList.add('error');
    //     vPhone = false;
    // } else {
    //     phoneError.innerHTML = 'Valid Phone Number';
    //     phoneError.style.color = 'green';
    //     phoneError.classList.remove('error');
    //     phoneError.classList.add('success');
    //     vPhone = true;
    // }
//     checkSubmitButton();
// }

// Validate email
function validateEmail() {
    var email = document.getElementById('Email').value;
    var emailPattern = /^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/;

    if (email.length === 0) {
        emailError.innerHTML = 'Email is required';
        emailError.style.color= 'red';
        emailError.classList.remove('success');
        emailError.classList.add('error');
        vEmail = false;
    } else if (!email.match(emailPattern)) {
        emailError.innerHTML = 'Please enter a valid email address';
        emailError.style.color= 'red';
        emailError.classList.remove('success');
        emailError.classList.add('error');
        vEmail = false;
    } else {
        emailError.innerHTML = 'Valid Email';
        emailError.style.color= 'green';
        emailError.classList.remove('error');
        emailError.classList.add('success');
        vEmail = true;
    }
    checkSubmitButton();
}

// Validate address
function validateAddress() {
    var address = document.getElementById('Address').value;

    if (address.length === 0) {
        addressError.innerHTML = 'Address is required';
        addressError.style.color= 'red';
        addressError.classList.remove('success');
        addressError.classList.add('error');
        vAddress = false;
    } else {
        addressError.innerHTML = 'Valid Address';
        addressError.style.color= 'green';
        addressError.classList.remove('error');
        addressError.classList.add('success');
        vAddress = true;
    }
    checkSubmitButton();
}

var existingImage = false; // Set this based on your logic
var vImage = false;
function validateImage() {
    var fileInput = document.getElementById('ProfilePic');
    var file = fileInput.files[0];
    var allowedTypes = ['image/jpeg', 'image/png'];
    var imageError = document.getElementById('image-error');

    // Check if there is an existing image
    if (existingImage && !file) {
        imageError.innerHTML = 'Existing image is available';
        imageError.classList.remove('error');
        imageError.classList.add('success');
        vImage = true; // Valid since existing image is present
        checkSubmitButton();
        return; // Exit the function since we found an existing image
    }

    // If a file is selected, perform the validation
    if (file) {
        if (!allowedTypes.includes(file.type)) {
            imageError.innerHTML = 'Only .jpg and .png files are allowed';
            imageError.style.color= 'red';
            imageError.classList.remove('success');
            imageError.classList.add('error');
            vImage = false;
        } else if (file.size > 5 * 1024 * 1024) { // 5MB limit
            imageError.innerHTML = 'Image size must not exceed 5MB';
            imageError.style.color= 'red';
            imageError.classList.remove('success');
            imageError.classList.add('error');
            vImage = false;
        } else {
            imageError.innerHTML = 'Valid Image';
            imageError.style.color= 'green';
            imageError.classList.remove('error');
            imageError.classList.add('success');
            vImage = true;
        }
    } else if (!existingImage) {
        // If no file is selected and no existing image
        imageError.innerHTML = 'Would you like to upload a new profile picture';
        imageError.style.color= 'green';
        imageError.classList.remove('error');
        imageError.classList.add('success');
        vImage = true;
    }

    checkSubmitButton(); // Call the function to check the button state
}


// Check if all validations pass and enable/disable the submit button
function checkSubmitButton() {
    if (vFirstName && vLastName && vPhone && vEmail && vAddress && vImage) {
        submitBtn.disabled = false; // Enable submit button
    } else {
        submitBtn.disabled = true; // Disable submit button
    }
}

// Event listeners for validation
document.getElementById('FirstName').addEventListener('input', restrictFirstNameInput);
document.getElementById('LastName').addEventListener('input', restrictLastNameInput);
document.getElementById('MobileNumber').addEventListener('change', validatePhone);
document.getElementById('Email').addEventListener('change', validateEmail);
document.getElementById('Address').addEventListener('change', validateAddress);
document.getElementById('ProfilePic').addEventListener('change', validateImage);

 // Validate all fields when page loads
 function validateAllFields() {
            validateFirstName();
            validateLastName();
            validatePhone();
            validateEmail();
            validateAddress();
            validateImage();
            checkSubmitButton();          
        }

</script>
</body>
</html>

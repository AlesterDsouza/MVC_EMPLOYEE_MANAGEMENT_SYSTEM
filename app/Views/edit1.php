<?php

session_start();
require_once 'User1.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    // exit;
}

$user = new User1();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $existingUser = $user->find1($id);
}

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

    <script>
        var existingImage = <?php echo json_encode(!empty($existingUser['ProfilePic'])); ?>; // Set true if a profile picture exists
    </script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }

    </style>
</head>
<body onload="validateAllFields()">
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Edit User</h2>
            <form action="edit1.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" autocomplete="off" id="editUserForm">
                <input type="hidden" name="ID" value="<?php echo $existingUser['ID']; ?>">

                <div class="form-group">
                    <label for="FirstName">First Name:</label>
                    <input type="text" class="form-control" id="FirstName" name="FirstName" 
                           value="<?php echo htmlspecialchars($existingUser['FirstName']); ?>" 
                           required autocomplete="off" oninput="restrictFirstNameInput()">
                    <div id="first-name-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="LastName">Last Name:</label>
                    <input type="text" class="form-control" id="LastName" name="LastName" 
                           value="<?php echo htmlspecialchars($existingUser['LastName']); ?>" 
                           required autocomplete="off" oninput="restrictLastNameInput()">
                    <div id="last-name-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="MobileNumber">Mobile Number:</label>
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
                    <textarea class="form-control" id="Address" name="Address" required autocomplete="off" oninput="validateAddress()"><?php echo htmlspecialchars($existingUser['Address']); ?></textarea>
                    <div id="address-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="ProfilePic">Profile Picture:</label>
                    <input type="file" class="form-control-file" id="ProfilePic" name="ProfilePic" accept="image/jpeg, image/png" onchange="validateImage()">
                    <?php if ($existingUser['ProfilePic']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($existingUser['ProfilePic']); ?>" alt="Profile Picture" width="100" class="mt-2">
                    <?php else: ?>
                        <p>No profile picture available.</p>
                    <?php endif; ?>
                    <div id="image-error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary btn-block" id="submitBtn" disabled>Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script>

        //Code 2 sample:

var nameError = document.getElementById('name-error');
var emailError = document.getElementById('email-error');
var phoneError = document.getElementById('phone-error');
var ageError = document.getElementById('age-error');
var genderError = document.getElementById('gender-error');
var imageError = document.getElementById('image-error');
var submitError = document.getElementById('submit-error');
// var submitBtn = document.getElementById('submitBtn');

var vname = false;
var vphone = false;
var vemail = false;
var vage = false;
var vgender = false;
var vimage = false;

// Reference the submit button
document.addEventListener('DOMContentLoaded', function() {
    var submitBtn = document.getElementById('submitBtn');
});

// Restrict input to letters and spaces for name
document.getElementById('Name').addEventListener('input', restrictNameInput);
function restrictNameInput(event) {
    const value = event.target.value;
    event.target.value = value.replace(/[^a-zA-Z ]/g, ''); 
    validateName();
}




// Restrict input to numeric values for phone and age
document.getElementById('Phone').addEventListener('input', restrictNumericInput);
document.getElementById('Age').addEventListener('input', restrictNumericInput);
function restrictNumericInput(event) {
    const value = event.target.value;
    event.target.value = value.replace(/[^0-9]/g, ''); 
    // validatePhone();
    // validateAge();
}



function validateName(event) {
    var nameInput = document.getElementById('Name');
    var name = nameInput.value;
    var key = event.key;


    // Check if the pressed key is a number
    if (isNaN(key)) {
        // If it's a number, prevent it from being entered and show the error
        //event.preventDefault();
        nameError.innerHTML = 'Only alphabets are allowed';
        nameError.classList.remove('success');
        nameError.classList.add('error');
        vname = false;
    } 
    else if(name.length === 0) {
        nameError.innerHTML = 'Name is required';
        nameError.classList.remove('success');
        nameError.classList.add('error');
        vname = false;
    } 
    
    if (name.length < 3) {
        nameError.innerHTML = 'Name must be at least 3 characters long and alphabets';
        nameError.classList.remove('success');
        nameError.classList.add('error');
        vname = false;
    }
    else{
        nameError.innerHTML = 'Valid name';
        nameError.classList.remove('error');
        nameError.classList.add('success');
        vname = true;
    }

    checkSubmitButton(); // Enable or disable submit button based on validation
}





// Validate phone number
function validatePhone() {
    var phoneInput = document.getElementById('Phone');
    var phone = phoneInput.value;

    // Allow only digits and limit input to 10 digits
    phone = phone.replace(/\D/g, ''); // Remove any non-digit characters
    if (phone.length > 10) {
        phone = phone.slice(0, 10); // Limit the input to the first 10 digits
    }
    phoneInput.value = phone; // Update the input value to exclude extra digits

    if (phone.length === 0) {
        phoneError.innerHTML = 'Phone number is required';
        phoneError.classList.remove('success');
        phoneError.classList.add('error');
        vphone = false;
    } else if (phone.length !== 10) {
        phoneError.innerHTML = 'Phone number must be exactly 10 digits';
        phoneError.classList.remove('success');
        phoneError.classList.add('error');
        vphone = false;
    } else {
        phoneError.innerHTML = 'Valid phone number';
        phoneError.classList.remove('error');
        phoneError.classList.add('success');
        vphone = true;
    }
    
    checkSubmitButton();
}


// Validate email
function validateEmail() {
    var email = document.getElementById('Email').value;
    var emailPattern = /^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/;
    if (email.length === 0) {
        emailError.innerHTML = 'Email is required';
        emailError.classList.remove('success');
        emailError.classList.add('error');
        vemail = false;
    } else if (!email.match(emailPattern)) {
        emailError.innerHTML = 'Please enter a valid email address';
        emailError.classList.remove('success');
        emailError.classList.add('error');
        vemail = false;
    } else {
        emailError.innerHTML = 'Valid email';
        emailError.classList.remove('error');
        emailError.classList.add('success');
        vemail = true;
        
    }
    checkSubmitButton();
}

// Validate age
function validateAge() {
    var age = document.getElementById('Age').value;
    if (age.length === 0) {
        ageError.innerHTML = 'Age is required';
        ageError.classList.remove('success');
        ageError.classList.add('error');
        vage = false;
    } else if (isNaN(age) || age < 18 || age > 100) {
        ageError.innerHTML = 'Age must be between 18 and 100';
        ageError.classList.remove('success');
        ageError.classList.add('error');
        vage = false;
    } else {
        ageError.innerHTML = 'Valid age';
        ageError.classList.remove('error');
        ageError.classList.add('success');
        vage = true;
        
    }
    checkSubmitButton();
}

// Validate gender
function validateGender() {
    var gender = document.getElementById('Gender').value;
    if (gender === "") {
        genderError.innerHTML = 'Gender is required';
        genderError.classList.remove('success');
        genderError.classList.add('error');
        vgender = false;
    } else {
        genderError.innerHTML = 'Valid gender';
        genderError.classList.remove('error');
        genderError.classList.add('success');
        vgender = true;
        
    }
    checkSubmitButton();
}

// Validate image
function validateImage() {
    var fileInput = document.getElementById('File');
    var file = fileInput.files[0];
    var allowedTypes = ['image/jpeg', 'image/png'];

    if (!file) {
        imageError.innerHTML = 'Image is required';
        imageError.classList.remove('success');
        imageError.classList.add('error');
        vimage = false;
    } else if (!allowedTypes.includes(file.type)) {
        imageError.innerHTML = 'Only .jpg and .png files are allowed';
        imageError.classList.remove('success');
        imageError.classList.add('error');
        vimage = false;
    } else if (file.size > 5 * 1024 * 1024) { // 5MB
        imageError.innerHTML = 'Image size must not exceed 5MB';
        imageError.classList.remove('success');
        imageError.classList.add('error');
        vimage = false;
    } else {
        imageError.innerHTML = 'Valid image';
        imageError.classList.remove('error');
        imageError.classList.add('success');
        vimage = true;
        
    }
    checkSubmitButton();
}

// Event listeners for validation
document.getElementById('Name').addEventListener('onchange', validateName);
document.getElementById('Phone').addEventListener('onchange', validatePhone);
document.getElementById('Email').addEventListener('onchange', validateEmail);
document.getElementById('Age').addEventListener('onchange', validateAge);
document.getElementById('Gender').addEventListener('onchange', validateGender);
document.getElementById('File').addEventListener('onchange', validateImage);

function checkSubmitButton() {

    if (vname && vphone && vemail && vage && vgender && vimage) {

        console.log("if");

           submitBtn.disabled = false; // Enable submit button
    }

    else {
            console.log("else");
            submitBtn.disabled = true; // Disable submit button
        }


}



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

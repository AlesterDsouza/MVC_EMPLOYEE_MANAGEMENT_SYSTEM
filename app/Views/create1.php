<?php
session_start(); // Start the session

require_once __DIR__ . '/../Models/User1.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../index.php");
    exit();
}


if (isset($_POST['submit'])) {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $mobileNumber = $_POST['MobileNumber'];
    $email = $_POST['Email'];
    $address = $_POST['Address'];

    // Initialize profile picture variable
    $profilePic = null;

    // Directory for file uploads
    $uploadDir = __DIR__ . '/../../uploads/';

    // Move uploaded file to uploads directory
    if (!empty($_FILES['ProfilePic']['name'])) {
        $profilePic = time() . '_' . $_FILES['ProfilePic']['name'];
        if (!move_uploaded_file($_FILES['ProfilePic']['tmp_name'], $uploadDir . $profilePic)) {
            echo "<div class='alert alert-danger'>Failed to upload file.</div>";
            die();
        }
    }

       // Remove "+91" and extract the 10-digit mobile number
       if (strpos($mobileNumber, '+91') === 0) {
        $mobileNumber = substr($mobileNumber, 3); // Extract digits after "+91"
    }

    // Validate the 10-digit mobile number
    if (!preg_match('/^[6-9]\d{9}$/', $mobileNumber)) {
        echo "<div class='alert alert-danger'>Invalid mobile number! Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.</div>";
        die();
    }

    // function restrictMobileNumberInput(event) {
    //     const input = event.target;
    //     const value = input.value;
    
    //     // Ensure it always starts with "+91"
    //     if (!value.startsWith("+91")) {
    //         input.value = "+91";
    //     }
    
    //     // Allow only numbers after "+91"
    //     input.value = value.replace(/[^+0-9]/g, "").replace(/(\+91)(.*?)([^0-9].*)/, "$1$2");
    //     validatePhone(); // Trigger validation
    // }
    

// $mobileNumber = preg_replace('/^\+91/', '', $mobileNumber);

// Validate Indian mobile number (10 digits starting with 6-9)
// if (!preg_match('/^[6-9]\d{9}$/', $mobileNumber)) {
//   echo "<div class='alert alert-danger'>Invalid mobile number! Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.</div>";
//   die();
// }

    // // Validate Indian mobile number (starts with 6-9 and is 10 digits long)
    // if (!preg_match('/^[6-9]\d{9}$/', $mobileNumber)) {
    //         echo "<div class='alert alert-danger'>Invalid mobile number! Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.</div>";
    //         die();
    // }
    

    // if (!move_uploaded_file($_FILES['ProfilePic']['tmp_name'], __DIR__ . '/../uploads/' . $profilePic)) {


    //     sudo chmod -R 755 /path/to/uploads
    //     sudo chown -R www-data:www-data /path/to/uploads
             
        
    //     After making these changes, your code should look like this:
        
    //     if (!empty($_FILES['ProfilePic']['name'])) {
    //         $profilePic = time() . '_' . $_FILES['ProfilePic']['name'];
    //         $uploadPath = __DIR__ . '/../uploads/' . $profilePic;
        
    //         if (!move_uploaded_file($_FILES['ProfilePic']['tmp_name'], $uploadPath)) {
    //             echo "<div class='alert alert-danger'>Failed to upload file.</div>";
    //             die();
    //         }
    //     }
        

    $user = new User1();

    if ($user->mobileNumberExists($mobileNumber)) {
        echo "<div class='alert alert-danger'>Mobile number already exists. Please use a different one.</div>";
    } elseif ($user->emailExists($email)) {
        echo "<div class='alert alert-danger'>Email already exists. Please use a different one.</div>";
    } else {
        $user->create1($firstName, $lastName, $mobileNumber, $email, $address, $profilePic);
        echo "<div class='alert alert-success'>User created successfully!</div>";
        header('Location: user_list1.php');
        exit();
    }
}
$existingImage = false; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> 
    <!-- <style>
        .intl-tel-input,
.iti{
  width: 100%;
} -->
<!-- </style> -->
</head>
<body onload="validateAllFields()">
<div class="container">
    <div class="form-container">
        <h2 class="text-center">Create New User</h2>
        <!-- <form action="create1.php" method="post" enctype="multipart/form-data" autocomplete="off"> -->
        <form action="../Controllers/User1Controller.php?action=create1" method="post" enctype="multipart/form-data" autocomplete="off" id="createUserForm">
            <div class="form-group">
                <label for="FirstName">First Name</label>
                <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                <div id="first-name-error" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="LastName">Last Name</label>
                <input type="text" class="form-control" id="LastName" name="LastName" required>
                <div id="last-name-error" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="MobileNumber">Mobile Number</label>
                <input type="text" class="form-control" id="MobileNumber" placeholder= "+91" name="MobileNumber" maxlength="10" required>
                <div id="phone-error" class="error-message"></div>
            </div>

            <!-- <select id="country">
   <option value="">Select Country</option>
   <option value="1">US</select>
   <option value="91">IN</select>
</select><br/>
<input type="text" class="form-control" id="MobileNumber" placeholder= "+91" name="MobileNumber" required> -->

            <!-- <div class="form-group">
  <label for="MobileNumber">Mobile Number</label>
  <input 
    type="text" 
    class="form-control" 
    id="MobileNumber" 
    name="MobileNumber" 
    value="+91" 
    required 
    onfocus="setCaretPosition(this, 3)"
    oninput="restrictMobileNumberInput(event)">
  <div id="phone-error" class="error-message"></div>
</div> -->



            <div class="form-group">
                <label for="Email">Email</label>
                <input type="email" class="form-control" id="Email" name="Email" required>
                <div id="email-error" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="Address">Address</label>
                <textarea class="form-control" id="Address" name="Address" rows="3" required></textarea>
                <div id="address-error" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="ProfilePic">Profile Picture</label>
                <input type="file" class="form-control-file" id="ProfilePic" name="ProfilePic" accept=".jpg, .png" required>
                <div id="image-error" class="error-message"></div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block" id="submitBtn" disabled>Add User</button>
        </form>
    </div>
</div>
<!-- 
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js" defer></script> -->
<script>

// -----Country Code Selection
// $("#MobileNumber").intlTelInput({
// 	initialCountry: "in",
// 	separateDialCode: true,
// 	// utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
// });    


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
// function validatePhone() {

//     var phoneInput = document.getElementById('MobileNumber').value; 
//     // const regex = /^(?:\+91[-\s]?)?[789]\d{9}$/; 
//     const regex = ^[6-9]\d{9}$;
 
//     if (regex.test(phoneInput)) { 
//         phoneError.innerHTML = "Valid phone number111!"; 
//         phoneError.style.color = "green"; 
//                 // Here you can proceed with form submission if needed 
//         vPhone = true;        
//     } else { 
//         phoneError.innerHTML = "Invalid phone number! Please enter a valid 10-digit mobile number starting with 7, 8, or 9."; 
//         phoneError.style.color = "red";
//         vPhone = false; 
//         } 


//     checkSubmitButton();
// }


function validatePhone() {
    const phoneInput = document.getElementById('MobileNumber').value;
    const regex = /^[6-9]\d{9}$/;
    // const regex = /^((\+91?)|\+)?[7-9][0-9]{9}$/;
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
        }else if (file.size > 5 * 1024 * 1024) { // 5MB limit
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
    } 
    else if (!existingImage) {
        // If no file is selected and no existing image
        imageError.innerHTML = 'Would you like to upload a profile picture';
        imageError.style.color= 'red';
        imageError.classList.remove('success');
        imageError.classList.add('error');
        vImage = true;
    }
    else if (!file) {
        imageError.innerHTML = 'Image is required';

        imageError.classList.remove('success');
        imageError.classList.add('error');
        vImage = false;
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
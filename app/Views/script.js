
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
    var phoneInput = document.getElementById('MobileNumber');
    var phone = phoneInput.value;

    phone = phone.replace(/\D/g, ''); // Remove any non-digit characters
    if (phone.length > 10) {
        phone = phone.slice(0, 10); // Limit the input to the first 10 digits
    }
    phoneInput.value = phone; // Update the input value to exclude extra digits

    if (phone.length === 0) {
        phoneError.innerHTML = 'Phone number is required';
        phoneError.style.color = 'red';
        phoneError.classList.remove('success');
        phoneError.classList.add('error');
        vPhone = false;
    } else if (phone.length !== 10) {
        phoneError.innerHTML = 'Phone number must be exactly 10 digits';
        phoneError.style.color = 'red';
        phoneError.classList.remove('success');
        phoneError.classList.add('error');
        vPhone = false;
    } else {
        phoneError.innerHTML = 'Valid Phone Number';
        phoneError.style.color = 'green';
        phoneError.classList.remove('error');
        phoneError.classList.add('success');
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
            imageError.classList.remove('success');
            imageError.classList.add('error');
            vImage = false;
        } else if (file.size > 5 * 1024 * 1024) { // 5MB limit
            imageError.innerHTML = 'Image size must not exceed 5MB';
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
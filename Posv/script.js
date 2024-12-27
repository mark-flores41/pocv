// Elements
const loginForm = document.getElementById('login-form');
const signupForm = document.getElementById('signup-form');
const signupLink = document.getElementById('signup-link');
const loginLink = document.getElementById('login-link');

// Show Signup Form
signupLink.addEventListener('click', (e) => {
    e.preventDefault();
    loginForm.classList.add('hidden');
    signupForm.classList.remove('hidden');
});

// Show Login Form
loginLink.addEventListener('click', (e) => {
    e.preventDefault();
    signupForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
});
// Script for switching between login and signup forms (if needed)

document.getElementById('signup-link').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = 'signup.php'; // Redirect to the signup page
});

document.getElementById('login-link').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = 'login.php'; // Redirect to the login page
});



// Login Form AJAX
const loginForm = document.querySelector('.login-form');
loginForm.addEventListener('submit', function(e) {
  e.preventDefault();

  const email = document.querySelector('input[name="email"]').value;
  const password = document.querySelector('input[name="password"]').value;

  const data = new FormData();
  data.append('email', email);
  data.append('password', password);

  fetch('login_server.php', {
    method: 'POST',
    body: data
  })
  .then(response => response.text())
  .then(responseText => {
    if (responseText.startsWith('redirect:')) {
      window.location.href = responseText.replace('redirect:', '');
    } else {
      alert(responseText); // Show error message
    }
  })
  .catch(error => console.error('Error:', error));
});

// Sign Up Form AJAX
const signupForm = document.querySelector('.signup-form');
signupForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const name = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const role = document.querySelector('select[name="role"]').value;

    const data = new FormData();
    data.append('name', name);
    data.append('email', email);
    data.append('password', password);
    data.append('role', role);

    fetch('signup_server.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.text())
    .then(responseText => {
        if (responseText.startsWith('redirect:')) {
            // Check if the redirection is to the login page
            const redirectUrl = responseText.replace('redirect:', '');
            
            // Update form view to show the login form
            formContainer.style.transform = 'translateX(0)'; // Show Login form
        } else {
            alert(responseText); // Show error message
        }
    })
    .catch(error => console.error('Error:', error));
});

const formContainer = document.getElementById('form-container');
const showSignupBtn = document.getElementById('show-signup');
const showLoginBtn = document.getElementById('show-login');

// Clear the form fields when switching to the Sign Up form
showSignupBtn.addEventListener('click', () => {
  // Clear Login form fields
  const loginForm = document.querySelector('.login-form');
  loginForm.reset();  // Reset the login form fields

  // Switch to Sign Up form
  formContainer.style.transform = 'translateX(-50%)';
});

// Clear the form fields when switching to the Login form
showLoginBtn.addEventListener('click', () => {
  // Clear Sign Up form fields
  const signupForm = document.querySelector('.signup-form');
  signupForm.reset();  // Reset the signup form fields

  // Switch to Login form
  formContainer.style.transform = 'translateX(0)';
});

// Fade in login form from the left on page load
window.addEventListener('DOMContentLoaded', () => {
  anime({
    targets: '#login-wrapper',
    opacity: [0, 1],
    translateX: ['-100px', '0px'],
    duration: 1000,
    easing: 'easeOutExpo'
  });
});
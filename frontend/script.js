// Redirect to Sign Up Page
document.getElementById('signup-btn').addEventListener('click', function() {
    window.location.href = 'signup.html';
});

// Redirect to Login Page
document.getElementById('login-btn').addEventListener('click', function() {
    window.location.href = 'login.html';
});

// Sign Up Form Submission
document.getElementById('signup-form')?.addEventListener('submit', function(event) {
    event.preventDefault();
    const firstName = document.getElementById('first-name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;
    const department = document.getElementById('department').value;
    const position = document.getElementById('position').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Validation
    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }

    // Save user
    const users = JSON.parse(localStorage.getItem('users')) || [];
    users.push({
        firstName,
        lastName,
        email,
        department,
        position,
        password
    });
    localStorage.setItem('users', JSON.stringify(users));

    alert('Sign Up Successful!');
    window.location.href = 'login.html';
});
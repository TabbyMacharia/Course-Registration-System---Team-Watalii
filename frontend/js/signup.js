document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.getElementById('signup-form');
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('toggle-password');

    // Toggle Password Visibility
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePasswordBtn.classList.toggle('fa-eye', !isPassword);
            togglePasswordBtn.classList.toggle('fa-eye-slash', isPassword);
        });
    }

    // Handle Form Submission
    if (signupForm) {
        signupForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(signupForm);

            fetch('../../backend/api/signup_action.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    window.location.href = 'login.html';
                }
            })
            .catch(err => {
                console.error('Signup error:', err);
                alert('An error occurred during account creation. Please try again.');
            });
        });
    }
});
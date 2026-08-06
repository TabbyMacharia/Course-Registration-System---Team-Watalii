document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const usernameInput = document.getElementById('username');
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

    // Handle Login Submit
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const username = usernameInput.value.trim();
            const password = passwordInput.value;

            if (!username || !password) {
                alert('Please fill in both fields.');
                return;
            }

            const formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);

            fetch('../../backend/api/login_action.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'courses.html';
                } else {
                    alert(data.message || 'Invalid username or password.');
                }
            })
            .catch(err => {
                console.error('Login error:', err);
                alert('An error occurred during authentication.');
            });
        });
    }
});
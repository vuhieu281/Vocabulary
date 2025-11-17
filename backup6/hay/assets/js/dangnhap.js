document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    const emailInput = loginForm.querySelector('input[type="email"]');
    const passwordInput = loginForm.querySelector('input[type="password"]');

    // Show error message
    function showError(input, message) {
        const formGroup = input.parentElement;
        let errorDiv = formGroup.querySelector('.error-message');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            formGroup.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;
        formGroup.classList.add('error');
    }

    // Clear error message
    function clearError(input) {
        const formGroup = input.parentElement;
        const errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        formGroup.classList.remove('error');
    }

    // Validate email format
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Email validation on blur
    emailInput.addEventListener('blur', function() {
        clearError(emailInput);
        if (!this.value) {
            showError(emailInput, 'Vui lòng nhập email');
        } else if (!isValidEmail(this.value)) {
            showError(emailInput, 'Email không hợp lệ');
        }
    });

    // Password validation on blur
    passwordInput.addEventListener('blur', function() {
        clearError(passwordInput);
        if (!this.value) {
            showError(passwordInput, 'Vui lòng nhập mật khẩu');
        }
    });

    // Form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;

        // Clear all previous errors
        document.querySelectorAll('.error-message').forEach(error => error.remove());

        // Validate email
        if (!emailInput.value || !isValidEmail(emailInput.value)) {
            showError(emailInput, 'Email không hợp lệ');
            isValid = false;
        }

        // Validate password
        if (!passwordInput.value) {
            showError(passwordInput, 'Vui lòng nhập mật khẩu');
            isValid = false;
        }

        if (isValid) {
            // Create login data object
            const loginData = {
                email: emailInput.value,
                password: passwordInput.value
            };

            // Simulate login (replace with actual API call)
            console.log('Login attempt:', loginData);

            // Simulated successful login
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userEmail', emailInput.value);

            // Show success message and redirect
            alert('Đăng nhập thành công!');
            window.location.href = './index.html';
        }
    });
});
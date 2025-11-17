document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.signup-form');
    const emailInput = form.querySelector('input[type="email"]');
    const usernameInput = form.querySelector('input[placeholder="Tên người dùng"]');
    const passwordInput = form.querySelector('input[placeholder="Mật khẩu"]');
    const confirmPasswordInput = form.querySelector('input[placeholder="Xác nhận mật khẩu"]');

    // Validate email format
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Validate password strength
    function isValidPassword(password) {
        // Ít nhất 8 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    }

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

    // Real-time email validation
    emailInput.addEventListener('blur', function() {
        clearError(emailInput);
        if (!isValidEmail(this.value)) {
            showError(emailInput, 'Email không hợp lệ');
        }
    });

    // Real-time username validation
    usernameInput.addEventListener('blur', function() {
        clearError(usernameInput);
        if (this.value.length < 3) {
            showError(usernameInput, 'Tên người dùng phải có ít nhất 3 ký tự');
        }
    });

    // Real-time password validation
    passwordInput.addEventListener('blur', function() {
        clearError(passwordInput);
        if (!isValidPassword(this.value)) {
            showError(passwordInput, 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt');
        }
    });

    // Real-time confirm password validation
    confirmPasswordInput.addEventListener('input', function() {
        clearError(confirmPasswordInput);
        if (this.value !== passwordInput.value) {
            showError(confirmPasswordInput, 'Mật khẩu không khớp');
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;

        // Clear all previous errors
        document.querySelectorAll('.error-message').forEach(error => error.remove());

        // Validate email
        if (!isValidEmail(emailInput.value)) {
            showError(emailInput, 'Email không hợp lệ');
            isValid = false;
        }

        // Validate username
        if (usernameInput.value.length < 3) {
            showError(usernameInput, 'Tên người dùng phải có ít nhất 3 ký tự');
            isValid = false;
        }

        // Validate password
        if (!isValidPassword(passwordInput.value)) {
            showError(passwordInput, 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt');
            isValid = false;
        }

        // Validate confirm password
        if (confirmPasswordInput.value !== passwordInput.value) {
            showError(confirmPasswordInput, 'Mật khẩu không khớp');
            isValid = false;
        }

        if (isValid) {
            // Create user data object
            const userData = {
                email: emailInput.value,
                username: usernameInput.value,
                password: passwordInput.value
            };

            // Here you would typically send the data to your backend
            console.log('Form data ready to submit:', userData);
            alert('Đăng ký thành công! Vui lòng đăng nhập.');
            window.location.href = './dangnhap.html';
        }
    });
});
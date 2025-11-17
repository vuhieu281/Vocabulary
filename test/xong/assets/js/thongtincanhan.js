document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    if (!isLoggedIn) {
        window.location.href = './dangnhap.html';
        return;
    }

    // Get form elements
    const personalInfoForm = document.querySelector('.settings-form');
    const passwordForm = document.querySelectorAll('.settings-form')[1];
    
    // Load user data from localStorage or mock data
    const userData = {
        name: localStorage.getItem('userName') || 'User Name',
        email: localStorage.getItem('userEmail') || 'user@example.com',
        phone: localStorage.getItem('userPhone') || '0123 456 789',
        address: localStorage.getItem('userAddress') || 'Hà Nội, Việt Nam'
    };

    // Populate form with user data
    document.getElementById('name').value = userData.name;
    document.getElementById('email').value = userData.email;
    document.getElementById('phone').value = userData.phone;
    document.getElementById('address').value = userData.address;

    // Personal Information Form Handler
    personalInfoForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate inputs
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const address = document.getElementById('address').value.trim();

        if (!validatePersonalInfo(name, email, phone, address)) {
            return;
        }

        // Save to localStorage (replace with API call in production)
        localStorage.setItem('userName', name);
        localStorage.setItem('userEmail', email);
        localStorage.setItem('userPhone', phone);
        localStorage.setItem('userAddress', address);

        showSuccess('Thông tin cá nhân đã được cập nhật thành công!');
    });

    // Password Change Form Handler
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (!validatePasswordChange(currentPassword, newPassword, confirmPassword)) {
            return;
        }

        // In production, send to API for password change
        console.log('Password change requested');
        showSuccess('Mật khẩu đã được thay đổi thành công!');
        this.reset();
    });

    // Validation Functions
    function validatePersonalInfo(name, email, phone, address) {
        // Name validation
        if (name.length < 2) {
            showError('Tên phải có ít nhất 2 ký tự');
            return false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('Email không hợp lệ');
            return false;
        }

        // Phone validation
        const phoneRegex = /^[0-9\s-]{10,}$/;
        if (!phoneRegex.test(phone)) {
            showError('Số điện thoại không hợp lệ');
            return false;
        }

        // Address validation
        if (address.length < 5) {
            showError('Địa chỉ phải có ít nhất 5 ký tự');
            return false;
        }

        return true;
    }

    function validatePasswordChange(current, newPass, confirm) {
        if (!current || !newPass || !confirm) {
            showError('Vui lòng điền đầy đủ thông tin');
            return false;
        }

        if (newPass.length < 8) {
            showError('Mật khẩu mới phải có ít nhất 8 ký tự');
            return false;
        }

        if (newPass !== confirm) {
            showError('Mật khẩu xác nhận không khớp');
            return false;
        }

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordRegex.test(newPass)) {
            showError('Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt');
            return false;
        }

        return true;
    }

    // UI Feedback Functions
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        
        // Remove any existing error messages
        document.querySelectorAll('.error-message').forEach(err => err.remove());
        
        // Add new error message
        document.querySelector('.container').insertBefore(errorDiv, document.querySelector('.settings-card'));
        
        // Auto remove after 3 seconds
        setTimeout(() => errorDiv.remove(), 3000);
    }

    function showSuccess(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        successDiv.textContent = message;
        
        // Remove any existing messages
        document.querySelectorAll('.success-message').forEach(msg => msg.remove());
        
        // Add new success message
        document.querySelector('.container').insertBefore(successDiv, document.querySelector('.settings-card'));
        
        // Auto remove after 3 seconds
        setTimeout(() => successDiv.remove(), 3000);
    }
});
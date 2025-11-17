/**
 * Notification System
 * Hệ thống hiển thị thông báo với các kiểu khác nhau
 */
const notifications = {
    container: null,

    init() {
        // Tạo container nếu chưa tồn tại
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },

    // Các phương thức hiển thị thông báo
    success(message, duration = 5000) {
        this.show({
            title: 'Thành công',
            message,
            type: 'success',
            duration
        });
    },

    error(message, duration = 5000) {
        this.show({
            title: 'Lỗi',
            message,
            type: 'error',
            duration
        });
    },
    
    info(message, duration = 5000) {
        this.show({
            title: 'Thông tin',
            message,
            type: 'info',
            duration
        });
    },

    // Thông báo thêm vào giỏ hàng
    addToCart(product, duration = 5000) {
        this.init();
        
        // Nếu không có thông tin sản phẩm, sử dụng thông tin mặc định
        const productInfo = product || {
            name: 'Sản phẩm',
            price: '',
            image: '',
            quantity: 1
        };
        
        // Tạo toast element
        const toast = document.createElement('div');
        toast.className = 'toast';
        
        // Tạo nội dung HTML cho toast
        toast.innerHTML = `
            <div class="toast-cart-success">
                <div class="toast-cart-header">
                    <div class="toast-cart-icon">✓</div>
                    <div class="toast-cart-title">Đã thêm vào giỏ hàng</div>
                    <button class="toast-cart-close">&times;</button>
                </div>
                <div class="toast-cart-content">
                    <img src="${productInfo.image || 'https://via.placeholder.com/65'}" class="toast-cart-product-img" alt="${productInfo.name}">
                    <div class="toast-cart-product-info">
                        <div class="toast-cart-product-name">${productInfo.name}</div>
                        <div class="toast-cart-product-price">${productInfo.price || ''}</div>
                        <div class="toast-cart-product-quantity">Số lượng: ${productInfo.quantity || 1}</div>
                    </div>
                </div>
                <div class="toast-cart-actions">
                    <button class="toast-cart-btn toast-cart-view">Xem giỏ hàng</button>
                    <button class="toast-cart-btn toast-cart-continue">Tiếp tục mua sắm</button>
                </div>
                <div class="toast-progress"></div>
            </div>
        `;
        
        // Thêm toast vào container
        this.container.appendChild(toast);
        
        // Xử lý animation cho progress bar
        const progressBar = toast.querySelector('.toast-progress');
        progressBar.style.animation = `progress ${duration/1000}s linear forwards`;
        
        // Xử lý sự kiện click cho các button
        toast.querySelector('.toast-cart-close').addEventListener('click', () => this.close(toast));
        toast.querySelector('.toast-cart-view').addEventListener('click', () => {
            window.location.href = 'giohang.php';
            this.close(toast);
        });
        toast.querySelector('.toast-cart-continue').addEventListener('click', () => this.close(toast));
        
        // Tự động đóng sau khoảng thời gian
        const timeoutId = setTimeout(() => {
            this.close(toast);
        }, duration);
        
        // Lưu timeout ID vào element để có thể hủy nếu cần
        toast._timeoutId = timeoutId;
    },
    
    // Phương thức hiển thị thông báo thường
    show(settings) {
        this.init();
        
        // Cài đặt mặc định
        const config = {
            type: 'info',
            title: 'Thông báo',
            message: '',
            duration: 5000,
            ...settings
        };
        
        // Tạo toast
        const toast = document.createElement('div');
        toast.className = `toast toast-${config.type}`;
        
        toast.innerHTML = `
            <div class="toast-header">
                <div class="toast-icon">${this.getIcon(config.type)}</div>
                <div class="toast-title">${config.title}</div>
                <button class="toast-close">&times;</button>
            </div>
            <div class="toast-message">${config.message}</div>
            <div class="toast-progress"></div>
        `;
        
        // Thêm toast vào container
        this.container.appendChild(toast);
        
        // Xử lý animation cho progress bar
        const progressBar = toast.querySelector('.toast-progress');
        progressBar.style.animation = `progress ${config.duration/1000}s linear forwards`;
        
        // Xử lý đóng toast
        toast.querySelector('.toast-close').addEventListener('click', () => this.close(toast));
        
        // Tự động đóng
        const timeoutId = setTimeout(() => {
            this.close(toast);
        }, config.duration);
        
        toast._timeoutId = timeoutId;
    },
    
    // Đóng toast
    close(toast) {
        // Hủy timeout nếu có
        if (toast._timeoutId) {
            clearTimeout(toast._timeoutId);
        }
        
        // Thêm hiệu ứng đóng
        toast.style.animation = 'slideOut 0.3s forwards';
        
        // Xóa khỏi DOM sau khi animation kết thúc
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    },
    
    // Lấy icon phù hợp với từng loại thông báo
    getIcon(type) {
        const icons = {
            'success': '✓',
            'error': '✕',
            'info': 'ℹ',
            'warning': '⚠'
        };
        return icons[type] || icons.info;
    }
};

// Khởi tạo khi trang load xong
document.addEventListener('DOMContentLoaded', () => {
    notifications.init();
});
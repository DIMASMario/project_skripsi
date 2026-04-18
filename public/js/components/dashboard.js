/**
 * Dashboard JavaScript
 * Modern dashboard functionality and interactions
 */

class Dashboard {
    constructor() {
        this.init();
    }

    init() {
        this.initSidebar();
        this.initTheme();
        this.initForms();
        this.initNotifications();
        this.initCharts();
    }

    // Sidebar functionality
    initSidebar() {
        const sidebarToggle = document.querySelector('#sidebar-toggle');
        const sidebar = document.querySelector('.dashboard-sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        });
    }

    // Theme switcher
    initTheme() {
        const themeToggle = document.querySelector('#theme-toggle');
        const html = document.documentElement;
        
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const isDark = html.classList.contains('dark');
                
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }

        // Apply saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            html.classList.add('dark');
        }
    }

    // Form enhancements
    initForms() {
        // Auto-resize textareas
        const textareas = document.querySelectorAll('textarea[data-auto-resize]');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', () => {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            });
        });

        // Form validation feedback
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });

        // Password visibility toggle
        const passwordToggles = document.querySelectorAll('[data-password-toggle]');
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const input = toggle.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                const icon = toggle.querySelector('span');
                if (icon) {
                    icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
                }
            });
        });
    }

    // Form validation
    validateForm(form) {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                this.showFieldError(input, 'Field ini wajib diisi');
                isValid = false;
            } else {
                this.clearFieldError(input);
            }
        });

        return isValid;
    }

    showFieldError(field, message) {
        field.classList.add('border-red-500');
        
        let errorDiv = field.parentNode.querySelector('.field-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'field-error text-red-500 text-sm mt-1';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    clearFieldError(field) {
        field.classList.remove('border-red-500');
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // Notification system
    initNotifications() {
        // Auto-hide flash messages
        const flashMessages = document.querySelectorAll('[data-flash-message]');
        flashMessages.forEach(message => {
            setTimeout(() => {
                message.style.transition = 'opacity 0.5s';
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 500);
            }, 5000);
        });
    }

    // Simple charts (if needed)
    initCharts() {
        // This can be expanded with Chart.js or other charting libraries
        const chartElements = document.querySelectorAll('[data-chart]');
        chartElements.forEach(element => {
            // Initialize charts here if needed
        });
    }

    // Utility functions
    static showLoader(button) {
        const originalText = button.textContent;
        button.textContent = 'Loading...';
        button.disabled = true;
        button.dataset.originalText = originalText;
    }

    static hideLoader(button) {
        button.textContent = button.dataset.originalText || 'Submit';
        button.disabled = false;
    }

    static showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${this.getToastClass(type)}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    static getToastClass(type) {
        const classes = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-black',
            info: 'bg-blue-500 text-white'
        };
        return classes[type] || classes.info;
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Dashboard();
});

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Dashboard;
}
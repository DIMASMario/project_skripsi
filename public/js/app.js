/**
 * DESA TANJUNG BARU - Main JavaScript File
 * Contains custom JavaScript functionality (Alpine.js loaded separately)
 */

// REMOVED: Dynamic script loading (scripts now loaded directly in base.php with defer)
// This prevents duplicate loading and race conditions

// Custom JavaScript Functions
document.addEventListener('DOMContentLoaded', function() {
    
    console.log('🟢 App.js loaded');
    
    // REMOVED: Smooth scroll handler (handled by frontend.js)
    // This prevents duplicate event listeners

    // Loading state management
    window.showLoading = function() {
        const loader = document.createElement('div');
        loader.id = 'global-loader';
        loader.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loader.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-gray-700">Loading...</span>
            </div>
        `;
        document.body.appendChild(loader);
    };

    window.hideLoading = function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.remove();
        }
    };

    // Form validation helpers
    window.validateForm = function(formId) {
        const form = document.getElementById(formId);
        if (!form) return false;
        
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        return isValid;
    };

    // File upload preview
    window.previewFile = function(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Toast notifications
    window.showToast = function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${getToastClass(type)}`;
        toast.innerHTML = `
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2">${getToastIcon(type)}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    };

    function getToastClass(type) {
        switch (type) {
            case 'success': return 'bg-green-500 text-white';
            case 'error': return 'bg-red-500 text-white';
            case 'warning': return 'bg-yellow-500 text-white';
            default: return 'bg-blue-500 text-white';
        }
    }

    function getToastIcon(type) {
        switch (type) {
            case 'success': return 'check_circle';
            case 'error': return 'error';
            case 'warning': return 'warning';
            default: return 'info';
        }
    }

    // Mobile menu toggle
    window.toggleMobileMenu = function() {
        const menu = document.getElementById('mobile-menu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    };

    // Search functionality
    window.initializeSearch = function(searchInput, searchTarget) {
        const input = document.getElementById(searchInput);
        const target = document.getElementById(searchTarget);
        
        if (input && target) {
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const items = target.querySelectorAll('[data-searchable]');
                
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    };

    // Copy to clipboard
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard', 'success');
        }).catch(() => {
            showToast('Failed to copy', 'error');
        });
    };

    // Format date
    window.formatDate = function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute bg-gray-800 text-white text-sm rounded py-1 px-2 z-50';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.id = 'tooltip-' + Math.random().toString(36).substr(2, 9);
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + 'px';
            tooltip.style.top = (rect.bottom + 5) + 'px';
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltips = document.querySelectorAll('[id^="tooltip-"]');
            tooltips.forEach(t => t.remove());
        });
    });
});

// Alpine.js Data Components
window.alpineData = {
    // Mobile menu data
    mobileMenu() {
        return {
            isOpen: false,
            toggle() {
                this.isOpen = !this.isOpen;
            },
            close() {
                this.isOpen = false;
            }
        };
    },
    
    // Search functionality
    search() {
        return {
            query: '',
            results: [],
            isLoading: false,
            
            async performSearch() {
                if (this.query.length < 2) {
                    this.results = [];
                    return;
                }
                
                this.isLoading = true;
                // Implement search logic here
                setTimeout(() => {
                    this.isLoading = false;
                }, 500);
            }
        };
    },
    
    // Form handling
    form() {
        return {
            data: {},
            errors: {},
            isSubmitting: false,
            
            async submit() {
                this.isSubmitting = true;
                this.errors = {};
                
                try {
                    // Form submission logic
                    console.log('Submitting form:', this.data);
                } catch (error) {
                    console.error('Form submission error:', error);
                } finally {
                    this.isSubmitting = false;
                }
            }
        };
    },
    
    // Notification system
    notifications() {
        return {
            items: [],
            
            add(message, type = 'info') {
                const id = Date.now();
                this.items.push({ id, message, type });
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    this.remove(id);
                }, 5000);
            },
            
            remove(id) {
                this.items = this.items.filter(item => item.id !== id);
            }
        };
    }
};

// Export for use in other files
window.DesaTanjungBaru = {
    showLoading: window.showLoading,
    hideLoading: window.hideLoading,
    validateForm: window.validateForm,
    previewFile: window.previewFile,
    showToast: window.showToast,
    toggleMobileMenu: window.toggleMobileMenu,
    initializeSearch: window.initializeSearch,
    copyToClipboard: window.copyToClipboard,
    formatDate: window.formatDate,
    alpineData: window.alpineData
};
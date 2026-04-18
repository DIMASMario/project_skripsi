/**
 * Base Layout JavaScript
 * Core functionality for layout components
 */

// Set base URL globally
window.BASE_URL = window.BASE_URL || '';

// Notification system for warga
const NotificationSystem = {
    urls: {
        list: window.BASE_URL + 'dashboard/notifikasi',
        markRead: window.BASE_URL + 'dashboard/notifikasi/read/',
        markAllRead: window.BASE_URL + 'dashboard/notifikasi/read-all'
    },

    init: function() {
        if (this.isWargaLoggedIn()) {
            this.loadNotifications();
            this.startAutoRefresh();
        }
    },

    isWargaLoggedIn: function() {
        return document.body.classList.contains('logged-in-warga') || 
               document.querySelector('[data-user-role="warga"]') !== null;
    },

    loadNotifications: function() {
        fetch(this.urls.list)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    this.updateUI(data.data, data.count);
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
    },

    updateUI: function(notifications, count) {
        const indicator = document.getElementById('notifikasi-indicator');
        const list = document.getElementById('notifikasi-list');
        
        // Update indicator
        if (indicator) {
            indicator.style.display = count > 0 ? 'block' : 'none';
        }
        
        // Update list
        if (list) {
            if (notifications.length === 0) {
                list.innerHTML = '<div class="p-4 text-center text-gray-500">Tidak ada notifikasi</div>';
            } else {
                let html = '';
                notifications.forEach(item => {
                    const isUnread = item.status === 'belum_dibaca';
                    const timeAgo = this.getTimeAgo(item.created_at);
                    
                    html += `
                        <div class="p-4 border-b border-border-light dark:border-border-dark cursor-pointer hover:bg-primary/5 ${isUnread ? 'bg-blue-50 dark:bg-blue-900/20' : ''}" onclick="NotificationSystem.markAsRead(${item.id})">
                            <p class="text-sm ${isUnread ? 'font-semibold' : ''}">${item.pesan}</p>
                            <p class="text-xs text-gray-500 mt-1">${timeAgo}</p>
                        </div>
                    `;
                });
                list.innerHTML = html;
            }
        }
    },

    markAsRead: function(id) {
        fetch(this.urls.markRead + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                this.loadNotifications();
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    },

    getTimeAgo: function(dateString) {
        const now = new Date();
        const date = new Date(dateString);
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Baru saja';
        if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' menit lalu';
        if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' jam lalu';
        return Math.floor(diffInSeconds / 86400) + ' hari lalu';
    },

    startAutoRefresh: function() {
        setInterval(() => {
            this.loadNotifications();
        }, 30000); // Refresh every 30 seconds
    }
};

// Mobile menu functionality
const MobileMenu = {
    toggle: function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            mobileMenu.classList.toggle('hidden');
        }
    },

    close: function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            mobileMenu.classList.add('hidden');
        }
    },

    init: function() {
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = document.querySelector('[onclick="toggleMobileMenu()"]');
            
            if (mobileMenu && !mobileMenu.contains(e.target) && !menuButton.contains(e.target)) {
                this.close();
            }
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) { // lg breakpoint
                this.close();
            }
        });
    }
};

// REMOVED: Smooth scrolling (handled by frontend.js to prevent triple event listeners)

// Dark mode toggle
const DarkMode = {
    init: function() {
        // Check for saved theme preference or default to light
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.className = currentTheme;
        
        // Listen for theme toggle button clicks
        const toggleBtn = document.getElementById('theme-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', this.toggle.bind(this));
        }
    },

    toggle: function() {
        const currentTheme = document.documentElement.className;
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.className = newTheme;
        localStorage.setItem('theme', newTheme);
        
        // Update toggle button icon if exists
        this.updateToggleIcon(newTheme);
    },

    updateToggleIcon: function(theme) {
        const toggleBtn = document.getElementById('theme-toggle');
        if (toggleBtn) {
            const icon = toggleBtn.querySelector('.material-symbols-outlined');
            if (icon) {
                icon.textContent = theme === 'dark' ? 'light_mode' : 'dark_mode';
            }
        }
    }
};

// Form utilities
const FormUtils = {
    validateEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    validatePhone: function(phone) {
        const re = /^[0-9+\-\s()]+$/;
        return re.test(phone);
    },

    showLoading: function(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="loading-spinner"></span> Loading...';
        button.disabled = true;
        
        return function() {
            button.innerHTML = originalText;
            button.disabled = false;
        };
    }
};

// Utility functions for global use
window.toggleMobileMenu = function() {
    MobileMenu.toggle();
};

window.loadNotifikasiWarga = function() {
    NotificationSystem.loadNotifications();
};

window.updateNotifikasiWargaUI = function(notifikasi, count) {
    NotificationSystem.updateUI(notifikasi, count);
};

window.markAsReadWarga = function(id) {
    NotificationSystem.markAsRead(id);
};

window.getTimeAgo = function(dateString) {
    return NotificationSystem.getTimeAgo(dateString);
};

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('🟢 Layout.js loaded');
    
    // Initialize all components
    NotificationSystem.init();
    MobileMenu.init();
    // REMOVED: SmoothScroll.init() - handled by frontend.js to prevent duplicate listeners
    DarkMode.init();
    
    // Add body class for logged in users (for easier JS detection)
    if (document.querySelector('[data-user-role]')) {
        const role = document.querySelector('[data-user-role]').dataset.userRole;
        document.body.classList.add(`logged-in-${role}`);
    }
});

// Handle page visibility change to refresh notifications
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && NotificationSystem.isWargaLoggedIn()) {
        NotificationSystem.loadNotifications();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        NotificationSystem,
        MobileMenu,
        // REMOVED: SmoothScroll
        DarkMode,
        FormUtils
    };
}
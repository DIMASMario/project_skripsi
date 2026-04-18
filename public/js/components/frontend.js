/**
 * Frontend JavaScript
 * Modern frontend functionality for Desa Blanakan website
 */

class Frontend {
    constructor() {
        this.init();
    }

    init() {
        this.initNavigation();
        this.initTheme();
        this.initAnimations();
        this.initForms();
        this.initGallery();
        this.initSearch();
        this.initCarousel();
    }

    // Navigation functionality
    initNavigation() {
        const mobileMenuToggle = document.querySelector('#mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileMenuClose = document.querySelector('#mobile-menu-close');
        
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.add('open');
            });
        }

        if (mobileMenuClose && mobileMenu) {
            mobileMenuClose.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (mobileMenu && !mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                mobileMenu.classList.remove('open');
            }
        });

        // Smooth scrolling for anchor links ONLY (not regular links)
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        console.log(`🟢 Frontend.js: Found ${anchorLinks.length} anchor links for smooth scroll`);
        
        anchorLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                
                // Only preventDefault for anchor links with actual targets
                if (href && href.startsWith('#') && href.length > 1) {
                    const targetId = href;
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        e.preventDefault();
                        console.log(`🔵 Smooth scrolling to: ${targetId}`);
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            });
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

    // Animation on scroll
    initAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        const animatedElements = document.querySelectorAll('[data-animate]');
        animatedElements.forEach(element => {
            observer.observe(element);
        });
    }

    // Form enhancements
    initForms() {
        // Contact form
        const contactForm = document.querySelector('#contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleContactForm(contactForm);
            });
        }

        // Service request forms
        const serviceForm = document.querySelector('#service-form');
        if (serviceForm) {
            serviceForm.addEventListener('submit', (e) => {
                this.handleServiceForm(e, serviceForm);
            });
        }

        // File upload preview
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                this.previewFile(e.target);
            });
        });
    }

    handleContactForm(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        
        // Show loading state
        submitButton.textContent = 'Mengirim...';
        submitButton.disabled = true;

        // Simulate form submission (replace with actual API call)
        setTimeout(() => {
            submitButton.textContent = 'Pesan Terkirim!';
            form.reset();
            
            setTimeout(() => {
                submitButton.textContent = 'Kirim Pesan';
                submitButton.disabled = false;
            }, 2000);
        }, 1500);
    }

    handleServiceForm(e, form) {
        if (!this.validateServiceForm(form)) {
            e.preventDefault();
            return false;
        }
        
        // Additional processing can be added here
        return true;
    }

    validateServiceForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'Field ini wajib diisi');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        return isValid;
    }

    previewFile(input) {
        const file = input.files[0];
        if (!file) return;

        const preview = input.parentNode.querySelector('.file-preview');
        if (!preview) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            if (file.type.startsWith('image/')) {
                preview.innerHTML = `<img src="${e.target.result}" class="max-w-full h-32 object-cover rounded">`;
            } else {
                preview.innerHTML = `<div class="p-4 bg-gray-100 rounded">📄 ${file.name}</div>`;
            }
        };
        reader.readAsDataURL(file);
    }

    // Gallery functionality
    initGallery() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        galleryItems.forEach(item => {
            item.addEventListener('click', () => {
                this.openLightbox(item);
            });
        });
    }

    openLightbox(item) {
        const imageSrc = item.querySelector('img').src;
        const caption = item.dataset.caption || '';
        
        const lightbox = document.createElement('div');
        lightbox.className = 'fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4';
        lightbox.innerHTML = `
            <div class="relative max-w-4xl max-h-full">
                <img src="${imageSrc}" alt="${caption}" class="max-w-full max-h-full object-contain">
                <button class="absolute top-2 right-2 text-white text-2xl" onclick="this.parentElement.parentElement.remove()">
                    ×
                </button>
                ${caption ? `<p class="text-white text-center mt-4">${caption}</p>` : ''}
            </div>
        `;
        
        document.body.appendChild(lightbox);
        
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.remove();
            }
        });
    }

    // Search functionality
    initSearch() {
        const searchInputs = document.querySelectorAll('[data-search]');
        
        searchInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                this.performSearch(e.target.value, e.target.dataset.search);
            });
        });
    }

    performSearch(query, target) {
        const targetElements = document.querySelectorAll(`[data-searchable="${target}"]`);
        
        targetElements.forEach(element => {
            const text = element.textContent.toLowerCase();
            const matches = text.includes(query.toLowerCase());
            
            element.style.display = matches ? 'block' : 'none';
        });
    }

    // Utility functions
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

    static showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${this.getNotificationClass(type)}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transition = 'opacity 0.5s';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 4000);
    }

    static getNotificationClass(type) {
        const classes = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-black',
            info: 'bg-blue-500 text-white'
        };
        return classes[type] || classes.info;
    }

    // Hero Carousel functionality
    initCarousel() {
        const carousel = document.getElementById('hero-carousel');
        if (!carousel) return;

        const slides = carousel.querySelectorAll('.carousel-slide');
        const indicators = carousel.querySelectorAll('.carousel-indicator');
        const prevBtn = carousel.querySelector('.carousel-prev');
        const nextBtn = carousel.querySelector('.carousel-next');
        const progressBar = carousel.querySelector('.carousel-progress');

        if (slides.length === 0) return;

        let currentSlide = 0;
        let isAnimating = false;
        let autoPlayInterval;
        let progressInterval;
        const autoPlayDelay = 5000; // 5 seconds
        const progressDuration = autoPlayDelay / 100; // For smooth progress bar

        // Initialize carousel
        const initCarousel = () => {
            slides[0].classList.add('active');
            indicators[0].classList.add('active');
            startAutoPlay();
        };

        // Go to specific slide
        const goToSlide = (slideIndex, direction = 'next') => {
            if (isAnimating || slideIndex === currentSlide) return;

            isAnimating = true;
            stopAutoPlay();

            // Remove active classes
            slides[currentSlide].classList.remove('active');
            indicators[currentSlide].classList.remove('active');

            // Add transition classes
            if (direction === 'next') {
                slides[currentSlide].classList.add('prev');
                slides[slideIndex].classList.add('next');
            } else {
                slides[currentSlide].classList.add('next');
                slides[slideIndex].classList.add('prev');
            }

            // Update current slide
            currentSlide = slideIndex;

            // Add active classes to new slide
            setTimeout(() => {
                slides.forEach(slide => {
                    slide.classList.remove('prev', 'next');
                });
                slides[currentSlide].classList.add('active');
                indicators[currentSlide].classList.add('active');
                
                isAnimating = false;
                startAutoPlay();
            }, 50);
        };

        // Next slide
        const nextSlide = () => {
            const next = (currentSlide + 1) % slides.length;
            goToSlide(next, 'next');
        };

        // Previous slide
        const prevSlide = () => {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            goToSlide(prev, 'prev');
        };

        // Start auto-play with progress bar
        const startAutoPlay = () => {
            let progress = 0;
            
            progressInterval = setInterval(() => {
                progress += 1;
                if (progressBar) {
                    progressBar.style.width = progress + '%';
                }
            }, progressDuration);

            autoPlayInterval = setTimeout(() => {
                nextSlide();
            }, autoPlayDelay);
        };

        // Stop auto-play
        const stopAutoPlay = () => {
            clearTimeout(autoPlayInterval);
            clearInterval(progressInterval);
            if (progressBar) {
                progressBar.style.width = '0%';
            }
        };

        // Event listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
            });
        }

        // Indicator clicks
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                const direction = index > currentSlide ? 'next' : 'prev';
                goToSlide(index, direction);
            });
        });

        // Pause auto-play on hover
        carousel.addEventListener('mouseenter', stopAutoPlay);
        carousel.addEventListener('mouseleave', startAutoPlay);

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        carousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const swipeThreshold = 50;
            
            if (touchStartX - touchEndX > swipeThreshold) {
                nextSlide(); // Swipe left - next slide
            } else if (touchEndX - touchStartX > swipeThreshold) {
                prevSlide(); // Swipe right - previous slide
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (carousel.matches(':hover') || document.activeElement === carousel) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    nextSlide();
                }
            }
        });

        // Initialize the carousel
        initCarousel();

        // Intersection Observer for performance
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startAutoPlay();
                } else {
                    stopAutoPlay();
                }
            });
        }, { threshold: 0.5 });

        observer.observe(carousel);
    }
}

// Initialize frontend when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('🟢 Frontend.js loaded - initializing components');
    new Frontend();
    console.log('✅ Frontend components initialized');
});

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Frontend;
}
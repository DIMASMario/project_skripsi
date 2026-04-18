// Tab Switching Logic
(function() {
    console.log('tabs.js loaded');
    
    function initializeTabs() {
        console.log('Initializing tabs...');
        
        const tabButtons = document.querySelectorAll('.tab-btn');
        const allContents = document.querySelectorAll('.tab-content');
        
        console.log('Found ' + tabButtons.length + ' tab buttons');
        console.log('Found ' + allContents.length + ' tab contents');
        
        tabButtons.forEach((button) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const tabName = this.getAttribute('data-tab');
                console.log('🔵 Clicked tab: ' + tabName);
                
                // Hide all tab contents
                allContents.forEach(content => {
                    console.log('  Hiding: ' + content.id);
                    content.classList.remove('active');
                });
                
                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Show selected content
                const selectedContent = document.getElementById(tabName + '-content');
                if (selectedContent) {
                    console.log('  ✓ Showing: ' + selectedContent.id);
                    selectedContent.classList.add('active');
                } else {
                    console.error('  ✗ Content not found: ' + tabName + '-content');
                }
                
                // Highlight button
                this.classList.add('active');
                console.log('  ✓ Button highlighted');
            });
        });
    }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeTabs);
    } else {
        initializeTabs();
    }
})();

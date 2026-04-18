// Remove all outlines and focus rings JavaScript
// SIMPLIFIED VERSION - Tidak block click events!
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Remove outline script loaded (safe version)');
    
    // HANYA hapus outline saat focus, JANGAN blur() atau preventDefault()
    document.addEventListener('focus', function(e) {
        if (e.target && e.target.style) {
            e.target.style.outline = 'none';
        }
    }, true);
    
    // JANGAN tambah event listener yang block click!
    // JANGAN pakai e.target.blur()!
});

// SAFE CSS injection - hanya outline, TIDAK border atau box-shadow!
const style = document.createElement('style');
style.textContent = `
    * {
        outline: none !important;
        -webkit-tap-highlight-color: transparent !important;
        -webkit-focus-ring-color: transparent !important;
    }
    
    *:focus {
        outline: none !important;
    }
`;
document.head.appendChild(style);
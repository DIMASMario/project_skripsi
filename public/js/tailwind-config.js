/**
 * Tailwind CSS Configuration
 * Centralized configuration for consistent theming
 */

// Tailwind CSS configuration object
window.tailwindConfig = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                primary: "#005C99",
                accent: "#FFD700",
                "background-light": "#F5F7FA",
                "background-dark": "#0A121A",
                "text-light": "#333333",
                "text-dark": "#E0E0E0",
                "card-light": "#FFFFFF",
                "card-dark": "#162331",
                "border-light": "#E0E6ED",
                "border-dark": "#3A4A5B"
            },
            fontFamily: {
                display: ["Inter", "sans-serif"]
            },
            borderRadius: {
                DEFAULT: "0.5rem",
                lg: "0.75rem",
                xl: "1rem",
                full: "9999px"
            }
        }
    }
};

// Apply configuration to Tailwind if available
if (typeof tailwind !== 'undefined') {
    tailwind.config = window.tailwindConfig;
}
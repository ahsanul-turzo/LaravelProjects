import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Function to set CSRF token
function setCsrfToken() {
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        console.log('✓ CSRF token configured for axios');
    } else {
        console.error('⨯ CSRF token not found. Ensure <meta name="csrf-token"> is in <head>');
    }
}

// Set CSRF token immediately if DOM is ready, otherwise wait
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setCsrfToken);
} else {
    setCsrfToken();
}


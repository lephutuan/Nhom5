// Configuration file for API endpoints
const CONFIG = {
    // Auto-detect API base URL based on current location
    getApiBaseUrl() {
        const currentPath = window.location.pathname;
        
        // If running from root directory
        if (currentPath.includes('/Nhom5_demo/')) {
            return '/Nhom5_demo/backend/api';
        }
        
        // If running from subdirectory
        if (currentPath.includes('/backend/')) {
            return '/backend/api';
        }
        
        // Default fallback
        return '/backend/api';
    },
    
    // Alternative: Use relative path
    getRelativeApiUrl() {
        return './backend/api';
    }
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CONFIG;
} 
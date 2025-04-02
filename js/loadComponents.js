/**
 * Loads external components into the page
 * @param {string} containerId - The ID of the container element
 * @param {string} filePath - Path to the component HTML file
 */
async function loadComponent(containerId, filePath) {
    try {
        const response = await fetch(filePath);
        if (!response.ok) {
            throw new Error(`Failed to load ${filePath}: ${response.status}`);
        }
        const data = await response.text();
        document.getElementById(containerId).innerHTML = data;
    } catch (error) {
        console.error(`Error loading ${filePath}:`, error);
        document.getElementById(containerId).innerHTML = `
            <div class="component-error">
                <p>Could not load ${containerId.replace('-container', '')}. Please try refreshing the page.</p>
            </div>
        `;
    }
}

// Load components when DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Load navbar and footer components
    loadComponent('navbar-container', 'navbar/navbar.html');
    loadComponent('footer-container', 'footer/footer.html');
    
    // You can add more components here if needed
    // loadComponent('another-container', 'path/to/component.html');
});
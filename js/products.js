// products.js - All JavaScript for products page

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Modal handling
    const modal = document.getElementById('productModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.querySelector('.close-btn');
    
    if (openBtn && modal && closeBtn) {
        openBtn.addEventListener('click', () => {
            modal.style.display = 'block';
        });
        
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
        
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // Category-subcategory filtering with improved logic
  // Category-subcategory filtering
const categorySelect = document.getElementById('category');
const subcategorySelect = document.getElementById('subcategory');

if (categorySelect && subcategorySelect) {
    const allSubOptions = Array.from(subcategorySelect.options);

    subcategorySelect.disabled = true;

    categorySelect.addEventListener('change', function () {
        const selectedCategoryId = this.value;

        // Reset subcategory dropdown
        subcategorySelect.innerHTML = '';

        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Subcategory';
        subcategorySelect.appendChild(defaultOption);

        // Filter and re-append matching subcategories
        allSubOptions.forEach(option => {
            if (option.dataset.category === selectedCategoryId) {
                subcategorySelect.appendChild(option);
            }
        });

        subcategorySelect.disabled = selectedCategoryId === '';
    });

    // Trigger change on page load if needed
    categorySelect.dispatchEvent(new Event('change'));
}


    // Load navbar and footer
    fetch("navbar/navbar.php")
        .then(response => response.text())
        .then(data => {
            const navbarContainer = document.getElementById('navbar-container');
            if (navbarContainer) navbarContainer.innerHTML = data;
        })
        .catch(error => console.error('Error loading navbar:', error));

    fetch("footer/footer.html")
        .then(response => response.text())
        .then(data => {
            const footerContainer = document.getElementById('footer-container');
            if (footerContainer) footerContainer.innerHTML = data;
        })
        .catch(error => console.error('Error loading footer:', error));
});
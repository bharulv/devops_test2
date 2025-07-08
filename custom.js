document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category-tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tabGroupId = this.getAttribute('data-tab-group');
            const categorySlug = this.getAttribute('data-category');
            
            // Update active tab
            document.querySelectorAll(`#${tabGroupId} .category-tab-button`).forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Update active content
            document.querySelectorAll(`#${tabGroupId} .category-tab-pane`).forEach(pane => {
                pane.classList.remove('active');
            });
            document.querySelector(`#${tabGroupId} .category-tab-pane[data-category="${categorySlug}"]`).classList.add('active');
        });
    });
});
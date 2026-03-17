// /kwetu_con/public/assets/js/admin/app.js
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            document.querySelector('.admin-wrapper').classList.toggle('expanded');
        });
    }
    
    // Confirmation dialogs
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
    
    // Data tables sorting
    document.querySelectorAll('.admin-table th[data-sort]').forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.sort;
            const direction = this.dataset.direction === 'asc' ? 'desc' : 'asc';
            
            // Update UI
            document.querySelectorAll('.admin-table th').forEach(th => {
                th.classList.remove('sorting-asc', 'sorting-desc');
            });
            this.classList.add(`sorting-${direction}`);
            this.dataset.direction = direction;
            
            // Sort table
            sortTable(column, direction);
        });
    });
    
    function sortTable(column, direction) {
        // AJAX call to sort data
        console.log('Sorting by:', column, direction);
    }
    
    // Bulk actions
    const bulkCheckbox = document.getElementById('select-all');
    if (bulkCheckbox) {
        bulkCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.row-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Date range pickers
    flatpickr('.date-range', {
        mode: 'range',
        dateFormat: 'Y-m-d'
    });
    
    // Charts initialization
    if (document.getElementById('usersChart')) {
        const ctx = document.getElementById('usersChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Nouveaux utilisateurs',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    }
});
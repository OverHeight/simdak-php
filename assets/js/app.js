/**
 * SIMDAK — Frontend Client Interaction Helper
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Password Visibility Toggle (Eye icon)
    document.querySelectorAll('.password-toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    btn.style.color = '#5046e5';
                } else {
                    input.type = 'password';
                    btn.style.color = '#9ca3af';
                }
            }
        });
    });

    // 2. Keyboard Shortcut: Press '/' to focus search input
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        window.addEventListener('keydown', (e) => {
            if (e.key === '/' && document.activeElement !== searchInput && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'SELECT') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }

    // 3. Delete Confirmation Modal
    const deleteModal = document.getElementById('delete-modal');
    const deleteIdInput = document.getElementById('delete-employee-id');
    const deleteEmployeeName = document.getElementById('delete-employee-name');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

    document.querySelectorAll('.btn-trigger-delete').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            if (deleteIdInput) deleteIdInput.value = id;
            if (deleteEmployeeName) deleteEmployeeName.textContent = name;
            if (deleteModal) deleteModal.classList.add('active');
        });
    });

    if (cancelDeleteBtn && deleteModal) {
        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.remove('active');
        });

        // Close on background click
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.classList.remove('active');
            }
        });
    }

    // 4. Filter dropdown auto-submit
    const filterSelects = document.querySelectorAll('.auto-submit-filter');
    filterSelects.forEach(select => {
        select.addEventListener('change', () => {
            const form = select.closest('form');
            if (form) form.submit();
        });
    });
});

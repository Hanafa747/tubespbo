document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const submit = form.querySelector('button[type="submit"]');
            if (submit && !form.classList.contains('filter-bar')) {
                submit.dataset.originalText = submit.textContent;
                submit.disabled = true;
                submit.textContent = 'Memproses...';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#archives-table');

    if (!table) return;

    table.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        e.preventDefault(); // Cegah submit langsung

        const form = btn.closest('form');
        const title = btn.dataset.title || 'Yakin ingin menghapus data ini?';
        const name = btn.dataset.name || '';
        const confirmText = btn.dataset.confirm || 'Ya, hapus!';
        const cancelText = btn.dataset.cancel || 'Batal';

        Swal.fire({
            title: title,
            text: name ? `Data: ${name}` : '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        console.log("Script loaded");
    });
});

import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {
    const successMessage = document.body.getAttribute('data-success');
    const errorMessage = document.body.getAttribute('data-error');

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage,
            showConfirmButton: true,
            confirmButtonColor: '#28a745'
        });
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: errorMessage,
            showConfirmButton: true,
            confirmButtonColor: '#dc3545'
        });
    }
});

import Swal from 'sweetalert2';

function bindRekomendasiButtons() {
    const pilihButtons = document.querySelectorAll('.btn-pilih');

    pilihButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.form-rekomendasi');
            const action = form.getAttribute('data-action');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-primary me-2",
                    cancelButton: "btn btn-secondary"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Yakin ingin merekomendasikan CPMI ini?",
                text: "CPMI akan ditandai sebagai layak dan masuk ke daftar rekomendasi.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, rekomendasikan!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.action = action;
                    form.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Dibatalkan",
                        text: "CPMI tidak jadi direkomendasikan.",
                        icon: "info"
                    });
                }
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', bindRekomendasiButtons);
window.bindRekomendasiButtons = bindRekomendasiButtons; // agar bisa dipanggil ulang setelah AJAX

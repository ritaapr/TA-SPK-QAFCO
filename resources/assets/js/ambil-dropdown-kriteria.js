document.addEventListener('DOMContentLoaded', function () {
  const urlParams = new URLSearchParams(window.location.search);
  const selectedKriteriaId = urlParams.get('kriteria_id');
  const modal = document.getElementById('modalTambahSubkriteria');
  const kriteriaSelect = document.getElementById('kriteria_id');

  if (modal) {
    modal.addEventListener('show.bs.modal', function () {
      if (selectedKriteriaId && kriteriaSelect) {
        kriteriaSelect.value = selectedKriteriaId;
      }
    });
  }
});

<div class="modal fade" id="hapusKategoriModal" tabindex="-1" aria-labelledby="hapusKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="hapusKategoriModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda benar-benar yakin ingin menghapus unit kerja:</p>
                <h5 class="text-center my-3">
                    <strong id="kategoriHapusNama"></strong>
                </h5>
                <p class="text-danger small">
                    <i class="bi bi-exclamation-circle me-1"></i> <strong>Peringatan:</strong> Tindakan ini tidak dapat
                    dibatalkan. Seluruh data terkait akan dihapus secara permanen.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteKategoriForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus Unit Kerja</button>
                </form>
            </div>
        </div>
    </div>
</div>

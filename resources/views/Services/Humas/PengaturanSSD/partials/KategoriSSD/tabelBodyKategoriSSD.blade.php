@forelse ($kategoriSsd as $kategori)
    <tr>
        <td><span class="fw-semibold">{{ $kategori->NAMA_KATEGORI }}</span></td>
        <td>
            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditKategori"
                data-id="{{ $kategori->ID_KATEGORI_SSD }}" data-kategori="{{ $kategori->NAMA_KATEGORI }}">
                <i class="bi bi-pencil-square me-2"></i>
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusKategoriModal"
                data-id="{{ $kategori->ID_KATEGORI_SSD }}" data-kategori="{{ $kategori->NAMA_KATEGORI }}">
                <i class="bi bi-trash"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="2" class="text-center text-muted">Tidak ada data kategori yang cocok.</td>
    </tr>
@endforelse

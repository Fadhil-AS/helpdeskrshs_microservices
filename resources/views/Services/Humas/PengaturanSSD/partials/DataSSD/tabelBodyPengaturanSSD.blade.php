@forelse ($ssds as $ssd)
    <tr>
        <td>{{ $ssd->PERTANYAAN_SSD }}</td>
        <td>{{ Str::limit($ssd->JAWABAN_SSD, 70) }}</td>
        <td>
            {{-- Menggunakan optional() untuk mencegah error jika relasi tidak ada --}}
            <span class="fw-semibold">{{ optional($ssd->kategori)->NAMA_KATEGORI ?? 'Tanpa Kategori' }}</span>
        </td>
        <td>
            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD" data-id="{{ $ssd->ID_SSD }}"
                data-pertanyaan="{{ $ssd->PERTANYAAN_SSD }}" data-jawaban="{{ $ssd->JAWABAN_SSD }}"
                data-kategori-id="{{ $ssd->ID_KATEGORI_SSD }}">
                <i class="bi bi-pencil-square me-2"></i>
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal" data-id="{{ $ssd->ID_SSD }}"
                data-pertanyaan="{{ $ssd->PERTANYAAN_SSD }}">
                <i class="bi bi-trash"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted">Tidak ada data SSD yang cocok.</td>
    </tr>
@endforelse

@forelse ($dataComplaint as $complaint)
    <tr>
        <td><strong>{{ $complaint->ID_COMPLAINT }}</strong></td>
        <td>{{ $complaint->JUDUL_COMPLAINT ?? 'Belum ada judul' }}</td>
        <td>{{ $complaint->jenisMedia?->JENIS_MEDIA ?? '-' }}</td>
        @if ($complaint->status_klarifikasi == 'Sudah')
            <td><span class="badge bg-info">Sudah</span></td>
        @elseif ($complaint->status_klarifikasi == 'Belum')
            <td><span class="badge bg-danger text-light">Belum</span></td>
        @else
            <td><span class="badge bg-secondary">N/A</span></td>
        @endif
        <td>
            @if ($complaint->STATUS == 'Open')
                <span class="badge bg-success">Open</span>
            @elseif ($complaint->STATUS == 'On Progress')
                <span class="badge bg-warning">On Progress</span>
            @elseif ($complaint->STATUS == 'Menunggu Konfirmasi')
                <span class="badge bg-warning">Menunggu Konfirmasi</span>
            @elseif ($complaint->STATUS == 'Close')
                <span class="badge bg-danger">Close</span>
            @elseif ($complaint->STATUS == 'Banding')
                <span class="badge bg-danger">Banding</span>
            @else
                <span class="badge bg-secondary">{{ $complaint->STATUS ?? '-' }}</span>
            @endif
        </td>
        <td>
            @if ($complaint->GRANDING == 'Merah')
                <span class="badge bg-danger text-light">Merah</span>
            @elseif ($complaint->GRANDING == 'Kuning')
                <span class="badge bg-warning text-light">Kuning</span>
            @elseif ($complaint->GRANDING == 'Hijau')
                <span class="badge bg-success text-light">Hijau</span>
            @else
                <span class="badge bg-light text-dark">{{ $complaint->GRANDING ?? 'Belum dinilai' }}</span>
            @endif
        </td>
        <td>
            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal"
                data-id="{{ $complaint->ID_COMPLAINT }}" title="Lihat Detail">
                <i class="bi bi-eye me-2"></i>
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal"
                data-id="{{ $complaint->ID_COMPLAINT }}" title="Isi Klarifikasi" class="tombol-edit-klarifikasi">
                <i class="bi bi-pencil-square"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-5">
            <p class="mb-0">Data pengaduan tidak ditemukan.</p>
        </td>
    </tr>
@endforelse

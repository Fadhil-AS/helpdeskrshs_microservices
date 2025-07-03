<!-- Table -->
<div class="table-responsive">
    <table class="table align-middle">
        <thead class="border-bottom">
            <tr class="text-nowrap">
                <th>ID</th>
                <th>Judul</th>
                <th>Media</th>
                <th>Unit Kerja</th>
                <th>Waktu Respon</th>
                <th>Status</th>
                <th>Klarifikasi</th>
                <th>Grading</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($dataComplaint) && $dataComplaint != null)
                @foreach ($dataComplaint as $dc)
                    <tr>
                        <td><strong>{{ $dc->ID_COMPLAINT }}</strong></td>
                        @if ($dc->JUDUL_COMPLAINT != null)
                            <td>{{ $dc->JUDUL_COMPLAINT }}</td>
                        @else
                            <td>Belum ada judul</td>
                        @endif

                        @if ($dc->JenisMedia && $dc->JenisMedia->JENIS_MEDIA !== null)
                            <td>{{ $dc->JenisMedia->JENIS_MEDIA }}</td>
                        @else
                            <td>Website Helpdesk</td>
                        @endif

                        @if ($dc->unitKerja && $dc->unitKerja->NAMA_BAGIAN != null)
                            <td>{{ $dc->unitKerja->NAMA_BAGIAN }}</td>
                        @else
                            <td>Belum dipilih unit kerja</td>
                        @endif

                        <td class="text-center">
                            @if (!is_null($dc->response_time))
                                @if ($dc->response_time == 0)
                                    1 Hari
                                @else
                                    {{ $dc->response_time }} Hari
                                @endif
                            @else
                                <span class="badge bg-light text-dark">N/A</span>
                            @endif
                        </td>

                        @if ($dc->STATUS == 'Open')
                            <td><span class="badge bg-success">Open</span></td>
                        @elseif ($dc->STATUS == 'On Progress')
                            <td><span class="badge bg-info">On Progress</span></td>
                        @elseif ($dc->STATUS == 'Menunggu Konfirmasi')
                            <td><span class="badge bg-warning">Menunggu Konfirmasi</span></td>
                        @elseif ($dc->STATUS == 'Close')
                            <td><span class="badge bg-danger text-light">Close</span></td>
                        @elseif ($dc->STATUS == 'Banding')
                            <td><span class="badge bg-danger text-light">Banding</span></td>
                        @endif

                        @if (!empty($dc->EVALUASI_COMPLAINT))
                            <td><span class="badge bg-info">Sudah</span></td>
                        @else
                            <td><span class="badge bg-danger text-light">Belum</span></td>
                        @endif

                        @if ($dc->GRANDING == 'Merah')
                            <td><span class="badge bg-danger text-light">Merah</span></td>
                        @elseif ($dc->GRANDING == 'Kuning')
                            <td><span class="badge bg-warning text-light">Kuning</span></td>
                        @elseif ($dc->GRANDING == 'Hijau')
                            <td><span class="badge bg-success text-light">Hijau</span></td>
                        @else
                            <td><span class="badge bg-warning text-light">Belum dipilih Grading</span></td>
                        @endif

                        <td>
                            <a href="javascript:void(0);" class="view-detail-btn" data-bs-toggle="modal"
                                data-bs-target="#detailModal" data-id="{{ $dc->ID_COMPLAINT }}">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="javascript:void(0);" class="edit-complaint-btn" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-id="{{ $dc->ID_COMPLAINT }}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="d-flex justify-content-end mt-3 page-tabel">
    <nav aria-label="Page navigation example">
        @if ($dataComplaint->hasPages())
            <ul class="pagination mb-0">

                {{-- Tombol Previous ('<<') --}}
                @if ($dataComplaint->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $dataComplaint->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                @endif

                {{-- Elemen Nomor Halaman --}}
                @foreach ($dataComplaint->links()->elements as $element)
                    {{-- "Three Dots" Separator (...) --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span
                                class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Link Halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $dataComplaint->currentPage())
                                <li class="page-item active" aria-current="page"><a class="page-link"
                                        href="#">{{ $page }}</a></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol Next ('>>') --}}
                @if ($dataComplaint->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $dataComplaint->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @endif
            </ul>
        @endif
    </nav>
</div>

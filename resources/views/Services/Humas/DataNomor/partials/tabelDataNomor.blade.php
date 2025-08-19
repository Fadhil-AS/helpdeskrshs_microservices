{{-- tabelDataNomor.blade.php --}}
<div class="container rounded container-tabel my-5 pt-2">
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Nomor WhatsApp</h5>
        <p class="mb-0">Kelola data kontak untuk sistem notifikasi</p>
    </div>

    <div class="bg-white p-3 rounded-bottom shadow-sm">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="border-bottom">
                    <tr class="text-nowrap">
                        <th style="width: 45%;">Data</th>
                        <th style="width: 45%;">Nomor HP</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataHumas as $data)
                        <tr>
                            <td>Nomor Humas</td>
                            <td><i class="bi bi-whatsapp me-2"></i>{{ $data->no_tlpn_humas ?? '-' }}</td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditNomor"
                                    data-id="{{ $data->ID_HUMAS }}" data-nama="Nomor WA {{ $data->NAMA }}"
                                    data-nomor="{{ $data->no_tlpn_humas }}" data-field="no_tlpn_humas"
                                    data-action="{{ route('humas.nomor.update', $data) }}">
                                    <i class="bi bi-pencil-square me-2" title="Edit Nomor"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor RSHS</td>
                            <td><i class="bi bi-whatsapp me-2"></i>{{ $data->no_tlpn_rshs ?? '-' }}</td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditNomor"
                                    data-id="{{ $data->ID_HUMAS }}" data-nama="Nomor RSHS ({{ $data->NAMA }})"
                                    data-nomor="{{ $data->no_tlpn_rshs }}" data-field="no_tlpn_rshs"
                                    data-action="{{ route('humas.nomor.update', $data) }}">
                                    <i class="bi bi-pencil-square me-2" title="Edit Nomor RSHS"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data nomor telepon whatsapp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

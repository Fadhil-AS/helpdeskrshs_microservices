{{-- tabelDataNomor.blade.php --}}
<div class="container rounded container-tabel my-5 pt-2">
    {{-- ... (kode notifikasi session tetap sama) ... --}}

    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Nomor WhatsApp</h5>
        <p class="mb-0">Kelola data kontak untuk sistem notifikasi</p>
    </div>

    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="border-bottom">
                    <tr class="text-nowrap">
                        <th style="width: 40%;">Data</th>
                        <th style="width: 40%;">Nomor HP</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nomor WA Humas</td>
                        <td><i class="bi bi-whatsapp me-2"></i>081234567890</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditNomor"
                                data-id="1" data-nama="Nomor Notifikasi Humas"
                                data-nomor="081234567890">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Nomor RSHS</td>
                        <td><i class="bi bi-whatsapp me-2"></i>089876543210</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditNomor"
                                data-id="2" data-nama="Nomor Notifikasi Direksi"
                                data-nomor="089876543210">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Nomor Cadangan</td>
                        <td><i class="bi bi-whatsapp me-2"></i>081122334455</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditNomor"
                                data-id="3" data-nama="Nomor Cadangan"
                                data-nomor="081122334455">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

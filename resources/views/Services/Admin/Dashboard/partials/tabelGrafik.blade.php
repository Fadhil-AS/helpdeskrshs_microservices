<div class="container container-tabel rounded my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Dashboard Admin RSHS Bandung</h5>
        <p class="mb-0">Visualisasi data pengaduan berdasarkan berbagai kategori</p>
    </div>

    <!-- Filter & Action -->
    <div class="bg-white p-4 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 grup-tombol">
                <select class="selectpicker select-panjang" data-style="btn-reset">
                    <option>Grading (Merah, Kuning, Hijau)</option>
                    <option>Sumber Media</option>
                    <option>Status Pengaduan</option>
                    <option>Unit Kerja</option>
                    <option>Jenis Laporan</option>
                    <option>Klasifikasi Pengaduan</option>
                    <option>Penyelesaian Pengaduan</option>
                </select>
                <select class="selectpicker" data-style="btn-reset">
                    <option>Semua Waktu</option>
                    <option>Bulanan</option>
                    <option>Triwulan</option>
                    <option>Semester</option>
                </select>
                <!-- Dropdown Unit Kerja -->
                <div id="unitKerjaFilterContainer" style="display: none;">
                    <select class="selectpicker select-panjang" data-style="btn-reset" id="unitKerjaFilter">
                        <option>Semua Unit Kerja</option>
                        <option>DIREKTUR UTAMA</option>
                        <option>DIREKTUR MEDIK DAN PERAWATAN</option>
                        <option>DIREKTUR SDM, PENDIDIKAN, DAN PENELITIAN</option>
                        <option>DIREKTUR PERENCANAAN DAN KEUANGAN</option>
                        <option>DIREKTUR LAYANAN OPERASIONAL</option>
                    </select>
                </div>
                <!-- Dropdown Sub Unit -->
                <div id="subUnitFilterContainer" style="display: none;">
                    <select class="selectpicker select-panjang" data-style="btn-reset" id="subUnitFilter">
                        <option>Semua Sub Unit</option>
                        <option>KOMITE MEDIK</option>
                        <option>KOMITE ETIK DAN HUKUM</option>
                        <option>KOMITE MUTU DAN KESELAMATAN PASIEN</option>
                        <option>SATUAN PEMERIKSAAN INTERN</option>
                        <option>KOMITE PENCEGAHAN DAN PENGENDALIAN INFEKSI RUMAH SAKIT</option>
                        <option>KOMITE ETIK PENELITIAN KESEHATAN</option>
                        <option>KOMITE KEPERAWATAN</option>
                        <option>KOMITE KOORDINASI PENDIDIKAN</option>
                        <option>KOMITE TENAGA KESEHATAN LAINNYA</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="chart-section border rounded p-4">
            <h4 id="chart-title">Grading (Merah, Kuning, Hijau)</h4>
            <p id="chart-subtitle">Distribusi pengaduan berdasarkan tingkat waktu penanganan komplain (Merah,
                Kuning, Hijau)</p>
            <canvas id="gradingChart" height="150"></canvas>
        </div>
    </div>
</div>

<div class="container container-tabel rounded my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Dashboard Pelaporan RSHS Bandung</h5>
        <p class="mb-0">Visualisasi data pengaduan berdasarkan berbagai kategori</p>
    </div>

    <!-- Filter & Action -->
    <div class="bg-white p-4 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 grup-tombol">
                <select class="selectpicker select-panjang" data-style="btn-reset" id="categoryFilter">
                    <option value="grading">Grading (Merah, Kuning, Hijau)</option>
                    <option value="sumberMedia">Sumber Media</option>
                    <option value="statusPengaduan">Status Pengaduan</option>
                    <option value="unitKerja">Unit Kerja</option>
                    <option value="jenisLaporan">Jenis Laporan</option>
                    <option value="klasifikasiPengaduan">Klasifikasi Pengaduan</option>
                    <option value="penyelesaianPengaduan">Penyelesaian Pengaduan</option>
                </select>
                <select class="selectpicker" data-style="btn-reset" id="timeFilter" name="time_filter">
                    <option value="semua">Semua Waktu</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="triwulan">Triwulan</option>
                    <option value="semester">Semester</option>
                </select>
                <!-- Dropdown Unit Kerja -->
                <div id="unitKerjaFilterContainer" style="display: none;">
                    <select class="selectpicker select-panjang" data-style="btn-reset" data-live-search="true"
                        id="unitKerjaFilter">
                        <option value="">Semua Unit Kerja</option>
                        @foreach ($unitKerjaList as $unit)
                            <option value="{{ $unit->ID_BAGIAN }}">{{ $unit->NAMA_BAGIAN }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Dropdown Sub Unit -->
                <div id="subUnitFilterContainer" style="display: none;">
                    <select class="selectpicker select-panjang" data-style="btn-reset" data-live-search="true"
                        id="subUnitFilter">
                        <option value="">Semua Sub Unit</option>
                        {{-- @foreach ($unitKerjaList as $unit)
                            <option value="{{ $unit->ID_BAGIAN }}">{{ $unit->NAMA_BAGIAN_SINGULAR }}</option>
                        @endforeach --}}
                    </select>
                </div>
            </div>
        </div>

        <div class="chart-section border rounded p-4">
            <h4 id="chart-title">Grading (Merah, Kuning, Hijau)</h4>
            <p id="chart-subtitle">Distribusi pengaduan berdasarkan tingkat waktu penanganan komplain (Merah,
                Kuning, Hijau)</p>
            <canvas id="gradingChart" height="150" data-api-url="{{ route('admin.dashboard.chart-data') }}"></canvas>
        </div>
    </div>
</div>

<script>
    const AllUnitKerja = @json(
        $unitKerjaList->map(function ($unit) {
            return ['id' => $unit->ID_BAGIAN, 'nama' => $unit->NAMA_BAGIAN, 'parent_id' => $unit->ID_PARENT_BAGIAN ?? null];
        }));
    document.addEventListener('DOMContentLoaded', function() {

        const categoryFilter = document.getElementById('categoryFilter');
        // ... dan semua variabel dan fungsi lainnya ...

    });
</script>

<div class="container rounded container-tabel my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Admin Unit Kerja RSHS Bandung</h5>
        <p class="mb-0">Kelola data unit kerja, struktur organisasi, dan admin unit kerja</p>
    </div>
    <!-- Filter & Action -->
    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 ">
                <div class="grup-tombol">
                    <button class="btn btn-tambah-pengaduan text-white btn-teal" data-bs-toggle="modal"
                        data-bs-target="#modalTambahAdmin">
                        <i class="bi bi-plus-circle"></i> Tambah Admin Unit Kerja
                    </button>
                </div>
                <form id="filterForm" action="{{ route('humas.unit-kerja-humas') }}" method="GET"
                    class="d-flex flex-wrap gap-2 grup-tombol align-items-center">
                    <select name="filter_unit" class="form-select" style="width: 150px;">
                        <option value="">Semua Unit</option>
                        @foreach ($allUnits as $unit)
                            <option value="{{ $unit->ID_BAGIAN }}"
                                {{ request('filter_unit') == $unit->ID_BAGIAN ? 'selected' : '' }}>
                                {{ $unit->ID_BAGIAN }} - {{ $unit->NAMA_BAGIAN }}
                            </option>
                        @endforeach
                    </select>

                    <select name="filter_status" class="form-select" style="width: 150px;">
                        <option value="">Semua Status</option>
                        <option value="Y" {{ request('filter_status') == 'Y' ? 'selected' : '' }}>Tervalidasi
                        </option>
                        <option value="N" {{ request('filter_status') == 'N' ? 'selected' : '' }}>Belum Tervalidasi
                        </option>
                    </select>
                </form>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                    placeholder="Cari Username" value="{{ request('search') }}">
            </div>
        </div>
        <!-- Table -->
        <div id="tableDataContainer">
            @include('Services.Humas.unitKerjaHumas.partials.adminUKH.admin_table_partial', [
                'admins' => $admins,
            ])
        </div>

        <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/fungsiModalTambah.js') }}"></script>
        <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/fungsiModalDetail.js') }}"></script>
        <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/fungsiModalEdit.js') }}"></script>
        <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/fungsiReset.js') }}"></script>
        <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/filteringAUKH.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const filterForm = document.getElementById('filterForm');
                const tableContainer = document.getElementById('tableDataContainer');
                if (!searchInput || !filterForm || !tableContainer) return;

                let debounceTimer;

                function fetchData(page = 1) {
                    const params = new URLSearchParams(new FormData(filterForm));
                    params.append('search', searchInput.value);
                    params.append('page', page);
                    const url = `{{ route('humas.admin.search') }}?${params.toString()}`;
                    tableContainer.style.opacity = '0.5';
                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            tableContainer.innerHTML = data.table_html;
                            tableContainer.style.opacity = '1';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            tableContainer.style.opacity = '1';
                        });
                }
                searchInput.addEventListener('keyup', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => fetchData(1), 400);
                });
                filterForm.querySelectorAll('select').forEach(select => {
                    select.addEventListener('change', () => fetchData(1));
                });
                document.addEventListener('click', event => {
                    if (event.target.closest('#tableDataContainer .pagination a')) {
                        event.preventDefault();
                        const page = new URL(event.target.closest('a').href).searchParams.get('page');
                        fetchData(page);
                    }
                });
            });
        </script>

        <!-- Pagination -->

    </div>
</div>

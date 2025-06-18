// GANTI ISI FILE JS ANDA DENGAN KODE INI YANG SUDAH DITAMBAHKAN LOG
document.addEventListener('DOMContentLoaded', function () {

    // --- 1. Definisi Elemen & Variabel (Semua di satu tempat) ---
    const categoryFilter = document.getElementById('categoryFilter');
    const timeFilter = document.getElementById('timeFilter');
    const unitKerjaFilter = document.getElementById('unitKerjaFilter');
    const subUnitFilter = document.getElementById('subUnitFilter');
    const unitKerjaContainer = document.getElementById("unitKerjaFilterContainer");
    const subUnitContainer = document.getElementById("subUnitFilterContainer");

    const chartCanvas = document.getElementById('gradingChart');
    const chartApiUrl = chartCanvas.dataset.apiUrl;
    const ctx = chartCanvas.getContext('2d');
    const chartTitle = document.getElementById('chart-title');
    const chartSubtitle = document.getElementById('chart-subtitle');
    const loadingState = document.getElementById('chart-loading-state');

    let activeChart = null;
    let dynamicChartData = {};

    // --- 2. Definisi Fungsi ---

    /**
     * Fungsi untuk membuat atau memperbarui chart.
     */
    function createChart(config) {
        // LOG #3: Lihat konfigurasi final yang akan digambar oleh Chart.js
        console.log("LOG 3: Konfigurasi yang diterima oleh fungsi createChart:", config);

        if (activeChart) activeChart.destroy();
        if (!config || !config.labels || !config.data || config.data.length === 0) { // Ditambah cek panjang data
            chartTitle.textContent = 'Data Tidak Tersedia';
            chartSubtitle.textContent = 'Tidak ada data untuk filter yang dipilih.';
            if (loadingState) loadingState.style.display = 'block';
            chartCanvas.style.display = 'none';
            return;
        }
        const filtered = { labels: [], data: [], colors: [] };
        config.data.forEach((value, index) => {
            if (value > 0) {
                filtered.labels.push(config.labels[index]);
                filtered.data.push(value);
                if (Array.isArray(config.backgroundColor)) {
                    filtered.colors.push(config.backgroundColor[index % config.backgroundColor.length]);
                }
            }
        });
        const finalBackgroundColor = Array.isArray(config.backgroundColor) ? filtered.colors : config.backgroundColor;
        if(loadingState) loadingState.style.display = 'none';
        chartCanvas.style.display = 'block';
        chartTitle.textContent = config.title;
        chartSubtitle.textContent = config.subtitle;
        activeChart = new Chart(ctx, {
            type: config.type,
            data: {
                labels: filtered.labels,
                datasets: [{ label: 'Jumlah Pengaduan', data: filtered.data, backgroundColor: finalBackgroundColor }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', display: config.type !== 'bar' },
                    datalabels: {
                        formatter: (value, context) => {
                            if (value <= 0) return null;
                            if (context.chart.config.type === 'pie') {
                                const label = context.chart.data.labels[context.dataIndex];
                                return `${label}\n${value}`;
                            }
                            return value;
                        },
                        anchor: (context) => context.chart.config.type === 'bar' ? 'end' : 'center',
                        align: (context) => context.chart.config.type === 'bar' ? 'end' : 'center',
                        color: config.type === 'pie' ? '#fff' : '#555',
                        font: { weight: 'bold' }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    /**
     * Fungsi untuk mengambil data dari server secara dinamis.
     */
    async function updateCharts() {
        const category = categoryFilter.value;
        const time = timeFilter.value;
        const unitKerjaId = unitKerjaFilter.value;
        const subUnitId = subUnitFilter.value;

        chartCanvas.style.display = 'none';
        if (loadingState) loadingState.style.display = 'block';
        chartTitle.textContent = 'Memuat data...';
        chartSubtitle.textContent = '';
        if (activeChart) activeChart.destroy();

        try {
            const params = new URLSearchParams({
                time_filter: time,
                unit_kerja_id: unitKerjaId,
                sub_unit_id: subUnitId
            });
            const response = await fetch(`${chartApiUrl}?${params.toString()}`);
            if (!response.ok) {
                throw new Error(`Gagal mengambil data (Status: ${response.status}).`);
            }
            dynamicChartData = await response.json();

            // LOG #1: Lihat semua data yang berhasil diterima dari server
            console.log("LOG 1: Semua data yang diterima dari server:", dynamicChartData);

            // LOG #2: Lihat data spesifik yang akan digunakan untuk chart saat ini
            console.log("LOG 2: Data yang akan digunakan untuk kategori '" + category + "':", dynamicChartData[category]);

            createChart(dynamicChartData[category]);
        } catch (error) {
            console.error('Error fetching chart data:', error);
            if (loadingState) loadingState.style.display = 'block';
            chartTitle.textContent = 'Gagal Memuat Data';
            chartSubtitle.textContent = error.message;
        }
    }

    /**
     * Fungsi untuk mengisi dropdown sub-unit.
     */
    function populateSubUnitFilter(selectedParentId) {
        subUnitFilter.innerHTML = '<option value="">Semua Sub Unit</option>';
        if (selectedParentId && typeof AllUnitKerja !== 'undefined') {
            const subUnits = AllUnitKerja.filter(unit => unit.parent_id == selectedParentId);
            if (subUnits.length > 0) {
                subUnits.forEach(unit => {
                    subUnitFilter.add(new Option(unit.nama, unit.id));
                });
                subUnitContainer.style.display = 'inline-block'; // Tampilkan dropdown
            } else {
                subUnitContainer.style.display = 'none'; // Sembunyikan jika tidak ada sub unit
            }
        } else {
            subUnitContainer.style.display = 'none'; // Sembunyikan jika "Semua Unit Kerja" dipilih
        }
        $(subUnitFilter).selectpicker('refresh'); // Refresh tampilan bootstrap-select
    }


    // --- 3. Pendaftaran Event Listeners ---
    categoryFilter.addEventListener('change', function () {
        if (dynamicChartData[this.value]) {
            createChart(dynamicChartData[this.value]);
        }
    });

    timeFilter.addEventListener('change', updateCharts);
    subUnitFilter.addEventListener('change', updateCharts);

    unitKerjaFilter.addEventListener('change', function() {
        populateSubUnitFilter(this.value);
        updateCharts();
    });

    // --- 4. Inisialisasi ---
    subUnitContainer.style.display = 'none'; // Sembunyikan sub-unit di awal
    updateCharts();
});

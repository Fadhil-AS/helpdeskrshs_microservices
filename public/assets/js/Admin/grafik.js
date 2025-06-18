document.addEventListener('DOMContentLoaded', function () {

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

    function createChart(config) {
        console.log("LOG 3: Konfigurasi yang diterima oleh fungsi createChart:", config);

        if (activeChart) activeChart.destroy();
        if (!config || !config.labels || !config.data || config.data.length === 0) {
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

        if (filtered.data.length === 0) {
            createChart(null);
            return;
        }
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

            console.log("LOG 1: Semua data yang diterima dari server:", dynamicChartData);

            console.log("LOG 2: Data yang akan digunakan untuk kategori '" + category + "':", dynamicChartData[category]);

            createChart(dynamicChartData[category]);
        } catch (error) {
            console.error('Error fetching chart data:', error);
            if (loadingState) loadingState.style.display = 'block';
            chartTitle.textContent = 'Gagal Memuat Data';
            chartSubtitle.textContent = error.message;
        }
    }

    function populateSubUnitFilter(selectedParentId) {
        const $select = $(subUnitFilter);

        $select.selectpicker('destroy');

        $select.empty().append('<option value="">Semua Sub Unit</option>');

        if (selectedParentId && typeof AllUnitKerja !== 'undefined') {
            const subUnits = AllUnitKerja.filter(unit => unit.parent_id == selectedParentId);

            if (subUnits.length > 0) {
                subUnits.forEach(unit => {
                    $select.append(new Option(unit.nama, unit.id));
                });
                subUnitContainer.style.display = 'inline-block';
            } else {
                subUnitContainer.style.display = 'none';
            }
        } else {
            subUnitContainer.style.display = 'none';
        }

        $select.selectpicker();
    }

    function handleCategoryChange() {
        if (categoryFilter.value === 'unitKerja') {
            unitKerjaContainer.style.display = 'inline-block';

            if (unitKerjaFilter.value) {
                 populateSubUnitFilter(unitKerjaFilter.value);
            } else {
                 subUnitContainer.style.display = 'none';
            }
        } else {
            unitKerjaContainer.style.display = 'none';
            subUnitContainer.style.display = 'none';
        }

        createChart(dynamicChartData[categoryFilter.value]);
    }

    categoryFilter.addEventListener('change', handleCategoryChange);

    timeFilter.addEventListener('change', updateCharts);
    subUnitFilter.addEventListener('change', updateCharts);

    unitKerjaFilter.addEventListener('change', function() {
        populateSubUnitFilter(this.value);
        updateCharts();
    });

    // --- 4. Inisialisasi ---
    subUnitContainer.style.display = 'none'; // Sembunyikan sub-unit di awal
    handleCategoryChange();
    updateCharts();
});

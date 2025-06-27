document.addEventListener('DOMContentLoaded', function () {

    const categoryFilter = document.getElementById('categoryFilter');
    const timeFilter = document.getElementById('timeFilter');
    const unitFilter = document.getElementById('unitKerjaFilter');
    const unitFilterContainer = document.getElementById("unitKerjaFilterContainer");

    const chartCanvas = document.getElementById('gradingChart');
    if (!chartCanvas) {
        return;
    }

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
        const category = categoryFilter ? categoryFilter.value : 'grading';
        const time = timeFilter ? timeFilter.value : 'semua';
        const unitKerjaId = unitFilter ? unitFilter.value : '';

        chartCanvas.style.display = 'none';
        if (loadingState) loadingState.style.display = 'block';
        chartTitle.textContent = 'Memuat data...';
        chartSubtitle.textContent = '';
        if (activeChart) activeChart.destroy();

        try {
            const params = new URLSearchParams({
                category: category,
                time_filter: time,
                unit_kerja_id: unitKerjaId,
            });
            const response = await fetch(`${chartApiUrl}?${params.toString()}`);
            if (!response.ok) {
                let errorText = `Gagal mengambil data (Status: ${response.status}).`;
                try {
                    const errorData = await response.json();
                    if (errorData.message) {
                        errorText += ` Pesan: ${errorData.message}`;
                    }
                } catch (e) {}
                throw new Error(errorText);
            }
            dynamicChartData = await response.json();
            if(category === 'unitKerja' && dynamicChartData[category] && dynamicChartData[category].labels.length === 0){
                createChart(null);
            } else {
                 createChart(dynamicChartData[category]);
            }
        } catch (error) {
            console.error('Error fetching chart data:', error);
            if (loadingState) loadingState.style.display = 'block';
            chartTitle.textContent = 'Gagal Memuat Data';
            chartSubtitle.textContent = error.message;
        }
    }

    function handleCategoryChange() {
        if (!categoryFilter || !unitFilterContainer) return;
        if (categoryFilter.value === 'unitKerja') {
            unitFilterContainer.style.display = 'inline-block';
        } else {
            unitFilterContainer.style.display = 'none';
        }
    }

    if (typeof $ === 'function' && $.fn.selectpicker) {
        $('#categoryFilter').on('changed.bs.select', () => {
            handleCategoryChange();
            updateCharts();
        });
        $('#timeFilter').on('changed.bs.select', updateCharts);
        $('#unitKerjaFilter').on('changed.bs.select', updateCharts);
    }
    handleCategoryChange();
    updateCharts();
});

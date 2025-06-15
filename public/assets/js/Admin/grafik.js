const chartDataSets = {
    grading: {
        labels: ['Merah', 'Kuning', 'Hijau'],
        data: [2, 4, 3],
        title: 'Grading (Merah, Kuning, Hijau)',
        subtitle: 'Distribusi pengaduan berdasarkan tingkat waktu penanganan komplain (Merah, Kuning, Hijau)',
        backgroundColor: ['#E0440E'],
        type: 'bar'
    },
    sumberMedia: {
        labels: ['SMS', 'Kotak Saran', 'Email', 'Instagram', 'Humas', 'Facebook', 'Kode QR', 'Customer Service',
            'Contact Center', 'Website', 'Twitter', 'P3JKM', 'Whatsapp', 'Media Massa', 'Lain-lain', 'Web Helpdesk'],
        data: [2, 3, 2, 3, 6, 5, 3, 4, 1, 3, 0, 3, 3, 0, 6, 3],
        title: 'Sumber Media',
        subtitle: 'Distribusi pengaduan berdasarkan sumber media pelaporan',
        backgroundColor: '#e65100',
        type: 'bar'
    },
    statusPengaduan: {
        labels: ['Open', 'On Progress', 'Close'],
        data: [3, 3, 4],
        title: 'Status Pengaduan',
        subtitle: 'Distribusi pengaduan berdasarkan status penanganan (Open, On Progress, Close)',
        backgroundColor: ['#00C853', '#FFD600', '#D50000'],
        type: 'pie'
    },
    unitKerja: {
        labels: [
            'Direktur Utama',
            'Direktur Medik dan Keperawatan',
            'Direktur SDM, Pendidikan, dan Penelitian',
            'Direktur Perencanaan dan Keuangan',
            'Direktur Layanan Operasional'
        ],
        data: [3, 2, 1, 1, 3],
        title: 'Unit Kerja',
        subtitle: 'Distribusi pengaduan berdasarkan unit kerja tujuan',
        backgroundColor: ['#E0440E'],
        type: 'bar'
    },
    jenisLaporan: {
        labels: ['Apresiasi', 'Keluhan', 'Informasi', 'Pertanyaan'],
        data: [4, 3, 3, 3],
        title: 'Jenis Laporan',
        subtitle: 'Distribusi pengaduan berdasarkan jenis laporan',
        backgroundColor: ['#2962FF', '#D84315', '#FF9800', '#2E7D32'],
        type: 'pie'
    },
    klasifikasiPengaduan: {
        labels: ['Etik', 'Sponsorship', 'Gratifikasi'],
        data: [3, 3, 3],
        title: 'Klasifikasi Pengaduan',
        subtitle: 'Distribusi pengaduan berdasarkan klasifikasi pengaduan',
        backgroundColor: ['#2962FF', '#D84315', '#FF9800'],
        type: 'pie'
    },
    penyelesaianPengaduan: {
        labels: ['Sudah Diberi Sanksi', 'Dibina'],
        data: [2, 3],
        title: 'Penyelesaian Pengaduan',
        subtitle: 'Distribusi pengaduan berdasarkan status penyelesaian',
        backgroundColor: ['#E0440E'],
        type: 'bar'
    }
};

const ctx = document.getElementById('gradingChart').getContext('2d');
let activeChart = null;

function createChart(config) {
    if (activeChart) activeChart.destroy();

    activeChart = new Chart(ctx, {
        type: config.type,
        data: {
            labels: config.labels,
            datasets: [{
                label: 'Jumlah Pengaduan',
                data: config.data,
                backgroundColor: config.backgroundColor
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                },
                datalabels: config.type === 'pie' ? {
                    color: '#fff',
                    font: { weight: 'bold', size: 21 },
                    formatter: value => value
                } : false
            }
        },
        plugins: config.type === 'pie' ? [ChartDataLabels] : []
    });

    document.getElementById("chart-title").textContent = config.title;
    document.getElementById("chart-subtitle").textContent = config.subtitle;
}

// Inisialisasi chart pertama (default grading)
createChart(chartDataSets.grading);

// Ketika kategori chart dipilih
document.querySelector('.select-panjang').addEventListener('change', function () {
    const selected = this.value;
    let selectedKey = "grading";

    if (selected === "Sumber Media") selectedKey = "sumberMedia";
    else if (selected === "Status Pengaduan") selectedKey = "statusPengaduan";
    else if (selected === "Unit Kerja") selectedKey = "unitKerja";
    else if (selected === "Jenis Laporan") selectedKey = "jenisLaporan";
    else if (selected === "Klasifikasi Pengaduan") selectedKey = "klasifikasiPengaduan";
    else if (selected === "Penyelesaian Pengaduan") selectedKey = "penyelesaianPengaduan";

    createChart(chartDataSets[selectedKey]);

    // Tampilkan/hilangkan filter "Unit Kerja" dan "Sub Unit"
    const unitKerjaDropdown = document.getElementById("unitKerjaFilterContainer");
    const subUnitDropdown = document.getElementById("subUnitFilterContainer");

    if (selectedKey === "unitKerja") {
        unitKerjaDropdown.style.display = "block";
    } else {
        unitKerjaDropdown.style.display = "none";
        subUnitDropdown.style.display = "none";
    }
});

// Saat opsi unit kerja dipilih
document.getElementById("unitKerjaFilter").addEventListener("change", function () {
    const selectedUnit = this.value;
    const subUnitContainer = document.getElementById("subUnitFilterContainer");

    // Tampilkan sub-unit hanya jika bukan "Semua Unit Kerja"
    if (
        selectedUnit &&
        selectedUnit !== 'Semua Unit Kerja' &&
        selectedUnit !== ''
    ) {
        subUnitContainer.style.display = 'block';
    } else {
        subUnitContainer.style.display = 'none';
    }
});

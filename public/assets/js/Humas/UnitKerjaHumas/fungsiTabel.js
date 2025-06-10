
    // Hapus script lama Anda dan ganti dengan ini
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.parent-row').forEach(row => {
        // Hanya tambahkan event listener jika baris memiliki ikon toggle yang terlihat
        const icon = row.querySelector('.toggle-icon');
        if (icon && icon.style.opacity !== '0') {
            row.addEventListener('click', function(event) {
                // Mengambil data dari baris yang di-klik
                const clickedRow = event.currentTarget;
                const childGroup = clickedRow.dataset.child; // Misal: "group-A"
                const clickedLevel = parseInt(clickedRow.dataset.level); // Misal: 0
                const iconElement = clickedRow.querySelector('.toggle-icon');
                const isCollapsed = iconElement.textContent.includes('▸');
                // Cari SEMUA elemen yang berpotensi menjadi anak
                const potentialChildren = document.querySelectorAll('.' + childGroup);
                potentialChildren.forEach(child => {
                    const childLevel = parseInt(child.dataset.level);
                    // INI LOGIKA KUNCINYA:
                    // Hanya proses elemen yang merupakan ANAK LANGSUNG (levelnya +1 dari yang diklik)
                    if (childLevel === clickedLevel + 1) {
                        child.style.display = isCollapsed ? 'table-row' : 'none';
                        // Jika anak ini juga punya anak, pastikan mereka juga tersembunyi saat parent-nya di-collapse
                        if (!isCollapsed) {
                            const grandChildren = document.querySelectorAll('.' + child.dataset.child);
                            grandChildren.forEach(gc => gc.style.display = 'none');
                            // Reset ikon cucu
                            const childIcon = child.querySelector('.toggle-icon');
                            if(childIcon) childIcon.textContent = '▸';
                        }
                    }
                });
                // Ubah ikon pada baris yang di-klik
                iconElement.textContent = isCollapsed ? '▾' : '▸';
            });
        }
    });
});

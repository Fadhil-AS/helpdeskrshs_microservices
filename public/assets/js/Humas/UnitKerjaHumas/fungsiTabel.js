document.querySelectorAll('.parent-row').forEach(row => {
    row.addEventListener('click', function() {
        const group = this.dataset.child;
        const icon = this.querySelector('.toggle-icon');
        const children = document.querySelectorAll(`.child-row.${group}`);
        children.forEach(child => {
            const visible = child.style.display !== 'none';
            child.style.display = visible ? 'none' : '';
        });
        icon.textContent = icon.textContent === '▸' ? '▾' : '▸';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.parent-row').forEach(row => {
        const icon = row.querySelector('.toggle-icon');
        if (icon && icon.style.opacity !== '0') {
            row.addEventListener('click', function(event) {
                const clickedRow = event.currentTarget;
                const childGroup = clickedRow.dataset.child;
                const clickedLevel = parseInt(clickedRow.dataset.level);
                const iconElement = clickedRow.querySelector('.toggle-icon');
                const isCollapsed = iconElement.textContent.includes('▸');
                const potentialChildren = document.querySelectorAll('.' + childGroup);
                potentialChildren.forEach(child => {
                    const childLevel = parseInt(child.dataset.level);
                    if (childLevel === clickedLevel + 1) {
                        child.style.display = isCollapsed ? 'table-row' : 'none';
                        if (!isCollapsed) {
                            const grandChildren = document.querySelectorAll('.' + child.dataset.child);
                            grandChildren.forEach(gc => gc.style.display = 'none');
                            const childIcon = child.querySelector('.toggle-icon');
                            if(childIcon) childIcon.textContent = '▸';
                        }
                    }
                });
                iconElement.textContent = isCollapsed ? '▾' : '▸';
            });
        }
    });
});

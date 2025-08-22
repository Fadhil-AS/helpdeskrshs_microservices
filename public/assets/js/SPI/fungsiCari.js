function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

$(document).ready(function() {
    $('#searchInput').on('keyup', debounce(function() {
        $('#filterForm').submit();
    }, 500));
});

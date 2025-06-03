
document.addEventListener('DOMContentLoaded', function() {
    // const navbarFetchUrl = "{{ route('internal.humasNavbar') }}";
    const navbarFetchUrl = "";
    fetch(navbarFetchUrl)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then((data) => {
            const navbarPlaceholder = document.getElementById("navbar-humas-placeholder");
            if (navbarPlaceholder) {
                navbarPlaceholder.innerHTML = data;
            } else {
                console.error("Navbar placeholder element not found.");
                return;
            }
            const currentPath = window.location.pathname;
            const navLinks = navbarPlaceholder.querySelectorAll(".nav-link");
            navLinks.forEach((link) => {
                const linkPath = link.getAttribute("href");
                try {
                    const linkUrl = new URL(link.href);
                    if (linkUrl.pathname === currentPath) {
                        link.classList.add("active");
                    }
                } catch (e) {
                    if (linkPath === currentPath) {
                        link.classList.add("active");
                    }
                }
            });
        })
        .catch((error) => console.error("Error loading the navbar:", error));
});

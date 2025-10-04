// SHOW MENU
const showMenu = (toggleId, navbarId, bodyId) => {
    const toggle = document.getElementById(toggleId),
        navbar = document.getElementById(navbarId),
        bodypadding = document.getElementById(bodyId);

    if (toggle && navbar) {
        toggle.addEventListener('click', () => {
            // Toggle class untuk menampilkan sidebar
            navbar.classList.toggle('show');
            // Rotate toggle button (misalnya ikon burger)
            toggle.classList.toggle('rotate');
            // Tambahkan padding pada body saat sidebar muncul
            bodypadding.classList.toggle('expander');
        });
    }
};
showMenu('nav-toggle', 'navbar', 'body');

// LINK ACTIVE COLOR
const linkColor = document.querySelectorAll('.nav__link');   

// Simpan state aktif di localStorage saat link diklik
linkColor.forEach(l => {
    l.addEventListener('click', function() {
        localStorage.setItem('activeLink', this.getAttribute('data-href'));
    });
});

// Ambil state aktif dari localStorage saat halaman dimuat
window.addEventListener('load', () => {
    const activeLink = localStorage.getItem('activeLink');
    linkColor.forEach(l => {
        if (l.getAttribute('data-href') === activeLink) {
            l.classList.add('active');
        } else {
            l.classList.remove('active');
        }
    });
});
